<?php

namespace App\Service\Node\Bitcoin;


use App\Entity\Currency;
use App\Service\DB\DBNodeAdapterInterface;
use App\Service\Node\NodeAdapterInterface;
use App\Service\Node\Bitcoin;

class BitcoinAdapter implements NodeAdapterInterface
{

    public function getName()
    {
        // TODO: Implement getName() method.
    }

    /**
     * @return Currency
     */
    public function getCurrency()
    {
        // TODO: Implement getCurrency() method.
    }

    public function getStatus()
    {
        // TODO: Implement getStatus() method.
    }

    public function getVersion()
    {
        // TODO: Implement getVersion() method.
    }

    public function getAccounts()
    {
        // TODO: Implement getAccounts() method.
    }

    public function getAccount(string $name)
    {
        // TODO: Implement getAccount() method.
    }

    public function getBalance(string $name)
    {
        // TODO: Implement getBalance() method.
    }

    public function getTransaction(string $hash)
    {
        // TODO: Implement getTransaction() method.
    }

    public function getTransactions(string $account)
    {
        // TODO: Implement getTransactions() method.
    }

    public function getNewAddress(string $name = null)
    {
        // TODO: Implement getNewAddress() method.
    }

    public function createAccount(string $name, $data = null)
    {
        // TODO: Implement createAccount() method.
    }

    public function send(string $address, int $amount)
    {
        // TODO: Implement send() method.
    }
}