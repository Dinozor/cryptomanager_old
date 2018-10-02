<?php

namespace App\Service\Node\Ethereum;


use App\Entity\Currency;
use App\Service\DB\DBNodeAdapterInterface;
use App\Service\Node\NodeAdapterInterface;
use App\Service\Node\Ethereum;

class EthereumAdapter implements NodeAdapterInterface
{
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
     */
    public function __construct(DBNodeAdapterInterface $db = null)
    {
        $this->node = new EthereumNode();
        $this->db = $db;
    }

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
}