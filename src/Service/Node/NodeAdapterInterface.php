<?php

namespace App\Service\Node;


use App\Entity\Account;
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

    /**
     * This method should check account (wallet) for new transaction and update old ones;
     * Method is called when node was down or account need rechecking or manual check
     * Method is called mostly manually and for old, deprecated wallets. This wallet may not be in node database.
     * @param Account $account Account to check and update transactions
     * @param int $lastBlock check transactions fron this specific block. if lastBlock is 0 - recheck all transactions of this account
     *if lastBlock is -1 (default) check all transactions from last checked block (checl all new blocks for transactions)
     * @return bool|int
     */
    public function checkAccount(Account $account, int $lastBlock = -1);

    public function createAccount(string $name, $data = null);

    public function send(string $address, int $amount);

    /**
     * this method is called automatically by nodes and NOT crontab when specific event occures like new transaction or block
     * @param $data
     * @return mixed
     */
    public function update($data);

    /**
     * This method is called automatically by CRON once each specific amount of time (1 min). FixedUpdate is limited by time execution.
     * Method should get specific count of accounts/wallets so they could be updated in specific amount of time.
     * Be sure to check specific constant amount of wallets and try to not exceed max method execution time (less than 1 min)
     * If you method is not finished or needs more time for execution it should return FALSE to show, that there is a problem with checking
     * If method succeeded - it should return amount of addresses/account it was able to check. Data used for statistic and to count
     * how much and how good the node is holding
     * As input there are going to be statisctics data about node, that could be used to decide how much and what accounts should be rechecked
     *
     * Note: database cleaning of extra account and other stuff that are related to node should be done here.
     * You can use data and settings to store temporary variables (like time since last cleaning) and decide when you should execute such methods
     * Later there will be separate method to call and queue to tell system, that node needs maintenance. And system will call it when it has time
     *
     * Note: please check lastBlock and current active block of node. Maybe you do not need to check transactions account each block
     * @param $data
     * @return bool|int
     */
    public function fixedUpdate($data);
}