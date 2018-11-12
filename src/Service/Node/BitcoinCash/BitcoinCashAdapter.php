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
        $txs = $this->node->listTransactions($account->getName());
        $transactions = [];

        foreach ($txs as $tx) {
            $amount = Currency::showMinorCurrency($this->currency, $tx['amount']);
            $blockIndex = $tx['blockindex'];
            $result = $this->db->addOrUpdateTransaction(
                $tx['blockhash'],
                $tx['txid'],
                $blockIndex,
                $tx['confirmations'],
                '',
                $tx['address'],
                $amount
            );

            if ($result !== null) {
                $total++;
            }
            if ($result === true) {
                $updated++;
                $transactions[] = [
                    'amount' => $amount,
                    'confirmations' => $tx['confirmations'],
                ];
            }
        }

        $balance = $this->node->getBalance($account->getName());
        $account->setLastBalance(Currency::showMinorCurrency($this->currency, $balance));
        $account->setLastBlock($blockIndex);

        $this->notifier->notifyAccount(
            self::NAME,
            $account->getGlobalUser()->getGuid(),
            Currency::showMinorCurrency($this->currency, $balance),
            $transactions
        );

        return ['updated' => $updated, 'total' => $total];
    }

    public function fixedUpdate($data)
    {
        $transactions = [];
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
            $accountBalance = Currency::showCurrency($this->currency, $account->getLastBalance());

            if ($balance == $accountBalance) {
                $result++;
                continue;
            }

            $isComplete = true;
            $blockIndex = 0;
            $limit = $data['filters']['limit'] ?? 10;
            $from = $data['filters']['from'] ?? 0;

            $txs = $this->node->listTransactions($account->getName(), $limit, $from);
            foreach ($txs as $tnx) {
                if (!$isOk()) {
                    $result = false;
                    $isComplete = false;
                    break;
                }

                $amount = Currency::showMinorCurrency($this->currency, $tnx['amount']);
                $blockIndex = $tnx['blockindex'];
                $this->db->addOrUpdateTransaction($tnx['blockhash'], $tnx['txid'], $blockIndex, $tnx['confirmations'], '', $tnx['address'], $amount, '');

                if (!isset($transactions[$tnx['address']])) {
                    $transactions[$tnx['address']] = [
                        'currency' => self::NAME,
                        'balance' => Currency::showMinorCurrency($this->currency, $balance),
                        'guid' => $account->getGlobalUser()->getGuid(),
                        'address' => $tnx['address'],
                        'transactions' => [],
                    ];
                }
                $transactions[$tnx['address']]['transactions'][] = [
                    'amount' => $amount,
                    'confirmations' => $tnx['confirmations'],
                ];
            }

            if ($isComplete) {
                $account->setLastBalance(Currency::showMinorCurrency($this->currency, $balance));
                $account->setLastBlock($blockIndex);
            }

            $result++;
        }

        $this->notifier->notifyTransactions($transactions);

        return $result;
    }

    public function update($data)
    {
        $transactions = [];
        $txs = [];
        if ($data['type'] == 'block') {
            $block = $this->node->getBlock($data['hash']);
            $txs = $block['tx'];
        } else if ($data['type'] == 'wallet') {
            $txs = [$data['hash']];
        }

        foreach ($txs as $txId) {
            $tx = $this->node->getRawTransaction($txId, 1);
            if (\is_string($tx)) {
                continue;
            }

            $amount = 0;
            $to = '';

            /** @var Account $account */
            $account = null;
            $addresses = [];
            foreach ($tx['vout'] as $i => $out) {
                foreach ($out['scriptPubKey']['addresses'] ?? [] as $address) {
                    $addresses[] = $address;
                }
            }

            $accounts = $this->db->getAccounts($addresses);
            foreach ($addresses as $i => $address) {
                if ($account = $accounts[$address] ?? null) {
                    $to = $address;
                    $amount = Currency::showMinorCurrency($this->currency, $tx['vout'][$i]['value']);
                    break;
                }
            }

            if ($account) {
                $this->db->addOrUpdateTransaction($tx['blockhash'], $tx['txid'], $tx['locktime'], $tx['confirmations'], '', $to, $amount, '');
                $balance = $this->node->getBalance($account->getName());
                $account->setLastBalance(Currency::showMinorCurrency($this->currency, $balance));
                $account->setLastBlock($tx['locktime']);

                if (!isset($transactions[$to])) {
                    $transactions[$to] = [
                        'currency' => self::NAME,
                        'balance' => Currency::showMinorCurrency($this->currency, $balance),
                        'guid' => $account->getGlobalUser()->getGuid(),
                        'address' => $to,
                        'transactions' => [],
                    ];
                }
                $transactions[$to]['transactions'][] = [
                    'amount' => $amount,
                    'confirmations' => $tx['confirmations'],
                ];
            }
        }

        $this->notifier->notifyTransactions($transactions);
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