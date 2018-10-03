<?php

namespace App\Service\Node\Bitcoin;

use App\Service\NodeDataManager;
use JsonRpc\Client;

class BitcoinNode
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
     * Each key is a bitcoin address or hex-encoded public key.
     * If [account] is specified, assign address to [account].
     * Returns a string containing the address.
     * <nrequired> <'["key","key"]'> [account]
     *
     * @param string $nRequired
     * @param array $keys
     * @param string $account
     * @return mixed|String
     */
    public function addMultiSigAddress(string $nRequired, array $keys, string $account = null)
    {
        return $this->_call('addmultisigaddress', [$nRequired, $keys, $account]);
    }

    /**
     * version 0.8
     * Attempts add or remove <node>
     * from the addnode list or try a connection to <node> once.
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
     * version 0.7
     * Creates a raw transaction spending given inputs.
     * [{"txid":txid,"vout":n},...] {address:amount,...}
     *
     * @param array|null $transactions
     * @return mixed
     */
    public function createRawTransaction(array $transactions = null)
    {
        return $this->_call('createrawtransaction', [$transactions]);
    }

    /**
     * version 0.7
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
     * Reveals the private key corresponding to <bitcoinaddress>
     * <bitcoinaddress>
     *
     * @param string $bitcoinAddress
     * @return mixed|String
     */
    public function dumpPrivKey(string $bitcoinAddress)
    {
        return $this->_call('dumpprivkey', [$bitcoinAddress]);
    }

    /**
     * version 0.13.0
     * Exports all wallet private keys to file
     * <dumpwallet>
     *
     * @param string $filename
     * @return mixed|String
     */
    public function dumpWallet(string $filename)
    {
        return $this->_call('dumpwallet', [$filename]);
    }

    /**
     * Encrypts the wallet with <passphrase>
     * <passphrase>
     *
     * @param string $passphrase
     * @return mixed|String
     */
    public function encryptWallet(string $passphrase)
    {
        return $this->_call('encryptwallet', [$passphrase]);
    }

    /**
     * Returns the account associated with the given address.
     * <bitcoinaddress>
     *
     * @param string $bitcoinAddress
     * @return mixed|String
     */
    public function getAccount(string $bitcoinAddress)
    {
        return $this->_call('getaccount', [$bitcoinAddress]);
    }

    /**
     * Returns the current bitcoin address for receiving payments to this account.
     * If <account> does not exist, it will be created along with
     * an associated new address that will be returned.
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
     * Returns information about the given added node, or all added nodes
     * (note that onetry addnodes are not listed here) If dns is false,
     * only a list of added nodes will be provided,
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
     * @param string $account
     * @return mixed|String
     */
    public function getAddressesByAccount(string $account)
    {
        return $this->_call('getaddressesbyaccount', [$account]);
    }

    /**
     * If [account] is not specified, returns the server's total available balance.
     * If [account] is specified, returns the balance in the account.
     * [account] [minconf=1]
     *
     * @param string $account
     * @param int $minconf
     * @return mixed|String
     */
    public function getBalance(string $account, int $minconf = 1)
    {
        return $this->_call('getbalance', [$account, $minconf]);
    }

    /**
     * version 0.9
     * Returns the hash of the best (tip) block in the longest block chain.
     *
     * @return mixed|String
     */
    public function getBestBlockHash()
    {
        return $this->_call('getbestblockhash');
    }

    /**
     * Returns information about the block with the given hash.
     * <hash>
     *
     * @param string $hash
     * @return mixed|String
     */
    public function getBlock(string $hash)
    {
        return $this->_call('getblock', [$hash]);
    }

    /**
     * Returns the number of blocks in the longest block chain.
     *
     * @return mixed|String
     */
    public function getBlockCount()
    {
        return $this->_call('getblockcount');
    }

    /**
     * Returns hash of block in best-block-chain at <index>; index 0 is the genesis block
     * <index>
     *
     * @param $index
     * @return mixed|String
     */
    public function getBlockHash($index)
    {
        return $this->_call('getblockhash', [$index]);
    }

    /**
     * Returns data needed to construct a block to work on.
     * See BIP_0022 for more info on params.
     * [params]
     *
     * @param array $params
     * @return mixed|String
     */
    public function getBlockTemplate(array $params = [])
    {
        return $this->_call('getblocktemplate', [$params]);
    }

    /**
     * Returns the number of connections to other nodes.
     *
     * @return mixed|String
     */
    public function getConnectionCount()
    {
        return $this->_call('getconnectioncount');
    }

    /**
     * Returns the proof-of-work difficulty as a multiple of the minimum difficulty.
     *
     * @return mixed|String
     */
    public function getDifficulty()
    {
        return $this->_call('getdifficulty');
    }

    /**
     * Returns true or false whether bitcoind is currently generating hashes
     *
     * @return mixed|String
     */
    public function getGenerate()
    {
        return $this->_call('getgenerate');
    }

    /**
     * Returns a recent hashes per second performance measurement while generating.
     *
     * @return mixed|String
     */
    public function getHashesPerSec()
    {
        return $this->_call('gethashespersec');
    }

    /**
     * Returns an object containing various state info.
     *
     * @return mixed|String
     */
    public function getInfo()
    {
        return $this->_call('getinfo');
    }

    /**
     * Returns an object containing mining-related information:
     * - blocks
     * - currentblocksize
     * - currentblocktx
     * - difficulty
     * - errors
     * - generate
     * - genproclimit
     * - hashespersec
     * - pooledtx
     * - testnet
     *
     * @return mixed|String
     */
    public function getMiningInfo()
    {
        return $this->_call('getmininginfo');
    }

    /**
     * Returns a new bitcoin address for receiving payments.
     * If [account] is specified payments received with the address
     * will be credited to [account].
     *
     * @param null $account
     * @return mixed|String
     */
    public function getNewAddress($account = null)
    {
        return $this->_call('getnewaddress', [$account]);
    }

    /**
     * version 0.7
     * Returns data about each connected node.
     *
     * @return mixed|String
     */
    public function getPeerInfo()
    {
        return $this->_call('getpeerinfo');
    }

    /**
     * version 0.9
     * Returns a new Bitcoin address, for receiving change.
     * This is for use with raw transactions, NOT normal use.
     * [account]
     *
     * @param $account
     * @return mixed|String
     */
    public function getRawChangeAddress($account = null)
    {
        return $this->_call('getrawchangeaddress', [$account]);
    }

    /**
     * version 0.7
     * Returns all transaction ids in memory pool
     *
     * @return mixed|String
     */
    public function getRawMemPool()
    {
        return $this->_call('getrawmempool');
    }

    /**
     * version 0.7
     * Returns raw transaction representation for given transaction id.
     * <txid> [verbose=0]
     *
     * @param $txId
     * @param int $verbose
     * @return mixed|String
     */
    public function getRawTransaction($txId, int $verbose = 0)
    {
        return $this->_call('getrawtransaction', [$txId, $verbose]);
    }

    /**
     * version 0.3.24
     * Returns the total amount received by addresses with [account]
     * in transactions with at least [minconf] confirmations.
     * If [account] not provided return will include all transactions to all accounts.
     * [account] [minconf=1]
     *
     * @param $account
     * @param int $minConf
     * @return mixed|String
     */
    public function getReceivedByAccount($account, int $minConf = 1)
    {
        return $this->_call('getreceivedbyaccount', [$account, $minConf]);
    }

    /**
     * Returns the amount received by <bitcoinaddress> in transactions
     * with at least [minconf] confirmations.
     * It correctly handles the case where someone has sent to the address
     * in multiple transactions. Keep in mind that addresses are only ever
     * used for receiving transactions. Works only for addresses in the local wallet,
     * external addresses will always show 0.
     * <bitcoinaddress> [minconf=1]
     *
     * @param string $bitcoinAddress
     * @param int $minConf
     * @return mixed|String
     */
    public function getReceivedByAddress(string $bitcoinAddress, int $minConf = 1)
    {
        return $this->_call('getreceivedbyaddress', [$bitcoinAddress, $minConf]);
    }

    /**
     * Returns an object about the given transaction containing:
     * - "amount" : total amount of the transaction
     * - "confirmations" : number of confirmations of the transaction
     * - "txid" : the transaction ID
     * - "time" : time associated with the transaction[1].
     * - "details" - An array of objects containing:
     *   - "account"
     *   - "address"
     *   - "category"
     *   - "amount"
     *   -"fee"
     * <txid>
     *
     * @param $txId
     * @return mixed|String
     */
    public function getTransaction($txId)
    {
        return $this->_call('gettransaction', [$txId]);
    }

    /**
     * Returns details about an unspent transaction output (UTXO)
     * <txid> <n> [includemempool=true]
     *
     * @param $txId
     * @param $n
     * @param bool $includeMemPool
     * @return mixed|String
     */
    public function getTxOut($txId, $n, bool $includeMemPool = true)
    {
        return $this->_call('gettxout', [$txId, $n, $includeMemPool]);
    }

    /**
     * Returns statistics about the unspent transaction output (UTXO) set
     *
     * @return mixed|String
     */
    public function getTxOutSetInfo()
    {
        return $this->_call('gettxoutsetinfo');
    }

    /**
     * If [data] is not specified, returns formatted hash data to work on:
     * - "midstate" : precomputed hash state after hashing the first half of the data
     * - "data" : block data
     * - "hash1" : formatted hash buffer for second hash
     * - "target" : little endian hash target
     * If [data] is specified, tries to solve the block and returns true
     * if it was successful.
     * [data]
     *
     * @param null $data
     * @return mixed|String
     */
    public function getWork($data = null)
    {
        return $this->_call('getwork', [$data]);
    }

    /**
     * List commands, or get help for a command.
     * [command]
     *
     * @param string $command
     * @return mixed|String
     */
    public function help(string $command)
    {
        return $this->_call('help', [$command]);
    }

    /**
     * Adds a private key (as returned by dumpprivkey) to your wallet.
     * This may take a while, as a rescan is done, looking for existing transactions.
     * Optional [rescan] parameter added in 0.8.0.
     * Note: There's no need to import public key,
     * as in ECDSA (unlike RSA) this can be computed from private key.
     * <bitcoinprivkey> [label] [rescan=true]
     *
     * @param $bitcoinPrivKey
     * @param null $label
     * @param bool $rescan
     * @return mixed|String
     */
    public function importPrivKey($bitcoinPrivKey, $label = null, bool $rescan = true)
    {
        return $this->_call('importprivkey', [$bitcoinPrivKey, $label, $rescan]);
    }

    /**
     * Permanently marks a block as invalid, as if it violated a consensus rule.
     * <hash>
     *
     * @param string $hash
     * @return mixed|String
     */
    public function invalidateBlock(string $hash)
    {
        return $this->_call('invalidateblock', [$hash]);
    }

    /**
     * Fills the keypool, requires wallet passphrase to be set.
     *
     * @return mixed|String
     */
    public function keyPoolRefill()
    {
        return $this->_call('keypoolrefill');
    }

    /**
     * Returns Object that has account names as keys,
     * account balances as values.
     * [minconf=1]
     *
     * @param int $minConf
     * @return mixed|String
     */
    public function listAccounts(int $minConf = 1)
    {
        return $this->_call('listaccounts', [$minConf]);
    }

    /**
     * version 0.7
     * Returns all addresses in the wallet and info used for coincontrol.
     *
     * @return mixed|String
     */
    public function listAddressGroupings()
    {
        return $this->_call('listaddressgroupings');
    }

    /**
     * Returns an array of objects containing:
     * - "account" : the account of the receiving addresses
     * - "amount" : total amount received by addresses with this account
     * - "confirmations" : number of confirmations of the most recent transaction included
     * [minconf=1] [includeempty=false]
     *
     * @param int $minConf
     * @param bool $includeEmpty
     * @return mixed|String
     */
    public function listReceivedByAccount(int $minConf = 1, bool $includeEmpty = false)
    {
        return $this->_call('listreceivedbyaccount', [$minConf, $includeEmpty]);
    }

    /**
     * Returns an array of objects containing:
     * - "address" : receiving address
     * - "account" : the account of the receiving address
     * - "amount" : total amount received by the address
     * - "confirmations" : number of confirmations of the most recent transaction included
     * To get a list of accounts on the system,
     * execute bitcoind listreceivedbyaddress 0 true
     * [minconf=1] [includeempty=false]
     *
     * @param int $minConf
     * @param bool $includeEmpty
     * @return mixed|String
     */
    public function listReceivedByAddress(int $minConf = 1, bool $includeEmpty = false)
    {
        return $this->_call('listreceivedbyaddress', [$minConf, $includeEmpty]);
    }

    /**
     * Get all transactions in blocks since block [blockhash],
     * or all transactions if omitted.
     * [target-confirmations] intentionally does not affect the list
     * of returned transactions, but only affects the returned "lastblock" value.
     * [blockhash] [target-confirmations]
     *
     * @param null $blockHash
     * @param null $targetConfirmations
     * @return mixed|String
     */
    public function listSinceBlock($blockHash = null, $targetConfirmations = null)
    {
        return $this->_call('listsinceblock', [$blockHash, $targetConfirmations]);
    }

    /**
     * Returns up to [count] most recent transactions skipping
     * the first [from] transactions for account [account].
     * If [account] not provided it'll return recent transactions from all accounts.
     * [account] [count=10] [from=0]
     *
     * @param $account
     * @param int $count
     * @param int $from
     * @return mixed|String
     */
    public function listTransactions($account, int $count = 10, int $from = 0)
    {
        return $this->_call('listtransactions', [$account, $count, $from]);
    }

    /**
     * version 0.7
     * Returns array of unspent transaction inputs in the wallet.
     * [minconf=1] [maxconf=999999]
     *
     * @param int $minConf
     * @param int $maxConf
     * @return mixed|String
     */
    public function listUnspent(int $minConf = 1, int $maxConf = 999999)
    {
        return $this->_call('listunspent', [$minConf, $maxConf]);
    }

    /**
     * version 0.8
     * Returns list of temporarily unspendable outputs
     *
     * @return mixed|String
     */
    public function listLockUnspent()
    {
        return $this->_call('listlockunspent');
    }

    /**
     * version 0.8
     * Updates list of temporarily unspendable outputs
     * <unlock?> [array-of-objects]
     *
     * @param $unlock
     * @param array $arrayOfObjects
     * @return mixed|String
     */
    public function lockUnspent($unlock, array $arrayOfObjects = [])
    {
        return $this->_call('lockunspent', [$unlock, $arrayOfObjects]);
    }

    /**
     * Move from one account in your wallet to another
     * <fromaccount> <toaccount> <amount> [minconf=1] [comment]
     *
     * @param $fromAccount
     * @param $toAccount
     * @param $amount
     * @param int $minConf
     * @param string $comment
     * @return mixed|String
     */
    public function move($fromAccount, $toAccount, $amount, int $minConf = 1, string $comment = '')
    {
        return $this->_call('move', [$fromAccount, $toAccount, $amount, $minConf, $comment]);
    }

    /**
     * <amount> is a real and is rounded to 8 decimal places.
     * Will send the given amount to the given address,
     * ensuring the account has a valid balance using [minconf] confirmations.
     * Returns the transaction ID if successful (not in JSON object).
     * <fromaccount> <tobitcoinaddress> <amount> [minconf=1] [comment] [comment-to]
     *
     * @param $fromAccount
     * @param $toBitcoinAddress
     * @param $amount
     * @param int $minConf
     * @param string $comment
     * @param string $commentTo
     * @return mixed|String
     */
    public function sendFrom($fromAccount, $toBitcoinAddress, $amount, int $minConf = 1, string $comment = '', string $commentTo = '')
    {
        return $this->_call('sendfrom', [$fromAccount, $toBitcoinAddress, $amount, $minConf, $comment, $commentTo]);
    }

    /**
     * version 0.7
     * Submits raw transaction (serialized, hex-encoded) to local node and network.
     * <hexstring>
     *
     * @param string $hexString
     * @return mixed|String
     */
    public function sendRawTransaction(string $hexString)
    {
        return $this->_call('sendrawtransaction', [$hexString]);
    }

    /**
     * <amount> is a real and is rounded to 8 decimal places.
     * Returns the transaction ID <txid> if successful.
     * <bitcoinaddress> <amount> [comment] [comment-to]
     *
     * @param $bitcoinAddress
     * @param $amount
     * @param string $comment
     * @param string $commentTo
     * @return mixed|String
     */
    public function sendToAddress($bitcoinAddress, $amount, string $comment = '', string $commentTo = '')
    {
        return $this->_call('sendtoaddress', [$bitcoinAddress, $amount, $comment, $commentTo]);
    }

    /**
     * Sets the account associated with the given address.
     * Assigning address that is already assigned to the same
     * account will create a new address associated with that account.
     * <bitcoinaddress> <account>
     *
     * @param $bitcoinAddress
     * @param $account
     * @return mixed|String
     */
    public function setAccount($bitcoinAddress, $account)
    {
        return $this->_call('setaccount', [$bitcoinAddress, $account]);
    }

    /**
     * <generate> is true or false to turn generation on or off.
     * Generation is limited to [genproclimit] processors, -1 is unlimited.
     * <generate> [genproclimit]
     *
     * @param $generate
     * @param null $genProcLimit
     * @return mixed|String
     */
    public function setGenerate($generate, $genProcLimit = null)
    {
        return $this->_call('setgenerate', [$generate, $genProcLimit]);
    }

    /**
     * <amount> is a real and is rounded to the nearest 0.00000001
     * <amount>
     *
     * @param $amount
     * @return mixed|String
     */
    public function setTxFee($amount)
    {
        return $this->_call('settxfee', [$amount]);
    }

    /**
     * Sign a message with the private key of an address.
     * <bitcoinaddress> <message>
     *
     * @param $bitcoinAddress
     * @param $message
     * @return mixed|String
     */
    public function signMessage($bitcoinAddress, $message)
    {
        return $this->_call('signmessage', [$bitcoinAddress, $message]);
    }

    /**
     * version 0.7
     * Adds signatures to a raw transaction and returns the resulting raw transaction.
     * <hexstring> [{"txid":txid,"vout":n,"scriptPubKey":hex},...] [<privatekey1>,...]
     *
     * @param $hexString
     * @param array $data
     * @param array $privateKeys
     * @return mixed|String
     */
    public function signRawTransaction($hexString, array $data = [], array $privateKeys = [])
    {
        return $this->_call('signrawtransaction', [$hexString, $data, $privateKeys]);
    }

    /**
     * @return mixed|String
     */
    public function stop()
    {
        return $this->_call('stop');
    }

    /**
     * Attempts to submit new block to network.
     * <hex data> [optional-params-obj]
     *
     * @param $hexData
     * @param null $params
     * @return mixed|String
     */
    public function submitBlock($hexData, $params = null)
    {
        return $this->_call('submitblock', [$hexData, $params]);
    }

    /**
     * Return information about <bitcoinaddress>.
     * <bitcoinaddress>
     *
     * @param $bitcoinAddress
     * @return mixed|String
     */
    public function validateAddress($bitcoinAddress)
    {
        return $this->_call('validateaddress', [$bitcoinAddress]);
    }

    /**
     * Verify a signed message.
     * <bitcoinaddress> <signature> <message>
     *
     * @param $bitcoinAddress
     * @param $signature
     * @param $message
     * @return mixed|String
     */
    public function verifyMessage($bitcoinAddress, $signature, $message)
    {
        return $this->_call('verifymessage', [$bitcoinAddress, $signature, $message]);
    }

    /**
     * Removes the wallet encryption key from memory, locking the wallet.
     * After calling this method, you will need to call walletpassphrase
     * again before being able to call any methods which require
     * the wallet to be unlocked.
     *
     * @return mixed|String
     */
    public function walletLock()
    {
        return $this->_call('walletlock');
    }

    /**
     * Stores the wallet decryption key in memory for <timeout> seconds.
     * <passphrase> <timeout>
     *
     * @param $passPhrase
     * @param $timeout
     * @return mixed|String
     */
    public function walletPassPhrase($passPhrase, $timeout)
    {
        return $this->_call('walletpassphrase', [$passPhrase, $timeout]);
    }

    /**
     * Changes the wallet passphrase from <oldpassphrase> to <newpassphrase>.
     * <oldpassphrase> <newpassphrase>
     *
     * @param $oldPassPhrase
     * @param $newPassPhrase
     * @return mixed|String
     */
    public function walletPassPhraseChange($oldPassPhrase, $newPassPhrase)
    {
        return $this->_call('walletpassphrasechange', [$oldPassPhrase, $newPassPhrase]);
    }
}