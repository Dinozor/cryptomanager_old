<?php

namespace App\Service\Node\Ripple;

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
        $info = $this->node->serverInfo();
        if (!empty($info)) {
            return $info['info']['build_version'];
        }
        return '';
    }

    public function getAccounts()
    {
        // TODO: Implement getAccounts() method.
    }

    public function getAccount(string $address)
    {
        return $this->node->accountInfo($address);
    }

    public function getBalance(string $account)
    {
        return $this->node->gatewayBalances($account);
    }

    public function getTransaction(string $txId)
    {
        return $this->node->tx($txId);
    }

    public function getTransactions(string $account)
    {
        return $this->node->accountTx($account);
    }

    public function getNewAddress(string $account = null)
    {
        // TODO: Implement getNewAddress() method.
    }

    public function createAccount(string $name, $data = null)
    {
        return $this->node->walletPropose();
    }

    public function send(string $address, int $amount)
    {
        // TODO: Implement send() method.
    }
}