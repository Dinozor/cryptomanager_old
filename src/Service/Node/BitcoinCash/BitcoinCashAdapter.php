<?php

namespace App\Service\Node\BitcoinCash;

use App\Entity\Account;
use App\Entity\Currency;
use App\Service\DB\DBNodeAdapterInterface;
use App\Service\Node\NodeAdapterInterface;
use App\Service\Notifier;

class BitcoinCashAdapter implements NodeAdapterInterface
{
    public const NAME = 'bch';

    private $node;
    private $db;
    private $currency;
    private $notifier;

    public function __construct(DBNodeAdapterInterface $db = null)
    {
        $this->node = new BitcoinCashNode();
        $this->notifier = new Notifier();
        $this->db = $db;
        $this->currency = $this->db->getCurrencyByName(self::NAME);
    }

    public function checkAccount(Account $account, int $lastBlock = -1)
    {
        $updated = 0;
        $total = 0;
        $blockIndex = 0;
        $transactions = $this->node->listTransactions($account->getName());
        $backendTransactions = [];

        foreach ($transactions as $transaction) {
            $rawTransaction = $this->node->getRawTransaction($transaction['txid'], 1);
            $amount = Currency::showMinorCurrency($this->currency, $transaction['amount']);
            if ($blockIndex < $rawTransaction['locktime']) {
                $blockIndex = $rawTransaction['locktime'];
            }

            $result = $this->db->addOrUpdateTransaction(
                $transaction['blockhash'],
                $transaction['txid'],
                $rawTransaction['locktime'],
                $transaction['confirmations'],
                '',
                $transaction['address'],
                $amount
            );

            if ($result !== null) {
                $total++;
            }
            if ($result === true) {
                $updated++;
                $backendTransactions[] = [
                    'amount' => $amount,
                    'confirmations' => $transaction['confirmations'],
                ];
            }
        }

        $balance = $this->node->getBalance($account->getName());
        $account->setLastBalance(Currency::showMinorCurrency($this->currency, $balance));
        if ($blockIndex > $account->getLastBlock()) {
            $account->setLastBlock($blockIndex);
        }

        $this->notifier->notifyAccount(
            self::NAME,
            $account->getGlobalUser()->getGuid(),
            Currency::showMinorCurrency($this->currency, $balance),
            $backendTransactions
        );

        return ['updated' => $updated, 'total' => $total];
    }

    public function fixedUpdate($data)
    {
        $backendTransactions = [];
        $result = 0;
        $timeline = time() + (int)getenv('FIXED_UPDATE_TIMEOUT');
        $isOk = function () use ($timeline) {
            return time() <= $timeline;
        };

        /** @var Account[] $wallets */
        $accounts = $this->db->getTopWallets();

        /** @var Account $account */
        foreach ($accounts as $account) {
            if (!$isOk()) {
                $result = false;
                break;
            }

            $balance = $this->node->getBalance($account->getName());
            /*$accountBalance = Currency::showCurrency($this->currency, $account->getLastBalance());
            if ($balance == $accountBalance) {
                $result++;
                continue;
            }*/

            $isComplete = true;
            $blockIndex = 0;
            $limit = $data['filters']['limit'] ?? 10;
            $from = $data['filters']['from'] ?? 0;

            $transactions = $this->node->listTransactions($account->getName(), $limit, $from);
            foreach ($transactions as $transaction) {
                if (!$isOk()) {
                    $result = false;
                    $isComplete = false;
                    break;
                }

                $amount = Currency::showMinorCurrency($this->currency, $transaction['amount']);
                $rawTransaction = $this->node->getRawTransaction($transaction['txid'], 1);
                $this->db->addOrUpdateTransaction($rawTransaction['hash'], $transaction['txid'], $rawTransaction['locktime'], $transaction['confirmations'], '', $transaction['address'], $amount, '');

                if ($blockIndex < $rawTransaction['locktime']) {
                    $blockIndex = $rawTransaction['locktime'];
                }
                if (!isset($backendTransactions[$transaction['address']])) {
                    $backendTransactions[$transaction['address']] = [
                        'currency' => self::NAME,
                        'balance' => Currency::showMinorCurrency($this->currency, $balance),
                        'guid' => $account->getGlobalUser()->getGuid(),
                        'type' => $account->getType(),
                        'address' => $transaction['address'],
                        'transactions' => [],
                    ];
                }
                $backendTransactions[$transaction['address']]['transactions'][$transaction['txid']] = [
                    'txid' => $transaction['txid'],
                    'hash' => $rawTransaction['hash'],
                    'amount' => $amount,
                    'confirmations' => $transaction['confirmations'],
                ];
            }

            if ($isComplete) {
                $account->setLastBalance(Currency::showMinorCurrency($this->currency, $balance));
                if ($blockIndex) {
                    $account->setLastBlock($blockIndex);
                }
            }

            $result++;
        }

        $this->notifier->notifyTransactions($backendTransactions);

        return $result;
    }

    public function update($data)
    {
        $backendTransactions = [];
        $transactionIds = [];
        if ($data['type'] == 'block') {
            $block = $this->node->getBlock($data['hash']);
            $transactionIds = $block == 'Block not found' ? [] : $block['tx'];
        } else if ($data['type'] == 'wallet') {
            $transactionIds = [$data['hash']];
        }

        foreach ($transactionIds as $transactionId) {
            $rawTransaction = $this->node->getRawTransaction($transactionId, 1);
            if (\is_string($rawTransaction)) {
                continue;
            }

            $amount = 0;
            $to = '';

            /** @var Account $account */
            $account = null;
            $addresses = [];
            foreach ($rawTransaction['vout'] as $i => $out) {
                foreach ($out['scriptPubKey']['addresses'] ?? [] as $address) {
                    $addresses[] = $address;
                }
            }

            $accounts = $this->db->getAccounts($addresses);
            foreach ($addresses as $i => $address) {
                if ($account = $accounts[$address] ?? null) {
                    $to = $address;
                    $amount = Currency::showMinorCurrency($this->currency, $rawTransaction['vout'][$i]['value']);
                    break;
                }
            }

            if ($account) {
                $this->db->addOrUpdateTransaction($rawTransaction['blockhash'], $rawTransaction['txid'], $rawTransaction['locktime'], $rawTransaction['confirmations'], '', $to, $amount, '');
                $balance = $this->node->getBalance($account->getName());
                $account->setLastBalance(Currency::showMinorCurrency($this->currency, $balance));
                $account->setLastBlock($rawTransaction['locktime']);

                if (!isset($backendTransactions[$to])) {
                    $backendTransactions[$to] = [
                        'currency' => self::NAME,
                        'balance' => Currency::showMinorCurrency($this->currency, $balance),
                        'guid' => $account->getGlobalUser()->getGuid(),
                        'type' => $account->getType(),
                        'address' => $to,
                        'transactions' => [],
                    ];
                }
                $backendTransactions[$to]['transactions'][$rawTransaction['txid']] = [
                    'txid' => $rawTransaction['txid'],
                    'hash' => $rawTransaction['hash'],
                    'amount' => $amount,
                    'confirmations' => $rawTransaction['confirmations'] ?? 0,
                ];
            }
        }

        $this->notifier->notifyTransactions($backendTransactions);
    }

    public function getName(): string
    {
        return $this->currency->getName();
    }

    /**
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getStatus()
    {
        $info = $this->node->getNetworkInfo();
        return $info['networkactive'];
    }

    public function getVersion()
    {
        $info = $this->node->getNetworkInfo();
        if (!empty($info)) {
            return $info['version'];
        }
        return '';
    }

    public function getAccounts()
    {
        return $this->node->listAccounts();
    }

    public function getAccount(string $address)
    {
        return $this->node->getAccount($address);
    }

    public function getBalance(string $name)
    {
        return $this->node->getBalance($name);
    }

    public function getTransaction(string $txId)
    {
        return $this->node->getTransaction($txId);
    }

    public function getTransactions(string $account)
    {
        return $this->node->listTransactions($account);
    }

    public function getNewAddress(string $account = null)
    {
        return $this->node->getNewAddress($account);
    }

    public function createAccount(string $name, $data = null)
    {
        $address = $this->getNewAddress($name);
        $account = $this->node->getAccount($address);
        $lastBalance = $this->node->getBalance($account);
        $blockChainInfo = $this->node->getBlockChainInfo();

        $this->db->addOrUpdateAccount($data['guid'], $address, $account, $lastBalance, $blockChainInfo['headers']);

        return $address;
    }

    public function send(string $address, int $amount)
    {
        return $this->node->sendToAddress($address, $amount);
    }
}