<?php

namespace App\Service\Node\Bitcoin;

use App\Service\Node\BaseNode;
use App\Service\NodeDataManager;

class BitcoinNode extends BaseNode
{
    private $rootWallet;
    private $dataManager;

    public function __construct(NodeDataManager $dataManager = null, ?string $rootWallet = null, $settings = null)
    {
        parent::__construct('test', '123456', '127.0.0.1', '18332');
    }

    /*== Blockchain ==*/

    /**
     * version 0.9
     * Returns the hash of the best (tip) block in the longest block chain.
     *
     * @return mixed
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
     * @return mixed
     */
    public function getBlock(string $hash)
    {
        return $this->_call('getblock', [$hash]);
    }

    /**
     * Returns an object containing various state info
     * regarding blockchain processing.
     *
     * @return mixed
     */
    public function getBlockChainInfo()
    {
        return $this->_call('getblockchaininfo');
    }

    /**
     * Returns the number of blocks in the longest block chain.
     *
     * @return mixed
     */
    public function getBlockCount()
    {
        return $this->_call('getblockcount');
    }

    /**
     * Returns hash of block in best-block-chain at <index>; index 0 is the genesis block
     * <index>
     *
     * @param int $index
     * @return mixed
     */
    public function getBlockHash(int $index)
    {
        return $this->_call('getblockhash', [$index]);
    }

    /**
     * If verbose is false, returns a string that is serialized,
     * hex-encoded data for blockheader 'hash'.
     * If verbose is true, returns an Object with information
     * about blockheader <hash>.
     * <hash> [verbose=true]
     *
     * @param string $hash
     * @param bool $verbose
     * @return mixed
     */
    public function getBlockHeader(string $hash, bool $verbose = true)
    {
        return $this->_call('getblockheader', [$hash, $verbose]);
    }

    /**
     * Return information about all known tips in the block tree,
     * including the main chain as well as orphaned branches.
     *
     * @return mixed
     */
    public function getChainTips()
    {
        return $this->_call('getchaintips');
    }

    /**
     * Compute statistics about the total number
     * and rate of transactions in the chain.
     * [nblocks=1] [blockhash]
     *
     * @param int $nBlocks
     * @param string $blockHash
     * @return mixed
     */
    public function getChainTxStats(int $nBlocks = 1, string $blockHash = '')
    {
        return $this->_call('getchaintxstats', [$nBlocks, $blockHash]);
    }

    /**
     * Returns the proof-of-work difficulty as a multiple of the minimum difficulty.
     *
     * @return mixed
     */
    public function getDifficulty()
    {
        return $this->_call('getdifficulty');
    }

    /**
     * If txid is in the mempool, returns all in-mempool ancestors.
     * <txid> [verbose=false]
     *
     * @param string $txId
     * @param bool $verbose
     * @return mixed
     */
    public function getMemPoolAncestors(string $txId, bool $verbose = false)
    {
        return $this->_call('getmempoolancestors', [$txId, $verbose]);
    }

    /**
     * If txid is in the mempool, returns all in-mempool descendants.
     * <txid> [verbose=false]
     *
     * @param string $txId
     * @param bool $verbose
     * @return mixed
     */
    public function getMemPoolDescendants(string $txId, bool $verbose = false)
    {
        return $this->_call('getmempooldescendants', [$txId, $verbose]);
    }

    /**
     * Returns mempool data for given transaction
     * <txid>
     *
     * @param string $txId
     * @return mixed
     */
    public function getMemPoolEntry(string $txId)
    {
        return $this->_call('getmempoolentry', [$txId]);
    }

    /**
     * Returns details on the active state of the TX memory pool.
     *
     * @return mixed
     */
    public function getMemPoolInfo()
    {
        return $this->_call('getmempoolinfo');
    }

    /**
     * version 0.7
     * Returns all transaction ids in memory pool
     *
     * @return mixed
     */
    public function getRawMemPool()
    {
        return $this->_call('getrawmempool');
    }

    /**
     * Returns details about an unspent transaction output (UTXO)
     * <txid> <n> [includemempool=true]
     *
     * @param string $txId
     * @param int $n
     * @param bool $includeMemPool
     * @return mixed
     */
    public function getTxOut(string $txId, int $n, bool $includeMemPool = true)
    {
        return $this->_call('gettxout', [$txId, $n, $includeMemPool]);
    }

    /**
     * Returns a hex-encoded proof that "txid" was included in a block.
     * NOTE: By default this function only works sometimes.
     * This is when there is an unspent output in the utxo for this transaction.
     * To make it always work, you need to maintain a transaction index,
     * using the -txindex command line option or specify
     * the block in which the transaction is included manually (by blockhash).
     * <txids> <blockhash>
     *
     * @param array $txIds
     * @param string $blockHash
     * @return mixed
     */
    public function getTxOutProof(array $txIds, string $blockHash)
    {
        return $this->_call('gettxoutproof', [$txIds, $blockHash]);
    }

    /**
     * Returns statistics about the unspent transaction output (UTXO) set
     *
     * @return mixed
     */
    public function getTxOutSetInfo()
    {
        return $this->_call('gettxoutsetinfo');
    }

    /**
     * Treats a block as if it were received before others with the same work.
     * A later preciousblock call can override the effect of an earlier one.
     * The effects of preciousblock are not retained across restarts.
     * <blockhash>
     *
     * @param string $blockHash
     * @return mixed
     */
    public function preciousBlock(string $blockHash)
    {
        return $this->_call('preciousblock', [$blockHash]);
    }

    /**
     * <height>
     *
     * @param int $height
     * @return mixed
     */
    public function pruneBlockChain(int $height)
    {
        return $this->_call('pruneblockchain', [$height]);
    }

    /**
     * Dumps the mempool to disk.
     *
     * @return mixed
     */
    public function saveMemPool()
    {
        return $this->_call('savemempool');
    }

    /**
     * Verifies blockchain database.
     * [checklevel=3] [nblocks=6]
     *
     * @param int $checkLevel
     * @param int $nBlocks
     * @return mixed
     */
    public function verifyChain(int $checkLevel = 3, int $nBlocks = 6)
    {
        return $this->_call('verifychain', [$checkLevel, $nBlocks]);
    }

    /**
     * Verifies that a proof points to a transaction in a block,
     * returning the transaction it commits to
     * and throwing an RPC error if the block is not in our best chain
     * <proof>
     *
     * @param string $proof
     * @return mixed
     */
    public function verifyTxOutProof(string $proof)
    {
        return $this->_call('verifytxoutproof', [$proof]);
    }

    /*== Control ==*/

    /**
     * Returns an object containing information about memory usage.
     * [mode]
     *
     * @param string $mode
     * @return mixed
     */
    public function getMemoryInfo(string $mode = '')
    {
        return $this->_call('getmemoryinfo', [$mode]);
    }

    /**
     * List commands, or get help for a command.
     * [command]
     *
     * @param string $command
     * @return mixed
     */
    public function help(string $command = '')
    {
        return $this->_call('help', [$command]);
    }

    /**
     * Stop Bitcoin server.
     *
     * @return mixed
     */
    public function stop()
    {
        return $this->_call('stop');
    }

    /**
     * Returns the total uptime of the server.
     *
     * @return mixed
     */
    public function upTime()
    {
        return $this->_call('uptime');
    }

    /*== Generating ==*/

    /**
     * Returns true or false whether bitcoind is currently generating hashes
     *
     * @return mixed
     */
    public function getGenerate()
    {
        return $this->_call('getgenerate');
    }

    /**
     * Mine blocks immediately to a specified address
     * (before the RPC call returns)
     * <nblocks> <address> [maxtries=1000000]
     *
     * @param float $nBlocks
     * @param string $address
     * @param int $maxTries
     * @return mixed
     */
    public function generateToAddress(float $nBlocks, string $address, int $maxTries = 1000000)
    {
        return $this->_call('generatetoaddress', [$nBlocks, $address, $maxTries]);
    }

    /*== Mining ==*/

    /**
     * Returns data needed to construct a block to work on.
     * See BIP_0022 for more info on params.
     * [params]
     *
     * @param array $params
     * @return mixed
     */
    public function getBlockTemplate(array $params = [])
    {
        return $this->_call('getblocktemplate', [$params]);
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
     * @return mixed
     */
    public function getMiningInfo()
    {
        return $this->_call('getmininginfo');
    }

    /**
     * Returns the estimated network hashes per second based on the last n blocks.
     * Pass in [blocks] to override # of blocks,
     * -1 specifies since last difficulty change.
     * Pass in [height] to estimate the network speed
     * at the time when a certain block was found.
     * [nblocks=120] [height=-1]
     *
     * @param int $nBlocks
     * @param int $height
     * @return mixed
     */
    public function getNetworkHashPs(int $nBlocks = 120, int $height = -1)
    {
        return $this->_call('getnetworkhashps', [$nBlocks, $height]);
    }

    /**
     * Permanently marks a block as invalid, as if it violated a consensus rule.
     * <hash>
     *
     * @param string $hash
     * @return mixed
     */
    public function invalidateBlock(string $hash)
    {
        return $this->_call('invalidateblock', [$hash]);
    }

    /**
     * Accepts the transaction into mined blocks at a higher (or lower) priority
     * <txid> [dummy value=0] [fee delta=0]
     *
     * @param string $txId
     * @param float $dummyValue
     * @param int $feeDelta
     * @return mixed
     */
    public function prioritiseTransaction(string $txId, float $dummyValue = 0, int $feeDelta = 0)
    {
        return $this->_call('prioritisetransaction', [$txId, $dummyValue, $feeDelta]);
    }

    /**
     * Attempts to submit new block to network.
     * <hex data> [optional-params-obj]
     *
     * @param string $hexData
     * @param $params
     * @return mixed
     */
    public function submitBlock(string $hexData, $params = null)
    {
        return $this->_call('submitblock', [$hexData, $params]);
    }

    /*== Network ==*/

    /**
     * version 0.8
     * Attempts add or remove <node>
     * from the addnode list or try a connection to <node> once.
     * <node> <add/remove/onetry>
     *
     * @param string $node
     * @param string $action
     * @return mixed
     */
    public function addNode(string $node, string $action)
    {
        return $this->_call('addnode', [$node, $action]);
    }

    /**
     * Clear all banned IPs.
     *
     * @return mixed
     */
    public function clearBanned()
    {
        return $this->_call('clearbanned');
    }

    /**
     * Immediately disconnects from the specified peer node.
     * Strictly one out of 'address' and 'nodeid' can be provided
     * to identify the node.
     * To disconnect by nodeid, either set 'address' to the empty string,
     * or call using the named 'nodeid' argument only.
     * <address> [nodeid]
     *
     * @param string $address
     * @param int|null $nodeId
     * @return mixed
     */
    public function disconnectNode(string $address, int $nodeId = null)
    {
        return $this->_call('disconnectnode', [$address, $nodeId]);
    }

    /**
     * version 0.8
     * Returns information about the given added node, or all added nodes
     * (note that onetry addnodes are not listed here) If dns is false,
     * only a list of added nodes will be provided,
     * otherwise connected information will also be available.
     * [node]
     *
     * @param string $node
     * @return mixed
     */
    public function getAddedNodeInfo(string $node = '')
    {
        return $this->_call('getaddednodeinfo', [$node]);
    }

    /**
     * Returns the number of connections to other nodes.
     *
     * @return mixed
     */
    public function getConnectionCount()
    {
        return $this->_call('getconnectioncount');
    }

    /**
     * Returns information about network traffic, including bytes in,
     * bytes out, and current time.
     *
     * @return mixed
     */
    public function getNetTotals()
    {
        return $this->_call('getnettotals');
    }

    /**
     * Returns an object containing various state info regarding P2P networking.
     *
     * @return mixed
     */
    public function getNetWorkInfo()
    {
        return $this->_call('getnetworkinfo');
    }

    /**
     * version 0.7
     * Returns data about each connected node.
     *
     * @return mixed
     */
    public function getPeerInfo()
    {
        return $this->_call('getpeerinfo');
    }

    /**
     * List all banned IPs/Subnets.
     *
     * @return mixed
     */
    public function listBanned()
    {
        return $this->_call('listbanned');
    }

    /**
     * Requests that a ping be sent to all other nodes, to measure ping time.
     * Results provided in getpeerinfo, pingtime and pingwait fields
     * are decimal seconds. Ping command is handled in queue with all
     * other commands, so it measures processing backlog, not just network ping.
     *
     * @return mixed
     */
    public function ping()
    {
        return $this->_call('ping');
    }

    /**
     * Attempts to add or remove an IP/Subnet from the banned list.
     * <subnet> <command> [bantime] [absolute]
     *
     * @param string $subNet
     * @param string $command
     * @param int $banTime
     * @param bool $absolute
     * @return mixed
     */
    public function setBan(string $subNet, string $command, int $banTime, bool $absolute)
    {
        return $this->_call('setban', [$subNet, $command, $banTime, $absolute]);
    }

    /**
     * Disable/enable all p2p network activity.
     * <state>
     *
     * @param bool $status
     * @return mixed
     */
    public function setNetworkActive(bool $status)
    {
        return $this->_call('setnetworkactive', [$status]);
    }

    /*== Rawtransactions ==*/

    /**
     * Combine multiple partially signed transactions into one transaction.
     * The combined transaction may be another partially signed transaction
     * or a fully signed transaction.
     * <txs>
     *
     * @param array $txs
     * @return mixed
     */
    public function combineRawTransaction(array $txs)
    {
        return $this->_call('combinerawtransaction', [$txs]);
    }

    /**
     * version 0.7
     * Creates a raw transaction spending given inputs.
     * [{"txid":txid,"vout":n},...] {address:amount,...}
     *
     * @param array $transactions
     * @return mixed
     */
    public function createRawTransaction(array $transactions = [])
    {
        return $this->_call('createrawtransaction', [$transactions]);
    }

    /**
     * version 0.7
     * Produces a human-readable JSON object for a raw transaction.
     * <hex string>
     *
     * @param string $hex
     * @return mixed
     */
    public function decodeRawTransaction(string $hex)
    {
        return $this->_call('decoderawtransaction', [$hex]);
    }

    /**
     * Decode a hex-encoded script.
     * <hex string>
     *
     * @param string $hex
     * @return mixed
     */
    public function decodeScript(string $hex)
    {
        return $this->_call('decodescript', [$hex]);
    }

    /**
     * Add inputs to a transaction until it has enough in value to meet
     * its out value. This will not modify existing inputs,
     * and will add at most one change output to the outputs.
     * No existing outputs will be modified unless "subtractFeeFromOutputs"
     * is specified.
     * Note that inputs which were signed may need
     * to be resigned after completion since in/outputs have been added.
     * The inputs added will not be signed, use signrawtransaction for that.
     * Note that all existing inputs must have their previous
     * output transaction be in the wallet.
     * Note that all inputs selected
     * must be of standard form and P2SH scripts must be in the wallet
     * using importaddress or addmultisigaddress (to calculate fees).
     * You can see whether this is the case by checking the "solvable"
     * field in the listunspent output. Only pay-to-pubkey, multisig,
     * and P2SH versions thereof are currently supported for watch-only
     * <hex string> [options]
     *
     * @param string $hex
     * @param array $options
     * @return mixed
     */
    public function fundRawTransaction(string $hex, array $options = [])
    {
        return $this->_call('fundrawtransaction', [$hex, $options]);
    }

    /**
     * version 0.7
     * Returns raw transaction representation for given transaction id.
     * <txid> [verbose=0]
     *
     * @param string $txId
     * @param int $verbose
     * @return mixed
     */
    public function getRawTransaction(string $txId, int $verbose = 0)
    {
        return $this->_call('getrawtransaction', [$txId, $verbose]);
    }

    /**
     * version 0.7
     * Submits raw transaction (serialized, hex-encoded) to local node and network.
     * <hex string>
     *
     * @param string $hex
     * @return mixed
     */
    public function sendRawTransaction(string $hex)
    {
        return $this->_call('sendrawtransaction', [$hex]);
    }

    /**
     * version 0.7
     * Adds signatures to a raw transaction and returns the resulting raw transaction.
     * <hexstring> [{"txid":txid,"vout":n,"scriptPubKey":hex},...] [<privatekey1>,...]
     *
     * @param string $hexString
     * @param array $data
     * @param array $privateKeys
     * @return mixed
     */
    public function signRawTransaction(string $hexString, array $data = [], array $privateKeys = [])
    {
        return $this->_call('signrawtransaction', [$hexString, $data, $privateKeys]);
    }

    /*== Util ==*/

    /**
     * Creates a multi-signature address and returns a json object
     * <nrequired> <'["key,"key"]'>
     *
     * @param int $nRequired
     * @param array $keys
     * @return mixed
     */
    public function createMultiSig(int $nRequired, array $keys)
    {
        return $this->_call('createmultisig', [$nRequired, $keys]);
    }

    /**
     * DEPRECATED. Please use estimatesmartfee for more intelligent estimates.
     * Estimates the approximate fee per kilobyte needed for a transaction
     * to begin confirmation within nblocks blocks.
     * Uses virtual transaction size of transaction as defined in BIP 141
     * (witness data is discounted).
     * <nblocks>
     *
     * @param int $nBlocks
     * @return mixed
     */
    public function estimateFee(int $nBlocks)
    {
        return $this->_call('estimatefee', [$nBlocks]);
    }

    /**
     * Estimates the approximate fee per kilobyte needed for a transaction
     * to begin confirmation within conf_target blocks if possible
     * and return the number of blocks for which the estimate is valid.
     * Uses virtual transaction size as defined in BIP 141
     * (witness data is discounted).
     * <conf_target> [estimate_mode=CONSERVATIVE]
     *
     * @param int $confTarget
     * @param string $estimateMode
     * @return mixed
     */
    public function estimateSmartFee(int $confTarget, string $estimateMode = 'CONSERVATIVE')
    {
        return $this->_call('estimatesmartfee', [$confTarget, $estimateMode]);
    }

    /**
     * Sign a message with the private key of an address
     * <privkey> <message>
     *
     * @param string $privKey
     * @param string $message
     * @return mixed
     */
    public function signMessageWithPrivKey(string $privKey, string $message)
    {
        return $this->_call('signmessagewithprivkey', [$privKey, $message]);
    }

    /**
     * Return information about <bitcoinaddress>.
     * <bitcoinaddress>
     *
     * @param string $bitcoinAddress
     * @return mixed
     */
    public function validateAddress(string $bitcoinAddress)
    {
        return $this->_call('validateaddress', [$bitcoinAddress]);
    }

    /**
     * Verify a signed message.
     * <bitcoinaddress> <signature> <message>
     *
     * @param string $bitcoinAddress
     * @param string $signature
     * @param string $message
     * @return mixed
     */
    public function verifyMessage(string $bitcoinAddress, string $signature, string $message)
    {
        return $this->_call('verifymessage', [$bitcoinAddress, $signature, $message]);
    }

    /*== Wallet ==*/

    /**
     * Mark in-wallet transaction <txid> as abandoned
     * This will mark this transaction and all its in-wallet
     * descendants as abandoned which will allow for their inputs to be respent.
     * It can be used to replace "stuck" or evicted transactions.
     * It only works on transactions which are not included
     * in a block and are not currently in the mempool.
     * It has no effect on transactions which are already conflicted or abandoned.
     * <txid>
     *
     * @param string $txId
     * @return mixed
     */
    public function abandonTransaction(string $txId)
    {
        return $this->_call('abandontransaction', [$txId]);
    }

    /**
     * Stops current wallet rescan triggered by an RPC call,
     * e.g. by an importprivkey call.
     *
     * @return mixed
     */
    public function abortRescan()
    {
        return $this->_call('abortrescan');
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
     * @return mixed
     */
    public function addMultiSigAddress(string $nRequired, array $keys, string $account = '')
    {
        return $this->_call('addmultisigaddress', [$nRequired, $keys, $account]);
    }

    /**
     * Safely copies wallet.dat to destination,
     * which can be a directory or a path with filename.
     * <destination>
     *
     * @param string $destination
     * @return mixed
     */
    public function backupWallet(string $destination)
    {
        return $this->_call('backupwallet', [$destination]);
    }

    /**
     * Bumps the fee of an opt-in-RBF transaction T, replacing it with
     * a new transaction B. An opt-in RBF transaction with
     * the given txid must be in the wallet. The command will pay
     * the additional fee by decreasing (or perhaps removing) its change output.
     * If the change output is not big enough to cover the increased fee,
     * the command will currently fail instead of adding new inputs to compensate.
     * (A future implementation could improve this.)
     * <txid>
     *
     * @param string $txId
     * @return mixed
     */
    public function bumpFee(string $txId)
    {
        return $this->_call('bumpfee', [$txId]);
    }

    /**
     * Reveals the private key corresponding to <bitcoinaddress>
     * <bitcoinaddress>
     *
     * @param string $bitcoinAddress
     * @return mixed
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
     * @return mixed
     */
    public function dumpWallet(string $filename)
    {
        return $this->_call('dumpwallet', [$filename]);
    }

    /**
     * Encrypts the wallet with <passphrase>
     * <passphrase>
     *
     * @param string $passPhrase
     * @return mixed
     */
    public function encryptWallet(string $passPhrase)
    {
        return $this->_call('encryptwallet', [$passPhrase]);
    }

    /**
     * Returns the account associated with the given address.
     * <bitcoinaddress>
     *
     * @param string $bitcoinAddress
     * @return mixed
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
     * @param string $account
     * @return mixed
     */
    public function getAccountAddress(string $account)
    {
        return $this->_call('getaccountaddress', [$account]);
    }

    /**
     * Returns the list of addresses for the given account.
     * <account>
     *
     * @param string $account
     * @return mixed
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
     * @param int $minConf
     * @return mixed
     */
    public function getBalance(string $account = '', int $minConf = 1)
    {
        return $this->_call('getbalance', [$account, $minConf]);
    }

    /**
     * Returns a new bitcoin address for receiving payments.
     * If [account] is specified payments received with the address
     * will be credited to [account].
     * [account]
     *
     * @param string $account
     * @return mixed
     */
    public function getNewAddress(string $account = '')
    {
        return $this->_call('getnewaddress', [$account]);
    }

    /**
     * version 0.9
     * Returns a new Bitcoin address, for receiving change.
     * This is for use with raw transactions, NOT normal use.
     * [account]
     *
     * @param string $account
     * @return mixed
     */
    public function getRawChangeAddress(string $account = '')
    {
        return $this->_call('getrawchangeaddress', [$account]);
    }

    /**
     * version 0.3.24
     * Returns the total amount received by addresses with [account]
     * in transactions with at least [minconf] confirmations.
     * If [account] not provided return will include all transactions to all accounts.
     * [account] [minconf=1]
     *
     * @param string $account
     * @param int $minConf
     * @return mixed
     */
    public function getReceivedByAccount(string $account, int $minConf = 1)
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
     * @return mixed
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
     * @param string $txId
     * @return mixed
     */
    public function getTransaction(string $txId)
    {
        return $this->_call('gettransaction', [$txId]);
    }

    /**
     * Returns the server's total unconfirmed balance
     *
     * @return mixed
     */
    public function getUnconfirmedBalance()
    {
        return $this->_call('getunconfirmedbalance');
    }

    /**
     * Returns an object containing various state info.
     *
     * @return mixed
     */
    public function getWalletInfo()
    {
        return $this->_call('getwalletinfo');
    }

    /**
     * Adds a script (in hex) or address that can be watched
     * as if it were in your wallet but cannot be used to spend.
     * Requires a new wallet backup.
     * <script> [label] [rescan=true]
     *
     * @param string $script
     * @param string $label
     * @param bool $rescan
     * @return mixed
     */
    public function importAddress(string $script, string $label = '', bool $rescan = true)
    {
        return $this->_call('importaddress', [$script, $label, $rescan]);
    }

    /**
     * Import addresses/scripts (with private or public keys, redeem script (P2SH)),
     * rescanning all addresses in one-shot-only
     * (rescan can be disabled via options).
     * Requires a new wallet backup.
     * <requests>
     *
     * @param array $requests
     * @return mixed
     */
    public function importMulti(array $requests)
    {
        return $this->_call('importmulti', [$requests]);
    }

    /**
     * Adds a private key (as returned by dumpprivkey) to your wallet.
     * This may take a while, as a rescan is done, looking for existing transactions.
     * Optional [rescan] parameter added in 0.8.0.
     * Note: There's no need to import public key,
     * as in ECDSA (unlike RSA) this can be computed from private key.
     * <bitcoinprivkey> [label] [rescan=true]
     *
     * @param string $bitcoinPrivKey
     * @param string $label
     * @param bool $rescan
     * @return mixed
     */
    public function importPrivKey(string $bitcoinPrivKey, string $label = '', bool $rescan = true)
    {
        return $this->_call('importprivkey', [$bitcoinPrivKey, $label, $rescan]);
    }

    /**
     * Imports funds without rescan.
     * Corresponding address or script must previously be included in wallet.
     * Aimed towards pruned wallets.
     * The end-user is responsible to import additional transactions that
     * subsequently spend the imported outputs or rescan after the point in
     * the blockchain the transaction is included.
     * <rawtransaction> <txoutproof>
     *
     * @param string $rawTransaction
     * @param string $txOutProof
     * @return mixed
     */
    public function importPrunedFunds(string $rawTransaction, string $txOutProof)
    {
        return $this->_call('importprunedfunds', [$rawTransaction, $txOutProof]);
    }

    /**
     * Adds a public key (in hex) that can be watched
     * as if it were in your wallet but cannot be used to spend.
     * Requires a new wallet backup.
     * <pubkey> [label] [rescan=true]
     *
     * @param string $pubKey
     * @param string $label
     * @param bool $rescan
     * @return mixed
     */
    public function importPubKey(string $pubKey, string $label = '', bool $rescan = true)
    {
        return $this->_call('importpubkey', [$pubKey, $label, $rescan]);
    }

    /**
     * Imports keys from a wallet dump file (see dumpwallet).
     * Requires a new wallet backup to include imported keys.
     * <filename>
     *
     * @param string $fileName
     * @return mixed
     */
    public function importWallet(string $fileName)
    {
        return $this->_call('importwallet', [$fileName]);
    }

    /**
     * Fills the keypool, requires wallet passphrase to be set.
     *
     * @return mixed
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
     * @return mixed
     */
    public function listAccounts(int $minConf = 1)
    {
        return $this->_call('listaccounts', [$minConf]);
    }

    /**
     * version 0.7
     * Returns all addresses in the wallet and info used for coincontrol.
     *
     * @return mixed
     */
    public function listAddressGroupings()
    {
        return $this->_call('listaddressgroupings');
    }

    /**
     * version 0.8
     * Returns list of temporarily unspendable outputs
     *
     * @return mixed
     */
    public function listLockUnspent()
    {
        return $this->_call('listlockunspent');
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
     * @return mixed
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
     * @return mixed
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
     * @param string $blockHash
     * @param string $targetConfirmations
     * @return mixed
     */
    public function listSinceBlock(string $blockHash = '', string $targetConfirmations = '')
    {
        return $this->_call('listsinceblock', [$blockHash, $targetConfirmations]);
    }

    /**
     * Returns up to [count] most recent transactions skipping
     * the first [from] transactions for account [account].
     * If [account] not provided it'll return recent transactions from all accounts.
     * [account] [count=10] [from=0]
     *
     * @param string $account
     * @param int $count
     * @param int $from
     * @return mixed
     */
    public function listTransactions(string $account, int $count = 10, int $from = 0)
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
     * @return mixed
     */
    public function listUnspent(int $minConf = 1, int $maxConf = 999999)
    {
        return $this->_call('listunspent', [$minConf, $maxConf]);
    }

    /**
     * Returns a list of currently loaded wallets.
     *
     * @return mixed
     */
    public function listWallets()
    {
        return $this->_call('listwallets');
    }

    /**
     * version 0.8
     * Updates list of temporarily unspendable outputs
     * <unlock?> [array-of-objects]
     *
     * @param $unlock
     * @param array $arrayOfObjects
     * @return mixed
     */
    public function lockUnspent($unlock, array $arrayOfObjects = [])
    {
        return $this->_call('lockunspent', [$unlock, $arrayOfObjects]);
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
     * @return mixed
     */
    public function move(string $fromAccount, string $toAccount, float $amount, int $minConf = 1, string $comment = '')
    {
        return $this->_call('move', [$fromAccount, $toAccount, $amount, $minConf, $comment]);
    }

    /**
     * Deletes the specified transaction from the wallet.
     * Meant for use with pruned wallets and as
     * a companion to importprunedfunds.
     * This will affect wallet balances.
     * <txid>
     *
     * @param string $txId
     * @return mixed
     */
    public function removePrunedFunds(string $txId)
    {
        return $this->_call('removeprunedfunds', [$txId]);
    }

    /**
     * Rescan the local blockchain for wallet related transactions.
     * <startheight> <stopheight>
     *
     * @param int $startHeight
     * @param int $stopHeight
     * @return mixed
     */
    public function rescanBlockChain(int $startHeight, int $stopHeight)
    {
        return $this->_call('rescanblockchain', [$startHeight, $stopHeight]);
    }

    /**
     * <amount> is a real and is rounded to 8 decimal places.
     * Will send the given amount to the given address,
     * ensuring the account has a valid balance using [minconf] confirmations.
     * Returns the transaction ID if successful (not in JSON object).
     * <fromaccount> <tobitcoinaddress> <amount> [minconf=1] [comment] [comment-to]
     *
     * @param string $fromAccount
     * @param string $toBitcoinAddress
     * @param float $amount
     * @param int $minConf
     * @param string $comment
     * @param string $commentTo
     * @return mixed
     */
    public function sendFrom(string $fromAccount, string $toBitcoinAddress, float $amount, int $minConf = 1, string $comment = '', string $commentTo = '')
    {
        return $this->_call('sendfrom', [$fromAccount, $toBitcoinAddress, $amount, $minConf, $comment, $commentTo]);
    }

    /**
     * Send multiple times. Amounts are double-precision floating point numbers.
     * <fromaccount> <amounts>
     *
     * @param string $fromAccount
     * @param array $amounts
     * @return mixed
     */
    public function sendMany(string $fromAccount, array $amounts)
    {
        return $this->_call('sendmany', [$fromAccount, $amounts]);
    }

    /**
     * <amount> is a real and is rounded to 8 decimal places.
     * Returns the transaction ID <txid> if successful.
     * <bitcoinaddress> <amount> [comment] [comment-to]
     *
     * @param string $bitcoinAddress
     * @param float $amount
     * @param string $comment
     * @param string $commentTo
     * @return mixed
     */
    public function sendToAddress(string $bitcoinAddress, float $amount, string $comment = '', string $commentTo = '')
    {
        return $this->_call('sendtoaddress', [$bitcoinAddress, $amount, $comment, $commentTo]);
    }

    /**
     * Sets the account associated with the given address.
     * Assigning address that is already assigned to the same
     * account will create a new address associated with that account.
     * <bitcoinaddress> <account>
     *
     * @param string $bitcoinAddress
     * @param string $account
     * @return mixed
     */
    public function setAccount(string $bitcoinAddress, string $account)
    {
        return $this->_call('setaccount', [$bitcoinAddress, $account]);
    }

    /**
     * <amount> is a real and is rounded to the nearest 0.00000001
     * <amount>
     *
     * @param float $amount
     * @return mixed
     */
    public function setTxFee(float $amount)
    {
        return $this->_call('settxfee', [$amount]);
    }

    /**
     * Sign a message with the private key of an address.
     * <bitcoinaddress> <message>
     *
     * @param string $bitcoinAddress
     * @param string $message
     * @return mixed
     */
    public function signMessage(string $bitcoinAddress, string $message)
    {
        return $this->_call('signmessage', [$bitcoinAddress, $message]);
    }

    /**
     * Removes the wallet encryption key from memory, locking the wallet.
     * After calling this method, you will need to call walletpassphrase
     * again before being able to call any methods which require
     * the wallet to be unlocked.
     *
     * @return mixed
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
     * @return mixed
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
     * @return mixed
     */
    public function walletPassPhraseChange(string $oldPassPhrase, string $newPassPhrase)
    {
        return $this->_call('walletpassphrasechange', [$oldPassPhrase, $newPassPhrase]);
    }
}