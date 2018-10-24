<?php

namespace App\Service\Node\Bitcoin;

use App\Entity\Account;
use App\Entity\Currency;
use App\Service\DB\DBNodeAdapterInterface;
use App\Service\Node\NodeAdapterInterface;

class BitcoinAdapter implements NodeAdapterInterface
{
    public const NAME = 'btc';

    private $node;
    private $db;

    public function __construct(DBNodeAdapterInterface $db = null)
    {
        $this->node = new BitcoinNode();
        $this->db = $db;
    }

    /**
     * @inheritdoc
     * @param Account $account
     * @param int $lastBlock
     * @return array|bool|int
     */
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

    /**
     * This method is called automatically by CRON once each specific amount
     * of time (1 min). FixedUpdate is limited by time execution.
     * Method should get specific count of accounts/wallets so they could be
     * updated in specific amount of time.
     * Be sure to check specific constant amount of wallets and try to
     * not exceed max method execution time (less than 1 min)
     *
     * Note: database cleaning of extra account and other stuff that are related
     * to node should be done here.
     * You can use data and settings to store temporary variables (like time
     * since last cleaning) and decide when you should execute such methods
     * Later there will be separate method to call and queue to tell system,
     * that node needs maintenance. And system will call it when it has time
     *
     * Note: please check lastBlock and current active block of node.
     * Maybe you do not need to check transactions account each block
     *
     * @param array $data As input there are going to be statisctics data about node,
     * that could be used to decide how much and what accounts should be rechecked
     * @return bool|int If you method is not finished or needs more time for execution it should
     * return FALSE to show, that there is a problem with checking
     * If method succeeded - it should return amount of addresses/account it was
     * able to check. Data used for statistic and to count
     * how much and how good the node is holding
     */
    public function fixedUpdate($data)
    {
        $result = 0;
        $timeline = time() + (int)getenv('FIXED_UPDATE_TIMEOUT');
        $isOk = function () use ($timeline) {
            return time() >= $timeline;
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

                $amount = Currency::showMinorCurrency($currency, $tnx['amount']);
                $this->db->addOrUpdateTransaction($tnx['blockhash'], $tnx['txid'], $tnx['blockindex'], $tnx['confirmations'], '', $tnx['address'], $amount, '');
            }

            if ($isComplete) {
                $account->setLastBalance(Currency::showMinorCurrency($currency, $balance));
                $account->setLastBalance($tnx['blockindex']);
            }

            $result++;
        }

        return $result;
    }

    /**
     * This method is called automatically by nodes and NOT crontab
     * when specific event occurs like new transaction or block
     *
     * @param $data
     */
    public function update($data)
    {
        $currency = $this->getCurrency();

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

            $hash = $tx['blockhash'];
            $index = 0;
            $amount = 0;
            $to = '';

            /** @var Account $account */
            $account = null;
            $addresses = [];
            foreach ($tx['vout'] as $i => $out) {
                foreach ($out['scriptPubKey']['addresses'] as $address) {
                    $addresses[] = $address;
                }
            }

            $accounts = $this->db->getAccounts($addresses);
            foreach ($addresses as $i => $address) {
                if ($account = $accounts[$address] ?? null) {
                    $to = $address;
                    $amount = Currency::showMinorCurrency($currency, $tx['vout'][$i]['value']);
                    break;
                }
            }

            if ($account) {
                $this->db->addOrUpdateTransaction($hash, $tx['txid'], $index, $tx['confirmations'], '', $to, $amount, '');
                $balance = $this->node->getBalance($account->getName());
                $account->setLastBalance(Currency::showMinorCurrency($currency, $balance));
                $account->setLastBlock($index);
            }
        }
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

        $this->db->addOrUpdateAccount($data['guid'], $address, $account, $lastBalance, $blockChainInfo['headers']);

        return $address;
    }

    public function send(string $address, int $amount)
    {
        return $this->node->sendToAddress($address, $amount);
    }
}