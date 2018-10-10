<?php

namespace App\Service\Node\Bitcoin;

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
        return $this->node->getNewAddress($name);
    }

    public function send(string $address, int $amount)
    {
        return $this->node->sendToAddress($address, $amount);
    }
}