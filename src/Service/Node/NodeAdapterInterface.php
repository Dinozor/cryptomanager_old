<?php

namespace App\Service\Node;


use App\Entity\Currency;
use App\Service\DB\DBNodeAdapterInterface;
use App\Service\NodeDataManager;

interface NodeAdapterInterface
{
    // DatabaseAccess, Guid, Currency, ||prev wallet, block count
//    public function __construct(NodeDataManager $dataManager, ?string $rootWallet = null, $settings = null);

    public function __construct(DBNodeAdapterInterface $db = null);

    public function getName();

    /**
     * @return Currency
     */
    public function getCurrency();

    public function getStatus();

    public function getVersion();

    public function getAccounts();

    public function getAccount(string $name);

    public function getBalance(string $name);

    public function getTransaction(string $hash);

    public function getTransactions(string $account);

    public function getNewAddress(string $name = null);

    public function createAccount(string $name, $data = null);

    public function send(string $address, int $amount);
}