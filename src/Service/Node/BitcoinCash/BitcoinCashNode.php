<?php

namespace App\Service\Node\BitcoinCash;

use App\Service\Node\BaseNode;
use App\Service\NodeDataManager;

class BitcoinCashNode extends BaseNode
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
     * Returns the hash of the best (tip) block in the longest blockchain.
     * [hex]
     *
     * @param string $hex
     * @return mixed
     */
    public function getBestBlockHash(string $hex = '')
    {
        return $this->__call('getbestblockhash', [$hex]);
    }

    /**
     * If verbose is false, returns a string that is serialized,
     * hex-encoded data for block 'hash'.
     * If verbose is true, returns an Object with information
     * about block <hash>.
     * <hash> [verbose=true]
     *
     * @param string $blockHash
     * @param bool $verbose
     * @return mixed
     */
    public function getBlock(string $blockHash, bool $verbose = true)
    {
        return $this->__call('getblock', [$blockHash, $verbose]);
    }

    /**
     * Returns an object containing various state info
     * regarding blockchain processing.
     *
     * @return mixed
     */
    public function getBlockChainInfo()
    {
        return $this->__call('getblockchaininfo');
    }

    /**
     * Returns the number of blocks in the longest block chain.
     *
     * @return mixed
     */
    public function getBlockCount()
    {
        return $this->__call('getblockcount');
    }

    /**
     * Returns hash of block in best-block-chain at height provided.
     * <index>
     *
     * @param int $height
     * @return mixed
     */
    public function getBlockHash(int $height)
    {
        return $this->__call('getblockhash', [$height]);
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
        return $this->__call('getblockheader', [$hash, $verbose]);
    }

    /**
     * Return information about all known tips in the block tree,
     * including the main chain as well as orphaned branches.
     *
     * @return mixed
     */
    public function getChainTips()
    {
        return $this->__call('getchaintips');
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
        return $this->__call('getchaintxstats', [$nBlocks, $blockHash]);
    }

    /**
     * Returns the proof-of-work difficulty as a multiple of the minimum difficulty.
     *
     * @return mixed
     */
    public function getDifficulty()
    {
        return $this->__call('getdifficulty');
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
        return $this->__call('getmempoolancestors', [$txId, $verbose]);
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
        return $this->__call('getmempooldescendants', [$txId, $verbose]);
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
        return $this->__call('getmempoolentry', [$txId]);
    }

    /**
     * Returns details on the active state of the TX memory pool.
     *
     * @return mixed
     */
    public function getMemPoolInfo()
    {
        return $this->__call('getmempoolinfo');
    }

    /**
     * version 0.7
     * Returns all transaction ids in memory pool
     * [verbose=false]
     *
     * @param bool $verbose
     * @return mixed
     */
    public function getRawMemPool(bool $verbose = false)
    {
        return $this->__call('getrawmempool', [$verbose]);
    }

    /**
     * Returns details about an unspent transaction output.
     * <txid> <n> [includemempool=true]
     *
     * @param string $txId
     * @param int $n
     * @param bool $includeMemPool
     * @return mixed
     */
    public function getTxOut(string $txId, int $n, bool $includeMemPool = true)
    {
        return $this->__call('gettxout', [$txId, $n, $includeMemPool]);
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
        return $this->__call('gettxoutproof', [$txIds, $blockHash]);
    }

    /**
     * Returns statistics about the unspent transaction output set.
     * Note this call may take some time.
     * @return mixed
     */
    public function getTxOutSetInfo()
    {
        return $this->__call('gettxoutsetinfo');
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
        return $this->__call('preciousblock', [$blockHash]);
    }

    /**
     * <height>
     *
     * @param int $height
     * @return mixed
     */
    public function pruneBlockChain(int $height)
    {
        return $this->__call('pruneblockchain', [$height]);
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
        return $this->__call('verifychain', [$checkLevel, $nBlocks]);
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
        return $this->__call('verifytxoutproof', [$proof]);
    }

    /**
     * Returns an object containing information about memory usage.
     *
     * @return mixed
     */
    public function getMemoryInfo()
    {
        return $this->__call('getmemoryinfo');
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
        return $this->__call('help', [$command]);
    }

    /**
     * Stop BitcoinCash server.
     *
     * @return mixed
     */
    public function stop()
    {
        return $this->__call('stop');
    }

    /**
     * Returns the total uptime of the server.
     *
     * @return mixed
     */
    public function upTime()
    {
        return $this->__call('uptime');
    }

    /**
     * Mine up to nblocks blocks immediately (before the RPC call returns)
     * to an address in the wallet.
     * <nblocks> [maxtries=1000000]
     *
     * @param int $nBlocks
     * @param int $maxTries
     * @return mixed|string
     */
    public function generate(int $nBlocks, int $maxTries = 1000000)
    {
        return $this->__call('generate', [$nBlocks, $maxTries]);
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
        return $this->__call('generatetoaddress', [$nBlocks, $address, $maxTries]);
    }

    /**
     * If the request parameters include a 'mode' key, that is used to explicitly select between
     * the default 'template' request or a 'proposal'.
     * It returns data needed to construct a block to work on.
     * For full specification, see BIPs 22, 23, 9, and 145:
     *    https://github.com/bitcoin/bips/blob/master/bip-0022.mediawiki
     *    https://github.com/bitcoin/bips/blob/master/bip-0023.mediawiki
     *    https://github.com/bitcoin/bips/blob/master/bip-0009.mediawiki#getblocktemplate_changes
     *    https://github.com/bitcoin/bips/blob/master/bip-0145.mediawiki
     * [params]
     *
     * @param array $params
     * @return mixed
     */
    public function getBlockTemplate(array $params = [])
    {
        return $this->__call('getblocktemplate', [$params]);
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
        return $this->__call('getmininginfo');
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
        return $this->__call('getnetworkhashps', [$nBlocks, $height]);
    }

    /**
     * Permanently marks a block as invalid, as if it violated a consensus rule.
     * <blockhash>
     *
     * @param string $blockHash
     * @return mixed
     */
    public function invalidateBlock(string $blockHash)
    {
        return $this->__call('invalidateblock', [$blockHash]);
    }

    /**
     * Accepts the transaction into mined blocks at a higher (or lower) priority
     * <txid> <priority_delta> <fee_delta>
     *
     * @param string $txId
     * @param float $priorityDelta
     * @param int $feeDelta
     * @return mixed
     */
    public function prioritiseTransaction(string $txId, float $priorityDelta, int $feeDelta)
    {
        return $this->__call('prioritisetransaction', [$txId, $priorityDelta, $feeDelta]);
    }

    /**
     * Attempts to submit new block to network.
     * <hexdata> [parameters]
     *
     * @param string $hexData
     * @param array $params
     * @return mixed
     */
    public function submitBlock(string $hexData, array $params = [])
    {
        return $this->__call('submitblock', [$hexData, $params]);
    }

    /**
     * Attempts add or remove a node from the addnode list.
     * Or try a connection to a node once.
     * Nodes added using addnode (or -connect) are protected
     * from DoS disconnection and are not required to be
     * full nodes/support SegWit as other outbound peers are
     * (though such peers will not be synced from).
     * <node> <add/remove/onetry>
     *
     * @param string $node
     * @param string $action
     * @return mixed
     */
    public function addNode(string $node, string $action)
    {
        return $this->__call('addnode', [$node, $action]);
    }

    /**
     * Clear all banned IPs.
     *
     * @return mixed
     */
    public function clearBanned()
    {
        return $this->__call('clearbanned');
    }

    /**
     * Immediately disconnects from the specified peer node.
     * Strictly one out of 'address' and 'nodeid' can be provided
     * to identify the node.
     * To disconnect by nodeid, either set 'address' to the empty string,
     * or call using the named 'nodeid' argument only.
     * [address] [nodeid]
     *
     * @param string $address
     * @param int|null $nodeId
     * @return mixed
     */
    public function disconnectNode(string $address = '', int $nodeId = null)
    {
        return $this->__call('disconnectnode', [$address, $nodeId]);
    }

    /**
     * Returns information about the given added node, or all added nodes
     * (note that onetry addnodes are not listed here)
     * [node]
     *
     * @param string $node
     * @return mixed
     */
    public function getAddedNodeInfo(string $node = '')
    {
        return $this->__call('getaddednodeinfo', [$node]);
    }

    /**
     * Returns the number of connections to other nodes.
     *
     * @return mixed
     */
    public function getConnectionCount()
    {
        return $this->__call('getconnectioncount');
    }

    /**
     * Return the excessive block size.
     *
     * @return mixed
     */
    public function getExcessiveBlock()
    {
        return $this->__call('getexcessiveblock');
    }

    /**
     * Returns information about network traffic, including bytes in,
     * bytes out, and current time.
     *
     * @return mixed
     */
    public function getNetTotals()
    {
        return $this->__call('getnettotals');
    }

    /**
     * Returns an object containing various state info regarding P2P networking.
     *
     * @return mixed
     */
    public function getNetworkInfo()
    {
        return $this->__call('getnetworkinfo');
    }

    /**
     * Returns data about each connected network node as a json array of objects.
     *
     * @return mixed
     */
    public function getPeerInfo()
    {
        return $this->__call('getpeerinfo');
    }

    /**
     * List all banned IPs/Subnets.
     *
     * @return mixed
     */
    public function listBanned()
    {
        return $this->__call('listbanned');
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
        return $this->__call('ping');
    }

    /**
     * Attempts to add or remove an IP/Subnet from the banned list.
     * <subnet> <command> [bantime] [absolute=false]
     *
     * @param string $subNet
     * @param string $command
     * @param int $banTime
     * @param bool $absolute
     * @return mixed
     */
    public function setBan(string $subNet, string $command, int $banTime = 0, bool $absolute = false)
    {
        return $this->__call('setban', [$subNet, $command, $banTime, $absolute]);
    }

    /**
     * Set the excessive block size. Excessive blocks will not be used
     * in the active chain or relayed. This  discourages the propagation of
     * blocks that you consider excessively large.
     *
     * @return mixed
     */
    public function setExcessiveBlock()
    {
        return $this->__call('setexcessiveblock');
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
        return $this->__call('setnetworkactive', [$status]);
    }

    /**
     * Create a transaction spending the given inputs and creating new outputs.
     * Outputs can be addresses or data.
     * Returns hex-encoded raw transaction.
     * Note that the transaction's inputs are not signed, and
     * it is not stored in the wallet or transmitted to the network.
     * <inputs> <outputs> [locktime=0]
     *
     * @param array $inputs
     * @param array $outputs
     * @param int $lockTime
     * @return mixed
     */
    public function createRawTransaction(array $inputs, array $outputs, int $lockTime = 0)
    {
        return $this->__call('createrawtransaction', [$inputs, $outputs, $lockTime]);
    }

    /**
     * Return a JSON object representing the serialized, hex-encoded transaction.
     * <hexstring>
     *
     * @param string $hex
     * @return mixed
     */
    public function decodeRawTransaction(string $hex)
    {
        return $this->__call('decoderawtransaction', [$hex]);
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
        return $this->__call('decodescript', [$hex]);
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
     * <hexstring> [options]
     *
     * @param string $hex
     * @param array $options
     * @return mixed
     */
    public function fundRawTransaction(string $hex, array $options = [])
    {
        return $this->__call('fundrawtransaction', [$hex, $options]);
    }

    /**
     * version 0.7
     * Returns raw transaction representation for given transaction id.
     * <txid> [verbose=false]
     *
     * @param string $txId
     * @param bool $verbose
     * @return mixed
     */
    public function getRawTransaction(string $txId, bool $verbose = false)
    {
        return $this->__call('getrawtransaction', [$txId, $verbose]);
    }

    /**
     * Submits raw transaction (serialized, hex-encoded) to local node and network.
     * Also see createrawtransaction and signrawtransaction calls.
     * <hexstring> [allowhighfees=false]
     *
     * @param string $hex
     * @param bool $allowHighFees
     * @return mixed
     */
    public function sendRawTransaction(string $hex, bool $allowHighFees)
    {
        return $this->__call('sendrawtransaction', [$hex, $allowHighFees]);
    }

    /**
     * Sign inputs for raw transaction (serialized, hex-encoded).
     * The second optional argument (may be null) is an array of previous transaction outputs that
     * this transaction depends on but may not yet be in the block chain.
     * The third optional argument (may be null) is an array of base58-encoded private
     * keys that, if given, will be the only keys used to sign the transaction.
     * <hexstring> [prevtxs] [privkeys] [sighashtype=ALL]
     *
     * @param string $hexString
     * @param array $prevTxs
     * @param array $privKeys
     * @param string $sigHashType
     * @return mixed
     */
    public function signRawTransaction(string $hexString, array $prevTxs = [], array $privKeys = [], string $sigHashType = 'ALL')
    {
        return $this->__call('signrawtransaction', [$hexString, $prevTxs, $privKeys, $sigHashType]);
    }

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
        return $this->__call('createmultisig', [$nRequired, $keys]);
    }

    /**
     * Estimates the approximate fee per kilobyte needed for a transaction to begin
     * confirmation within nblocks blocks.
     * <nblocks>
     *
     * @param int $nBlocks
     * @return mixed
     */
    public function estimateFee(int $nBlocks)
    {
        return $this->__call('estimatefee', [$nBlocks]);
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
        return $this->__call('signmessagewithprivkey', [$privKey, $message]);
    }

    /**
     * Return information about the given address.
     * <address>
     *
     * @param string $address
     * @return mixed
     */
    public function validateAddress(string $address)
    {
        return $this->__call('validateaddress', [$address]);
    }

    /**
     * Verify a signed message.
     * <address> <signature> <message>
     *
     * @param string $address
     * @param string $signature
     * @param string $message
     * @return mixed
     */
    public function verifyMessage(string $address, string $signature, string $message)
    {
        return $this->__call('verifymessage', [$address, $signature, $message]);
    }

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
        return $this->__call('abandontransaction', [$txId]);
    }

    /**
     * Stops current wallet rescan triggered by an RPC call,
     * e.g. by an importprivkey call.
     *
     * @return mixed
     */
    public function abortRescan()
    {
        return $this->__call('abortrescan');
    }

    /**
     * Add a nrequired-to-sign multisignature address to the wallet.
     * Each key is a BitcoinCash address or hex-encoded public key.
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
        return $this->__call('addmultisigaddress', [$nRequired, $keys, $account]);
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
        return $this->__call('backupwallet', [$destination]);
    }

    /**
     * Reveals the private key corresponding to 'address'.
     * Then the importprivkey can be used with this output
     * <address>
     *
     * @param string $address
     * @return mixed
     */
    public function dumpPrivKey(string $address)
    {
        return $this->__call('dumpprivkey', [$address]);
    }

    /**
     * Dumps all wallet keys in a human-readable format to a server-side file.
     * This does not allow overwriting existing files.
     * <dumpwallet>
     *
     * @param string $filename
     * @return mixed
     */
    public function dumpWallet(string $filename)
    {
        return $this->__call('dumpwallet', [$filename]);
    }

    /**
     * Encrypts the wallet with 'passphrase'. This is for first time encryption.
     * After this, any calls that interact with private keys such as sending or signing
     * will require the passphrase to be set prior the making these calls.
     * Use the walletpassphrase call for this, and then walletlock call.
     * If the wallet is already encrypted, use the walletpassphrasechange call.
     * Note that this will shutdown the server.
     * <passphrase>
     *
     * @param string $passPhrase
     * @return mixed
     */
    public function encryptWallet(string $passPhrase)
    {
        return $this->__call('encryptwallet', [$passPhrase]);
    }

    /**
     * Returns the account associated with the given address.
     * <address>
     *
     * @param string $address
     * @return mixed
     */
    public function getAccount(string $address)
    {
        return $this->__call('getaccount', [$address]);
    }

    /**
     * DEPRECATED. Returns the current Bitcoin address for receiving payments to this account.
     * <account>
     *
     * @param string $account
     * @return mixed
     */
    public function getAccountAddress(string $account)
    {
        return $this->__call('getaccountaddress', [$account]);
    }

    /**
     * DEPRECATED. Returns the list of addresses for the given account.
     * <account>
     *
     * @param string $account
     * @return mixed
     */
    public function getAddressesByAccount(string $account)
    {
        return $this->__call('getaddressesbyaccount', [$account]);
    }

    /**
     * If [account] is not specified, returns the server's total available balance.
     * If [account] is specified, returns the balance in the account.
     * [account] [minconf=1] [include_watchonly=false]
     *
     * @param string $account
     * @param int $minConf
     * @param bool $includeWatchOnly
     * @return mixed
     */
    public function getBalance(string $account = '', int $minConf = 1, bool $includeWatchOnly = false)
    {
        return $this->__call('getbalance', [$account, $minConf, $includeWatchOnly]);
    }

    /**
     * Returns a new Bitcoin address for receiving payments.
     * If 'account' is specified (DEPRECATED), it is added to the address book
     * so payments received with the address will be credited to 'account'.
     * [account]
     *
     * @param string $account
     * @return mixed
     */
    public function getNewAddress(string $account = '')
    {
        return $this->__call('getnewaddress', [$account]);
    }

    /**
     * Returns a new Bitcoin address, for receiving change.
     * This is for use with raw transactions, NOT normal use.
     *
     * @return mixed
     */
    public function getRawChangeAddress()
    {
        return $this->__call('getrawchangeaddress');
    }

    /**
     * DEPRECATED. Returns the total amount received by addresses with <account>
     * in transactions with at least [minconf] confirmations.
     * [account] [minconf=1]
     *
     * @param string $account
     * @param int $minConf
     * @return mixed
     */
    public function getReceivedByAccount(string $account, int $minConf = 1)
    {
        return $this->__call('getreceivedbyaccount', [$account, $minConf]);
    }

    /**
     * Returns the total amount received by the given address in transactions with at least minconf confirmations.
     * <address> [minconf=1]
     *
     * @param string $address
     * @param int $minConf
     * @return mixed
     */
    public function getReceivedByAddress(string $address, int $minConf = 1)
    {
        return $this->__call('getreceivedbyaddress', [$address, $minConf]);
    }

    /**
     * Get detailed information about in-wallet transaction <txid>
     * <txid> [include_watchonly=false]
     *
     * @param string $txId
     * @param bool $includeWatchOnly
     * @return mixed
     */
    public function getTransaction(string $txId, bool $includeWatchOnly = false)
    {
        return $this->__call('gettransaction', [$txId, $includeWatchOnly]);
    }

    /**
     * Returns the server's total unconfirmed balance
     *
     * @return mixed
     */
    public function getUnconfirmedBalance()
    {
        return $this->__call('getunconfirmedbalance');
    }

    /**
     * Returns an object containing various state info.
     *
     * @return mixed
     */
    public function getWalletInfo()
    {
        return $this->__call('getwalletinfo');
    }

    /**
     * Adds a script (in hex) or address that can be watched as if it were in your wallet but cannot be used to spend.
     * <script> [label] [rescan=true]
     *
     * @param string $script
     * @param string $label
     * @param bool $rescan
     * @return mixed
     */
    public function importAddress(string $script, string $label = '', bool $rescan = true)
    {
        return $this->__call('importaddress', [$script, $label, $rescan]);
    }

    /**
     * Import addresses/scripts (with private or public keys, redeem script (P2SH)),
     * rescanning all addresses in one-shot-only (rescan can be disabled via options).
     * <requests> [options]
     *
     * @param array $requests
     * @param array $options
     * @return mixed
     */
    public function importMulti(array $requests, array $options)
    {
        return $this->__call('importmulti', [$requests, $options]);
    }

    /**
     * Adds a private key (as returned by dumpprivkey) to your wallet.
     * <bitcoinprivkey> [label] [rescan=true]
     *
     * @param string $privKey
     * @param string $label
     * @param bool $rescan
     * @return mixed
     */
    public function importPrivKey(string $privKey, string $label = '', bool $rescan = true)
    {
        return $this->__call('importprivkey', [$privKey, $label, $rescan]);
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
        return $this->__call('importprunedfunds', [$rawTransaction, $txOutProof]);
    }

    /**
     * Adds a public key (in hex) that can be watched
     * as if it were in your wallet but cannot be used to spend.
     * <pubkey> [label] [rescan=true]
     *
     * @param string $pubKey
     * @param string $label
     * @param bool $rescan
     * @return mixed
     */
    public function importPubKey(string $pubKey, string $label = '', bool $rescan = true)
    {
        return $this->__call('importpubkey', [$pubKey, $label, $rescan]);
    }

    /**
     * Imports keys from a wallet dump file (see dumpwallet).
     * <filename>
     *
     * @param string $fileName
     * @return mixed
     */
    public function importWallet(string $fileName)
    {
        return $this->__call('importwallet', [$fileName]);
    }

    /**
     * Fills the keypool.
     * [newsize]
     *
     * @param int $newSize
     * @return mixed
     */
    public function keyPoolRefill(int $newSize = 100)
    {
        return $this->__call('keypoolrefill', [$newSize]);
    }

    /**
     * DEPRECATED. Returns Object that has account names as keys, account balances as values.
     * [minconf=1] [include_watchonly=false]
     *
     * @param int $minConf
     * @param bool $includeWatchOnly
     * @return mixed
     */
    public function listAccounts(int $minConf = 1, bool $includeWatchOnly = false)
    {
        return $this->__call('listaccounts', [$minConf, $includeWatchOnly]);
    }

    /**
     * Lists groups of addresses which have had their common ownership
     * made public by common use as inputs or as the resulting change
     * in past transactions
     *
     * @return mixed
     */
    public function listAddressGroupings()
    {
        return $this->__call('listaddressgroupings');
    }

    /**
     * Returns list of temporarily unspendable outputs.
     * See the lockunspent call to lock and unlock transactions for spending.
     *
     * @return mixed
     */
    public function listLockUnspent()
    {
        return $this->__call('listlockunspent');
    }

    /**
     * DEPRECATED. List balances by account.
     * [minconf=1] [include_empty=false] [include_watchonly=false]
     *
     * @param int $minConf
     * @param bool $includeEmpty
     * @param bool $includeWatchOnly
     * @return mixed
     */
    public function listReceivedByAccount(int $minConf = 1, bool $includeEmpty = false, bool $includeWatchOnly = false)
    {
        return $this->__call('listreceivedbyaccount', [$minConf, $includeEmpty, $includeWatchOnly]);
    }

    /**
     * List balances by receiving address.
     * [minconf=1] [include_empty=false] [include_watchonly=false]
     *
     * @param int $minConf
     * @param bool $includeEmpty
     * @param bool $includeWatchOnly
     * @return mixed
     */
    public function listReceivedByAddress(int $minConf = 1, bool $includeEmpty = false, bool $includeWatchOnly = false)
    {
        return $this->__call('listreceivedbyaddress', [$minConf, $includeEmpty, $includeWatchOnly]);
    }

    /**
     * Get all transactions in blocks since block [blockhash], or all transactions if omitted
     * [blockhash] [target_confirmations] [include_watchonly]
     *
     * @param string $blockHash
     * @param int $targetConfirmations
     * @param bool $includeWatchOnly
     * @return mixed
     */
    public function listSinceBlock(string $blockHash = '', int $targetConfirmations = 1, bool $includeWatchOnly = false)
    {
        return $this->__call('listsinceblock', [$blockHash, $targetConfirmations, $includeWatchOnly]);
    }

    /**
     * Returns up to 'count' most recent transactions skipping the first 'from'
     * transactions for account 'account'.
     * [account] [count=10] [from=0] [include_watchonly=false]
     *
     * @param string $account
     * @param int $count
     * @param int $from
     * @param bool $includeWatchOnly
     * @return mixed
     */
    public function listTransactions(string $account, int $count = 10, int $from = 0, bool $includeWatchOnly = false)
    {
        return $this->__call('listtransactions', [$account, $count, $from, $includeWatchOnly]);
    }

    /**
     * Returns array of unspent transaction outputs
     * with between minconf and maxconf (inclusive) confirmations.
     * Optionally filter to only include txouts paid to specified addresses.
     * [minconf=1] [maxconf=999999] [addresses]
     *
     * @param int $minConf
     * @param int $maxConf
     * @param array $addresses
     * @return mixed
     */
    public function listUnspent(int $minConf = 1, int $maxConf = 999999, array $addresses = [])
    {
        return $this->__call('listunspent', [$minConf, $maxConf, $addresses]);
    }

    /**
     * Returns a list of currently loaded wallets.
     *
     * @return mixed
     */
    public function listWallets()
    {
        return $this->__call('listwallets');
    }

    /**
     * Updates list of temporarily unspendable outputs.
     * Temporarily lock (unlock=false) or unlock (unlock=true) specified transaction outputs.
     * If no transaction outputs are specified when unlocking then all current locked transaction outputs are unlocked.
     * A locked transaction output will not be chosen by automatic coin selection, when spending bitcoins.
     * Locks are stored in memory only. Nodes start with zero locked outputs, and the locked output list
     * is always cleared (by virtue of process exit) when a node stops or fails.
     * Also see the listunspent call
     * <unlock> [transactions]
     *
     * @param bool $unlock
     * @param array $transactions
     * @return mixed
     */
    public function lockUnspent(bool $unlock, array $transactions = [])
    {
        return $this->__call('lockunspent', [$unlock, $transactions]);
    }

    /**
     * DEPRECATED. Move from one account in your wallet to another
     * <fromaccount> <toaccount> <amount> [dummy] [comment]
     *
     * @param string $fromAccount
     * @param string $toAccount
     * @param float $amount
     * @param int $dummy
     * @param string $comment
     * @return mixed
     */
    public function move(string $fromAccount, string $toAccount, float $amount, int $dummy = null, string $comment = '')
    {
        return $this->__call('move', [$fromAccount, $toAccount, $amount, $dummy, $comment]);
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
        return $this->__call('removeprunedfunds', [$txId]);
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
        return $this->__call('rescanblockchain', [$startHeight, $stopHeight]);
    }

    /**
     * DEPRECATED (use sendtoaddress). Sent an amount from an account to a bitcoin address.
     * <fromaccount> <toaddress> <amount> [minconf=1] [comment] [comment-to]
     *
     * @param string $fromAccount
     * @param string $toAddress
     * @param float $amount
     * @param int $minConf
     * @param string $comment
     * @param string $commentTo
     * @return mixed
     */
    public function sendFrom(string $fromAccount, string $toAddress, float $amount, int $minConf = 1, string $comment = '', string $commentTo = '')
    {
        return $this->__call('sendfrom', [$fromAccount, $toAddress, $amount, $minConf, $comment, $commentTo]);
    }

    /**
     * Send multiple times. Amounts are double-precision floating point numbers.
     * <fromaccount> <amounts> [minconf=1] [comment] [subtractfeefrom]
     *
     * @param string $fromAccount
     * @param array $amounts
     * @param int $minConf
     * @param string $comment
     * @param array $subTractFeeFrom
     * @return mixed
     */
    public function sendMany(string $fromAccount, array $amounts, int $minConf = 1, string $comment = '', array $subTractFeeFrom = [])
    {
        return $this->__call('sendmany', [$fromAccount, $amounts, $minConf, $comment, $subTractFeeFrom]);
    }

    /**
     * Send an amount to a given address.
     * <address> <amount> [comment] [comment_to]
     *
     * @param string $address
     * @param float $amount
     * @param string $comment
     * @param string $commentTo
     * @return mixed
     */
    public function sendToAddress(string $address, float $amount, string $comment = '', string $commentTo = '')
    {
        return $this->__call('sendtoaddress', [$address, $amount, $comment, $commentTo]);
    }

    /**
     * DEPRECATED. Sets the account associated with the given address.
     * <address> <account>
     *
     * @param string $address
     * @param string $account
     * @return mixed
     */
    public function setAccount(string $address, string $account)
    {
        return $this->__call('setaccount', [$address, $account]);
    }

    /**
     * Set the transaction fee per kB. Overwrites the paytxfee parameter.
     * <amount>
     *
     * @param float $amount
     * @return mixed
     */
    public function setTxFee(float $amount)
    {
        return $this->__call('settxfee', [$amount]);
    }

    /**
     * Sign a message with the private key of an address.
     * <address> <message>
     *
     * @param string $address
     * @param string $message
     * @return mixed
     */
    public function signMessage(string $address, string $message)
    {
        return $this->__call('signmessage', [$address, $message]);
    }
}