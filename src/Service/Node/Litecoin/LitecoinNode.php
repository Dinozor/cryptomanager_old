<?php

namespace App\Service\Node\Litecoin;

use App\Service\Node\BaseNode;
use App\Service\NodeDataManager;

class LitecoinNode extends BaseNode
{
    private $rootWallet;
    private $dataManager;

    public function __construct(NodeDataManager $dataManager = null, ?string $rootWallet = null, $settings = null)
    {
        parent::__construct('test', '123456');
        $this->dataManager = $dataManager;
        $this->rootWallet = $rootWallet;
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
     * @param string $account
     * @param int $minConf
     * @return mixed|String
     */
    public function getBalance(string $account = '', int $minConf = 1)
    {
        return $this->_call('getbalance', [$account, $minConf]);
    }

    /**
     * version 0.8.6.1
     * Returns the hash of the newest block in the longest block chain.
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
     * Returns hash of block in best-block-chain at <index>;
     * index 0 is the genesis block
     * <index>
     *
     * @param int $index
     * @return mixed|String
     */
    public function getBlockHash(int $index)
    {
        return $this->_call('getblockhash', [$index]);
    }

    /**
     * Returns data needed to construct a block to work on
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
     * Returns the proof-of-work difficulty as
     * a multiple of the minimum difficulty.
     *
     * @return mixed|String
     */
    public function getDifficulty()
    {
        return $this->_call('getdifficulty');
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
     * blocks, currentblocksize, currentblocktx,
     * difficulty, errors, generate, genproclimit,
     * hashespersec, networkhashps, pooledtx, testnet
     *
     * @return mixed|String
     */
    public function getMiningInfo()
    {
        return $this->_call('getmininginfo');
    }

    /**
     * Returns the estimated network hashes per second based
     * on the last 120 blocks. Pass in [blocks] to override # of blocks,
     * -1 specifies since last difficulty change.
     * Pass in [height] to estimate the network speed at the time
     * when a certain block was found.
     * Optional [height] parameter added in 0.8.4.
     * [blocks] [height]
     *
     * @param int $blocks
     * @param $height
     * @return mixed|String
     */
    public function getNetworkHashPs(int $blocks, $height)
    {
        return $this->_call('getnetworkhashps', [$blocks, $height]);
    }

    /**
     * Returns a new Litecoin address for receiving payments.
     * If [account] is specified (recommended),
     * it is added to the address book so payments received
     * with the address will be credited to [account].
     * [account]
     *
     * @param string $account
     * @return mixed|String
     */
    public function getNewAddress(string $account)
    {
        return $this->_call('getnewaddress', [$account]);
    }

    /**
     * Returns data about each connected node.
     *
     * @return mixed|String
     */
    public function getPeerInfo()
    {
        return $this->_call('getpeerinfo');
    }

    /**
     * Returns all transaction ids in memory pool.
     *
     * @return mixed|String
     */
    public function getRawMemPool()
    {
        return $this->_call('getrawmempool');
    }

    /**
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
     * Returns the total amount received by addresses with
     * [account] in transactions with at least [minconf] confirmations.
     * If [account] not provided return will include
     * all transactions to all accounts.
     * [account] [minconf=1]
     *
     * @param string $account
     * @param int $minConf
     * @return mixed|String
     */
    public function getReceivedByAccount(string $account, int $minConf = 1)
    {
        return $this->_call('getreceivedbyaccount', [$account, $minConf]);
    }

    /**
     * Returns the total amount received by <litecoinaddress>
     * in transactions with at least [minconf] confirmations.
     * While some might consider this obvious, value reported
     * by this only considers *receiving* transactions.
     * It does not check payments that have been made *from* this address.
     * In other words, this is not "getaddressbalance".
     * Works only for addresses in the local wallet,
     * external addresses will always show 0.
     * <litecoinaddress> [minconf=1]
     *
     * @param string $litecoinAddress
     * @param int $minConf
     * @return mixed|String
     */
    public function getReceivedByAddress(string $litecoinAddress, int $minConf = 1)
    {
        return $this->_call('getreceivedbyaddress', [$litecoinAddress, $minConf]);
    }

    /**
     * Returns an object about the given transaction containing:
     * amount, confirmations, txid, time[1], details
     * (an array of objects containing: account, address, category, amount, fee)
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
        return $this->_call('gettxoutsetinfo', []);
    }

    /**
     * If [data] is not specified, returns formatted hash data to work on:
     * midstate, data, hash1, target.
     * If [data] is specified, tries to solve the block and returns true
     * if it was successful.
     * [data]
     *
     * @param array|null $data
     * @return mixed|String
     */
    public function getWork(array $data = null)
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
    public function help(string $command = '')
    {
        return $this->_call('help', [$command]);
    }

    /**
     * Adds a private key (as returned by dumpprivkey) to your wallet.
     * This may take a while, as a rescan is done,
     * looking for existing transactions.
     * Optional [rescan] parameter added in 0.8.0.
     * <litecoinprivkey> [label] [rescan=true]
     *
     * @param string $litecoinPrivKey
     * @param string $label
     * @param bool $rescan
     * @return mixed|String
     */
    public function importPrivKey(string $litecoinPrivKey, string $label = '', bool $rescan = true)
    {
        return $this->_call('importprivkey', [$litecoinPrivKey, $label, $rescan]);
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
     * Returns all addresses in the wallet and info used for coincontrol.
     *
     * @return mixed|String
     */
    public function listAddressGroupings()
    {
        return $this->_call('listaddressgroupings');
    }

    /**
     * OMG2 only Returns a list of currently
     * banned nodes along with the ban expiration timestamps.
     *
     * @return mixed|String
     */
    public function listBannedNodes()
    {
        return $this->_call('listbannednodes');
    }

    /**
     * Returns list of temporarily unspendable outputs.
     *
     * @return mixed|String
     */
    public function listLockUnspent()
    {
        return $this->_call('listlockunspent');
    }

    /**
     * Returns an array of objects containing: account, amount, confirmations
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
     * address, account, amount, confirmations.
     * To get a list of accounts on the system,
     * execute litecoind listreceivedbyaddress 0 true
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
     * [blockhash] [target-confirmations]
     *
     * @param string $blockHash
     * @param int $targetConfirmations
     * @return mixed|String
     */
    public function listSinceBlock(string $blockHash = '', int $targetConfirmations = 0)
    {
        return $this->_call('listsinceblock', [$blockHash, $targetConfirmations]);
    }

    /**
     * Returns up to [count] most recent transactions skipping
     * the first [from] transactions for account [account].
     * If [account] not provided will return recent transaction from all accounts.
     * [account] [count=10] [from=0]
     *
     * @param string $account
     * @param int $count
     * @param int $from
     * @return mixed|String
     */
    public function listTransactions(string $account = '', int $count = 10, int $from = 0)
    {
        return $this->_call('listtransactions', [$account, $count, $from]);
    }

    /**
     * Returns array of unspent transaction inputs in the wallet.
     * [minconf=1] [maxconf=9999999] ["address",...]
     *
     * @param int $minConf
     * @param int $maxConf
     * @param array $data
     * @return mixed|String
     */
    public function listUnspent(int $minConf = 1, int $maxConf = 9999999, array $data = [])
    {
        return $this->_call('listunspent', [$minConf. $maxConf, $data]);
    }

    /**
     * version 0.8
     * Updates list of temporarily unspendable outputs
     * <unlock?> [array-of-Objects]
     *
     * @param $unlock
     * @param array $objects
     * @return mixed|String
     */
    public function lockUnspent($unlock, array $objects = [])
    {
        return $this->_call('lockunspent', [$unlock, $objects]);
    }

    /**
     * Move from one account in your wallet to another
     * <fromaccount> <toaccount> <amount> [minconf=1] [comment]
     *
     * @param string $fromAccount
     * @param string $toAccount
     * @param float $amount
     * @param int $minConf
     * @param string $comment
     * @return mixed|String
     */
    public function move(string $fromAccount, string $toAccount, float $amount, int $minConf = 1, string $comment = '')
    {
        return $this->_call('move', [$fromAccount, $toAccount, $amount, $minConf, $comment]);
    }

    /**
     * <amount> is a real and is rounded to 8 decimal places.
     * Will send the given amount to the given address,
     * ensuring the account has a valid balance using [minconf] confirmations.
     * Returns the transaction ID if successful (not in JSON object).
     * <fromaccount> <tolitecoinaddress> <amount> [minconf=1] [comment] [comment-to]
     *
     * @param string $fromAccount
     * @param string $toLitecoinAddress
     * @param float $amount
     * @param int $minConf
     * @param string $comment
     * @param string $commentTo
     * @return mixed|String
     */
    public function sendFrom(string $fromAccount, string $toLitecoinAddress, float $amount, int $minConf = 1, string $comment = '',  string $commentTo = '')
    {
        return $this->_call('sendfrom', [$fromAccount, $toLitecoinAddress, $amount, $minConf, $comment, $commentTo]);
    }

    /**
     * Amounts are double-precision floating point numbers
     * <fromaccount> {address:amount,...} [minconf=1] [comment]
     *
     * @param string $fromAccount
     * @param array $data
     * @param int $minConf
     * @param string $comment
     * @return mixed|String
     */
    public function sendMany(string $fromAccount, array $data = [], int $minConf = 1, string $comment = '')
    {
        return $this->_call('sendmany', [$fromAccount, $data, $minConf, $comment]);
    }

    /**
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
     * <litecoinaddress> <amount> [comment] [comment-to]
     *
     * @param string $litecoinAddress
     * @param float $amount
     * @param string $comment
     * @param string $commentTo
     * @return mixed|String
     */
    public function sendToAddress(string $litecoinAddress, float $amount, string $comment = '', string $commentTo = '')
    {
        return $this->_call('sendtoaddress', [$litecoinAddress, $amount, $comment, $commentTo]);
    }

    /**
     * Sets the account associated with the given address.
     * Assigning address that is already assigned to the same account
     * will create a new address associated with that account.
     * <litecoinaddress> <account>
     *
     * @param string $litecoinAddress
     * @param string $account
     * @return mixed|String
     */
    public function setAccount(string $litecoinAddress, string $account)
    {
        return $this->_call('setaccount', [$litecoinAddress, $account]);
    }

    /**
     * <amount> is a real and is rounded to the nearest 0.00000001
     * <amount>
     *
     * @param float $amount
     * @return mixed|String
     */
    public function setMinInput(float $amount)
    {
        return $this->_call('setmininput', [$amount]);
    }

    /**
     * <amount> is a real and is rounded to the nearest 0.00000001
     * <amount>
     *
     * @param float $amount
     * @return mixed|String
     */
    public function setTxFee(float $amount)
    {
        return $this->_call('settxfee', [$amount]);
    }

    /**
     * Sign a message with the private key of an address.
     * <litecoinaddress> <message>
     *
     * @param string $litecoinAddress
     * @param string $message
     * @return mixed|String
     */
    public function signMessage(string $litecoinAddress, string $message)
    {
        return $this->_call('signmessage', [$litecoinAddress, $message]);
    }

    /**
     * Adds signatures to a raw transaction and
     * returns the resulting raw transaction.
     * <hexstring> [{"txid":txid,"vout":n,"scriptPubKey":hex},...] [<privatekey1>,...]
     *
     * @param string $hexString
     * @param array $data
     * @param array $privateKeys
     * @return mixed|String
     */
    public function signRawTransaction(string $hexString, array $data = [], array $privateKeys = [])
    {
        return $this->_call('signrawtransaction', [$hexString, $data, $privateKeys]);
    }

    /**
     * Stop Litecoin server.
     *
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
     * @param array $params
     * @return mixed|String
     */
    public function submitBlock($hexData, array $params = [])
    {
        return $this->_call('submitblock', [$hexData, $params]);
    }

    /**
     * Return information about <litecoinaddress>.
     * <litecoinaddress>
     *
     * @param string $litecoinAddress
     * @return mixed|String
     */
    public function validateAddress(string $litecoinAddress)
    {
        return $this->_call('validateaddress', [$litecoinAddress]);
    }

    /**
     * version 0.8.6.1
     * Verifies chain database at runtime.
     *
     * @return mixed|String
     */
    public function verifyChain()
    {
        return $this->_call('verifychain');
    }

    /**
     * Verifies a signed message.
     * <litecoinaddress> <signature> <message>
     *
     * @param string $litecoinAddress
     * @param string $signature
     * @param string $message
     * @return mixed|String
     */
    public function verifyMessage(string $litecoinAddress, string $signature, string $message)
    {
        return $this->_call('verifymessage', [$litecoinAddress, $signature, $message]);
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
     * @param string $passPhrase
     * @param int $timeout
     * @return mixed|String
     */
    public function walletPassPhrase(string $passPhrase, int $timeout)
    {
        return $this->_call('walletpassphrase', [$passPhrase, $timeout]);
    }

    /**
     * Changes the wallet passphrase from <oldpassphrase> to <newpassphrase>.
     * <oldpassphrase> <newpassphrase>
     *
     * @param string $oldPassPhrase
     * @param string $newPassPhrase
     * @return mixed|String
     */
    public function walletPassPhraseChange(string $oldPassPhrase, string $newPassPhrase)
    {
        return $this->_call('walletpassphrasechange', [$oldPassPhrase, $newPassPhrase]);
    }
}