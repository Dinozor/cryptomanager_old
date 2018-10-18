<?php

namespace App\Service\Node\Litecoin;

use App\Entity\Account;
use App\Entity\Currency;
use App\Service\DB\DBNodeAdapterInterface;
use App\Service\Node\NodeAdapterInterface;

class LitecoinAdapter implements NodeAdapterInterface
{
    public const NAME = 'ltc';

    private $node;
    private $db;

    public function __construct(DBNodeAdapterInterface $db = null)
    {
        $this->node = new LitecoinNode();
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
                $tx['amount'],
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