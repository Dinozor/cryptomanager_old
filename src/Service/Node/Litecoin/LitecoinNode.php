<?php

namespace App\Service\Node\Litecoin;

use App\Service\NodeDataManager;
use JsonRpc\Client;

class LitecoinNode
{
    private const URL = 'http://127.0.0.1:8545';
    private $client;
    private $rootWallet;
    private $dataManager;

    public $status;
    public $error;
    public $raw_response;
    public $response;

    public function __construct(NodeDataManager $dataManager = null, ?string $rootWallet = null, $settings = null)
    {
        $this->dataManager = $dataManager;
        $this->rootWallet = $rootWallet;
        $this->client = new Client(self::URL);
    }

    private function _call(string $method, array $params = [])
    {
        return $this->client->call($method, $params)
            ? $this->client->result
            : $this->client->error;
    }

    /**
     * Add a nrequired-to-sign multisignature address to the wallet.
     * Each key is a Litecoin address or hex-encoded public key.
     * If [account] is specified, assign address to [account].
     * <nrequired> <'["key","key"]'> [account]
     *
     * @param $nRequired
     * @param array $keys
     * @param null $account
     * @return mixed|String
     */
    public function addMultiSigAddress($nRequired, array $keys, $account = null)
    {
        return $this->_call('addmultisigaddress', [$nRequired, $keys, $account]);
    }

    /**
     * version 0.8
     * Attempts add or remove <node> from the addnode list or try
     * a connection to <node> once.
     * <node> <add/remove/onetry>
     *
     * @param $node
     * @param $action
     * @return mixed|String
     */
    public function addNode($node, $action)
    {
        return $this->_call('addnode', [$node, $action]);
    }

    /**
     * Safely copies wallet.dat to destination,
     * which can be a directory or a path with filename.
     * <destination>
     *
     * @param $destination
     * @return mixed|String
     */
    public function backupWallet($destination)
    {
        return $this->_call('backupwallet', [$destination]);
    }

    /**
     * OMG2 only Bans <node> until unix timestamp <expiration>.
     * Set <expiration> to -1 to unban a node.
     * <node> <expiration>
     *
     * @param $node
     * @param $expiration
     * @return mixed|String
     */
    public function banNode($node, $expiration)
    {
        return $this->_call('bannode', [$node, $expiration]);
    }

    /**
     * Creates a multi-signature address and returns a json object
     * <nrequired> <'["key,"key"]'>
     *
     * @param $nRequired
     * @param array $keys
     * @return mixed|String
     */
    public function createMultiSig($nRequired, array $keys)
    {
        return $this->_call('createmultisig', [$nRequired, $keys]);
    }

    /**
     * Creates a raw transaction spending given inputs.
     * [{"txid":txid,"vout":n},...] {address:amount,...}
     *
     * @param array $params
     * @param array $data
     * @return mixed|String
     */
    public function createRawTransaction(array $params = [], array $data = [])
    {
        return $this->_call('createrawtransaction', [$params, $data]);
    }

    /**
     * Produces a human-readable JSON object for a raw transaction.
     * <hex string>
     *
     * @param string $hex
     * @return mixed|String
     */
    public function decodeRawTransaction(string $hex)
    {
        return $this->_call('decoderawtransaction', [$hex]);
    }

    /**
     * Reveals the private key corresponding to <litecoinaddress>
     * <litecoinaddress>
     *
     * @param $litecoinAddress
     * @return mixed|String
     */
    public function dumpPrivKey($litecoinAddress)
    {
        return $this->_call('dumpprivkey', [$litecoinAddress]);
    }

    /**
     * Encrypts the wallet with <passphrase>
     * <passphrase>
     *
     * @param string $passPhrase
     * @return mixed|String
     */
    public function encryptWallet(string $passPhrase)
    {
        return $this->_call('encryptwallet', [$passPhrase]);
    }

    /**
     * Returns the account associated with the given address.
     * <litecoinaddress>
     *
     * @param string $litecoinAddress
     * @return mixed|String
     */
    public function getAccount(string $litecoinAddress)
    {
        return $this->_call('getaccount', [$litecoinAddress]);
    }

    /**
     * Returns the current Litecoin address for receiving payments to this account.
     * <account>
     *
     * @param $account
     * @return mixed|String
     */
    public function getAccountAddress($account)
    {
        return $this->_call('getaccountaddress', [$account]);
    }

    /**
     * version 0.8
     * Returns information about the given added node,
     * or all added nodes (note that onetry addnodes are not listed here)
     * If dns is false, only a list of added nodes will be provided,
     * otherwise connected information will also be available.
     * <dns> [node]
     *
     * @param $dns
     * @param null $node
     * @return mixed|String
     */
    public function getAddedNodeInfo($dns, $node = null)
    {
        return $this->_call('getaddednodeinfo', [$dns, $node]);
    }

    /**
     * Returns the list of addresses for the given account.
     * <account>
     *
     * @param $account
     * @return mixed|String
     */
    public function getAddressesByAccount($account)
    {
        return $this->_call('getaddressesbyaccount', [$account]);
    }

    /**
     * If [account] is not specified, returns the server's total available balance.
     * If [account] is specified, returns the balance in the account.
     * [account] [minconf=1]
     *
     * @param null $account
     * @param int $minConf
     * @return mixed|String
     */
    public function getBalance($account = null, $minConf = 1)
    {
        return $this->_call('', [$account, $minConf]);
    }

    public function _()
    {
        return $this->_call('', []);
    }
}