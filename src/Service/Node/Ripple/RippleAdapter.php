<?php

namespace App\Service\Node\Ripple;

use App\Entity\Account;
use App\Entity\Currency;
use App\Service\DB\DBNodeAdapterInterface;
use App\Service\Node\NodeAdapterInterface;

class RippleAdapter implements NodeAdapterInterface
{
    public const NAME = 'xrp';

    private $node;
    private $db;

    public function __construct(DBNodeAdapterInterface $db = null)
    {
        $this->node = new RippleNode();
        $this->db = $db;
    }

    public function checkAccount(Account $account, int $lastBlock = -1)
    {
        $updated = 0;
        $total = 0;
        $txs = $this->node->getAccountTransactionHistory($account->getAddress());

        foreach ($txs['transactions'] as $tnx) {
            $result = $this->db->addOrUpdateTransaction(
                $tnx['hash'],
                $tnx['ledger_index'],
                $tnx['meta']['AffectedNodes'][0]['CreatedNode']['NewFields']['Account'],
                $tnx['meta']['AffectedNodes'][1]['ModifiedNode']['FinalFields']['Account'],
                $tnx['tx']['Amount'],
                $tnx['tx']['TransactionResult']
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
        // TODO: Implement fixedUpdate() method.
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
        // TODO: Implement getStatus() method.
    }

    public function getVersion()
    {
        $info = $this->node->getRippledVersions();
        if (!empty($info)) {
            return $info['rows'][0]['version'];
        }
        return '';
    }

    public function getAccounts()
    {
        return $this->node->getAccounts();
    }

    public function getAccount(string $address)
    {
        return $this->node->getAccount($address);
    }

    public function getBalance(string $address)
    {
        return $this->node->getAccountBalances($address);
    }

    public function getTransaction(string $hash)
    {
        return $this->node->getTransaction($hash);
    }

    public function getTransactions(string $address)
    {
        return $this->node->getAccountTransactionHistory($address);
    }

    public function getNewAddress(string $account = null)
    {
        return null;
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
        return null;
    }
}