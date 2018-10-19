<?php

namespace App\Service\Node\BitcoinCash;

use App\Entity\Account;
use App\Entity\Currency;
use App\Service\DB\DBNodeAdapterInterface;
use App\Service\Node\NodeAdapterInterface;

class BitcoinCashAdapter implements NodeAdapterInterface
{
    public const NAME = 'bch';

    private $node;
    private $db;

    public function __construct(DBNodeAdapterInterface $db = null)
    {
        $this->node = new BitcoinCashNode();
        $this->db = $db;
    }

    public function checkAccount(Account $account, int $lastBlock = -1)
    {
        $updated = 0;
        $total = 0;
        $txs = $this->node->listTransactions($account->getName());

        foreach ($txs as $tnx) {
            $tx = $this->node->getTransaction($tnx['txid']);
            $result = $this->db->addOrUpdateTransaction(
                $tnx['blockhash'],
                $tnx['blockindex'],
                $tnx['address'],
                $tx['details'][0]['address'],
                Currency::showMinorCurrency($account->getCurrency(), $tx['amount']),
                ''
            );

            if ($result != null) {
                $total++;
            }
            if ($result) {
                $updated++;
            }
        }

        return ['updated' => $updated, 'total' => $total];
    }

    public function fixedUpdate($data)
    {
        $result = 0;
        $start = time();
        $isOk = function () use ($start) {
            return (time() - $start) < 60;
        };

        /** @var Account[] $wallets */
        $accounts = $this->db->getTopWallets();

        /** @var Account $account */
        foreach ($accounts as $account) {
            if (!$isOk()) {
                $result = false;
                break;
            }

            $currency = $account->getCurrency();
            $balance = $this->node->getBalance($account->getName());
            $accountBalance = Currency::showCurrency($currency, $account->getLastBalance());

            if ($balance == $accountBalance) {
                $result++;
                continue;
            }

            $isComplete = true;
            $limit = $data['filters']['limit'] ?? 10;
            $from = $data['filters']['from'] ?? 0;

            $txs = $this->node->listTransactions($account->getName(), $limit, $from);
            foreach ($txs as $tnx) {
                if (!$isOk()) {
                    $result = false;
                    $isComplete = false;
                    break;
                }

                $tx = $this->node->getTransaction($tnx['txid']);
                $this->db->addOrUpdateTransaction(
                    $tnx['blockhash'],
                    $tnx['blockindex'],
                    $tnx['address'],
                    $tx['details'][0]['address'],
                    Currency::showMinorCurrency($currency, $tx['amount']),
                    ''
                );
            }

            if ($isComplete) {
                $account->setLastBalance(Currency::showMinorCurrency($currency, $balance));
                $account->setLastBalance($tnx['blockindex']);
            }

            $result++;
        }

        return $result;
    }

    public function update($data)
    {
        // TODO: Implement update() method.
    }

    public function getName(): string
    {
        return $this->db->getCurrencyByName(self::NAME)->getName();
    }

    /**
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return $this->db->getCurrencyByName(self::NAME);
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
        $address = $this->node->getNewAddress($name);
        $account = $this->node->getAccount($address);
        $lastBalance = $this->node->getBalance($account);
        $blockChainInfo = $this->node->getBlockChainInfo();

        $this->db->addAccount($data['guid'], $address, $account, $lastBalance, $blockChainInfo['headers']);

        return $address;
    }

    public function send(string $address, int $amount)
    {
        return $this->node->sendToAddress($address, $amount);
    }
}