<?php

namespace App\Service\Node\Ethereum;


use App\Entity\Account;
use App\Entity\Currency;
use App\Service\DB\DBNodeAdapterInterface;
use App\Service\Node\NodeAdapterInterface;
use App\Service\Node\Ethereum;

class EthereumAdapter implements NodeAdapterInterface
{
    public const NAME = 'Ethereum';
    public const SHORT_NAME = 'ETH';
    private $rootWallet;
    /**
     * @var EthereumNode|null
     */
    private $node = null;
    private const URL = "http://127.0.0.1:8545";
    private $client;
    private $lastCheckedBlock = 0;
    private $totalBlocks = 0;
    private $settings = null;

    /**
     * @var DBNodeAdapterInterface
     */
    private $db;

    /**
     * EthereumAdapter constructor.
     * @param DBNodeAdapterInterface|null $db
     */
    public function __construct(DBNodeAdapterInterface $db = null)
    {
        $this->node = new EthereumNode();
        $this->db = $db;
        $this->settings = $this->db->getNodeSettings();
    }

    public function getName()
    {
        return self::NAME;
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
        return $this->node->getVersion();
    }

    public function getAccounts()
    {
        return $this->node->getAccounts();
    }

    public function getAccount(string $name)
    {
        return $this->node->getAccount($name);
    }

    public function getBalance(string $name)
    {
        return $this->node->getBalance($name);
    }

    public function getTransaction(string $hash)
    {
        return $this->node-$this->getTransaction($hash);
    }

    public function getTransactions(string $account)
    {
        return $this->node->getTransactions($account);
    }

    public function getNewAddress(string $name = null)
    {
        return $this->node->getNewAddress($name);
    }

    public function send(string $address, int $amount)
    {
        // TODO: Implement send() method.
    }

    static function hexToDec($hex) {
        if(strlen($hex) == 1) {
            return hexdec($hex);
        } else {
            $remain = substr($hex, 0, -1);
            $last = substr($hex, -1);
            return bcadd(bcmul(16, self::hexToDec($remain)), hexdec($last));
        }
    }

    static function decToHex($dec) {
        $hex = '';
        do {
            $last = bcmod($dec, 16);
            $hex = dechex($last).$hex;
            $dec = bcdiv(bcsub($dec, $last), 16);
        } while($dec>0);
        return '0x' . $hex;
    }

    static function weiToEth($wei)
    {
        return bcdiv($wei,1000000000000000000,18);
    }

    public function createAccount(string $name, $data = null)
    {
        return $this->getNewAddress($name);
    }
    public function getGasPrice()
    {
        return self::hexToDec($this->node->getGasPrice());
    }

    /**
     * this method is called automatically by nodes or crontab when specific event occures like new transaction or block
     * Method can be called without ny data and it can
     * @param $data
     * @return mixed
     */
    public function update($data)
    {
         $lastCheckedBlock = $this->settings['lastCheckedBlock'];
         $filters = $this->settings['filters'];
         if ($filters) {

         }
//        $this->node->getF
    }

    private function checkLogs() {

    }

    private function checkFilter($filterID, $filterType) {
        $changes = $this->node->getFilterChanges($filterID);
        switch ($filterType) {
            case 'block': break;
            case 'pending':break;
        }
    }

    /**
     * This method should check account (wallet) for new transaction and update old ones;
     * Method is called when node was down or account need rechecking or manual check
     * Method is called mostly manually and for old, deprecated wallets. This wallet may not be in node database.
     * @param Account $account Account to check and update transactions
     * @param int $lastBlock check transactions fron this specific block. if lastBlock is 0 - recheck all transactions of this account
     *if lastBlock is -1 (default) check all transactions from last checked block (checl all new blocks for transactions)
     * @return bool|int
     */
    public function checkAccount(Account $account, int $lastBlock = -1)
    {
        // TODO: Implement checkAccount() method.
    }

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
    public function fixedUpdate($data)
    {
        // TODO: Implement fixedUpdate() method.
    }
}