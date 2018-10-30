<?php

namespace App\Service\Node\ZCash;

use App\Service\Node\BaseNode;
use App\Service\NodeDataManager;

class ZCashNode extends BaseNode
{
    private $rootWallet;
    private $dataManager;

    public function __construct(NodeDataManager $dataManager = null, ?string $rootWallet = null, $settings = null)
    {
        parent::__construct(getenv('ZCASH_HOST'));
        $this->dataManager = $dataManager;
        $this->rootWallet = $rootWallet;
    }

    /**
     * Returns the hash of the best (tip) block in the longest block chain.
     *
     * @return mixed
     */
    public function getBestBlockHash()
    {
        return $this->__call('getbestblockhash');
    }

    /**
     * If verbosity is 0, returns a string that is serialized,
     * hex-encoded data for the block.
     * If verbosity is 1, returns an Object with information about the block.
     * If verbosity is 2, returns an Object with information about the block
     * and information about each transaction.
     * <hash|height> [verbosity=1]
     *
     * @param string $hash
     * @param int $verbosity
     * @return mixed
     */
    public function getBlock(string $hash, int $verbosity = 1)
    {
        return $this->__call('getblock', [$hash, $verbosity]);
    }

    /**
     * Returns an object containing various state info regarding block chain processing.
     * Note that when the chain tip is at the last block before a network upgrade activation,
     * consensus.chaintip != consensus.nextblock.
     *
     * @return mixed|string
     */
    public function getBlockChainInfo()
    {
        return $this->__call('getblockchaininfo');
    }

    /**
     * Returns the number of blocks in the best valid block chain.
     *
     * @return mixed
     */
    public function getBlockCount()
    {
        return $this->__call('getblockcount');
    }

    /**
     * Returns hash of block in best-block-chain at index provided.
     * <index>
     *
     * @param int $index
     * @return mixed
     */
    public function getBlockHash(int $index)
    {
        return $this->__call('getblockhash', [$index]);
    }

    /**
     * If verbose is false, returns a string that is serialized, hex-encoded data for blockheader 'hash'.
     * If verbose is true, returns an Object with information about blockheader <hash>.
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
     * Returns the proof-of-work difficulty as a multiple of the minimum difficulty.
     *
     * @return mixed
     */
    public function getDifficulty()
    {
        return $this->__call('getdifficulty');
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
     * Returns all transaction ids in memory pool as a json array of string transaction ids.
     * [verbose=false]
     *
     * @param $verbose
     * @return mixed
     */
    public function getRawMemPool(bool $verbose = false)
    {
        return $this->__call('getrawmempool', [$verbose]);
    }

    /**
     * Returns details about an unspent transaction output.
     * <txid> <n> [includemempool=false]
     *
     * @param string $txId
     * @param int $n
     * @param bool $includeMemPool
     * @return mixed
     */
    public function getTxOut(string $txId, int $n, bool $includeMemPool = false)
    {
        return $this->__call('gettxout', [$txId, $n, $includeMemPool]);
    }

    /**
     * Returns a hex-encoded proof that "txid" was included in a block.
     * NOTE: By default this function only works sometimes. This is when there is an
     * unspent output in the utxo for this transaction. To make it always work,
     * you need to maintain a transaction index, using the -txindex command line option or
     * specify the block in which the transaction is included in manually (by blockhash).
     * <txids> [block hash]
     *
     * @param array $txIds
     * @param string $blockHash
     * @return mixed
     */
    public function getTxOutProof(array $txIds, string $blockHash = '')
    {
        return $this->__call('gettxoutproof', [$txIds, $blockHash]);
    }

    /**
     * Returns statistics about the unspent transaction output set.
     * Note this call may take some time.
     *
     * @return mixed
     */
    public function getTxOutSetInfo()
    {
        return $this->__call('gettxoutsetinfo', []);
    }

    /**
     * Verifies blockchain database.
     * [checklevel=3] [numblocks=288]
     *
     * @param int $checkLevel
     * @param int $numBlocks
     * @return mixed
     */
    public function verifyChain(int $checkLevel, int $numBlocks)
    {
        return $this->__call('verifychain', [$checkLevel, $numBlocks]);
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
     * Returns an object containing various state info.
     *
     * @return mixed
     */
    public function getInfo()
    {
        return $this->__call('getinfo');
    }

    /**
     * List all commands, or get help for a specified command.
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
     * Stop Zcash server.
     *
     * @return mixed
     */
    public function stop()
    {
        return $this->__call('stop');
    }

    /**
     * Generate a payment disclosure for a given joinsplit output.
     *
     * EXPERIMENTAL FEATURE
     *
     * WARNING: z_getpaymentdisclosure is disabled.
     * To enable it, restart zcashd with the -experimentalfeatures and
     * -paymentdisclosure commandline options, or add these two lines
     * to the zcash.conf file:
     *    experimentalfeatures=1
     *    paymentdisclosure=1
     * <txid> <js_index> <output_index> [message]
     *
     * @param string $txId
     * @param string $jsIndex
     * @param string $outputIndex
     * @param string $message
     * @return mixed
     */
    public function z_getPaymentDisclosure(string $txId, string $jsIndex, string $outputIndex, string $message = '')
    {
        return $this->__call('z_getpaymentdisclosure', [$txId, $jsIndex, $outputIndex, $message]);
    }

    /**
     * Validates a payment disclosure.
     *
     * EXPERIMENTAL FEATURE
     *
     * WARNING: z_validatepaymentdisclosure is disabled.
     * To enable it, restart zcashd with the -experimentalfeatures and
     * -paymentdisclosure commandline options, or add these two lines
     * to the zcash.conf file:
     *    experimentalfeatures=1
     *    paymentdisclosure=1
     * <paymentdisclosure>
     *
     * @param string $paymentDisclosure
     * @return mixed|string
     */
    public function z_validatePaymentDisclosure(string $paymentDisclosure)
    {
        return $this->__call('z_validatepaymentdisclosure', [$paymentDisclosure]);
    }

    /**
     * Mine blocks immediately (before the RPC call returns)
     * Note: this function can only be used on the regtest network
     * <numblocks>
     *
     * @param int $numBlocks
     * @return mixed
     */
    public function generate(int $numBlocks)
    {
        return $this->__call('generate', [$numBlocks]);
    }

    /**
     * Return if the server is set to generate coins or not. The default is false.
     * It is set with the command line argument -gen (or zcash.conf setting gen)
     * It can also be set with the setgenerate call.
     *
     * @return mixed
     */
    public function getGenerate()
    {
        return $this->__call('getgenerate');
    }

    /**
     * Set 'generate' true or false to turn generation on or off.
     * Generation is limited to 'genproclimit' processors, -1 is unlimited.
     * See the getgenerate call for the current setting.
     * <generate> [genproclimit=-1]
     *
     * @param bool $generate
     * @param int $genProcLimit
     * @return mixed
     */
    public function setGenerate(bool $generate, int $genProcLimit = -1)
    {
        return $this->__call('setgenerate', [$generate, $genProcLimit]);
    }

    /**
     * Returns block subsidy reward, taking into account the mining
     * slow start and the founders reward, of block at index provided.
     * [height]
     *
     * @param int $height
     * @return mixed
     */
    public function getBlockSubsidy(int $height)
    {
        return $this->__call('getblocksubsidy', [$height]);
    }

    /**
     * If the request parameters include a 'mode' key, that is used to explicitly select between the default 'template' request or a 'proposal'.
     * It returns data needed to construct a block to work on.
     * See https://en.bitcoin.it/wiki/BIP_0022 for full specification.
     * [jsonrequestobject]
     *
     * @param array $jsonRequestObject
     * @return mixed
     */
    public function getBlockTemplate(array $jsonRequestObject)
    {
        return $this->__call('getblocktemplate', [$jsonRequestObject]);
    }

    /**
     * Returns the average local solutions per second since this node was started.
     * This is the same information shown on the metrics screen (if enabled).
     *
     * @return mixed
     */
    public function getLocalSolPs()
    {
        return $this->__call('getlocalsolps');
    }

    /**
     * Returns a json object containing mining-related information.
     *
     * @return mixed
     */
    public function getMiningInfo()
    {
        return $this->__call('getmininginfo');
    }

    /**
     * Returns the estimated network solutions per second based on the last n blocks.
     * Pass in [blocks] to override # of blocks, -1 specifies over difficulty averaging window.
     * Pass in [height] to estimate the network speed at the time when a certain block was found.
     * [blocks=120] [height=-1]
     *
     * @param int $blocks
     * @param int $height
     * @return mixed|string
     */
    public function getNetworkHashPs(int $blocks = 120, int $height = -1)
    {
        return $this->__call('getnetworkhashps', [$blocks, $height]);
    }

    /**
     * Returns the estimated network solutions per second based on the last n blocks.
     * Pass in [blocks] to override # of blocks, -1 specifies over difficulty averaging window.
     * Pass in [height] to estimate the network speed at the time when a certain block was found.
     * [blocks=120] [height=-1]
     *
     * @param int $blocks
     * @param int $height
     * @return mixed
     */
    public function getNetworkSolPs(int $blocks = 120, int $height = -1)
    {
        return $this->__call('getnetworksolps', [$blocks, $height]);
    }

    /**
     * Accepts the transaction into mined blocks at a higher (or lower) priority
     * <txid> <priority delta> <fee delta>
     *
     * @param string $txId
     * @param int $priorityDelta
     * @param int $feeDelta
     * @return mixed
     */
    public function prioritiseTransaction(string $txId, int $priorityDelta, int $feeDelta)
    {
        return $this->__call('prioritisetransaction', [$txId, $priorityDelta, $feeDelta]);
    }

    /**
     * Attempts to submit new block to network.
     * The 'jsonparametersobject' parameter is currently ignored.
     * See https://en.bitcoin.it/wiki/BIP_0022 for full specification.
     * <hexdata> [jsonparametersobject]
     *
     * @param string $hexData
     * @param array $jsonParametersObject
     * @return mixed
     */
    public function submitBlock(string $hexData, array $jsonParametersObject)
    {
        return $this->__call('submitblock', [$hexData, $jsonParametersObject]);
    }

    /**
     * Attempts add or remove a node from the addnode list.
     * Or try a connection to a node once.
     * <node> <command>
     *
     * @param string $node
     * @param string $command
     * @return mixed
     */
    public function addNode(string $node, string $command)
    {
        return $this->__call('addnode', [$node, $command]);
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
     * Immediately disconnects from the specified node.
     * <node>
     *
     * @param string $node
     * @return mixed
     */
    public function disconnectNode(string $node)
    {
        return $this->__call('disconnectnode', [$node]);
    }

    /**
     * Returns information about the given added node, or all added nodes
     * (note that onetry addnodes are not listed here)
     * If dns is false, only a list of added nodes will be provided,
     * otherwise connected information will also be available.
     * <dns> [node]
     *
     * @param bool $dns
     * @param string $node
     * @return mixed
     */
    public function getAddedNodeInfo(bool $dns, string $node = '')
    {
        return $this->__call('getaddednodeinfo', [$dns, $node]);
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
     * Returns an object containing current version and deprecation block height.
     * Applicable only on mainnet.
     *
     * @return mixed
     */
    public function getDeprecationInfo()
    {
        return $this->__call('getdeprecationinfo');
    }

    /**
     * Returns information about network traffic, including bytes in, bytes out,
     * and current time.
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
        return $this->__call('getnetworkinfo', []);
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
     * Results provided in getpeerinfo, pingtime and pingwait fields are decimal seconds.
     * Ping command is handled in queue with all other commands, so it measures processing backlog, not just network ping.
     *
     * @return mixed
     */
    public function ping()
    {
        return $this->__call('ping', []);
    }

    /**
     * Attempts add or remove a IP/Subnet from the banned list.
     * <ip> <command> [bantime] [absolute]
     *
     * @param string $ip
     * @param string $command
     * @param int $banTime
     * @param bool $absolute
     * @return mixed
     */
    public function setBan(string $ip, string $command, int $banTime, bool $absolute)
    {
        return $this->__call('setban', [$ip, $command, $banTime, $absolute]);
    }

    /**
     * Create a transaction spending the given inputs and sending to the given addresses.
     * Returns hex-encoded raw transaction.
     * Note that the transaction's inputs are not signed, and
     * it is not stored in the wallet or transmitted to the network.
     * <transactions> <addresses> [locktime=0] [expiryheight=20]
     *
     * @param array $transactions
     * @param array $addresses
     * @param int $lockTime
     * @param int $expiryHeight
     * @return mixed
     */
    public function createRawTransaction(array $transactions, array $addresses, int $lockTime, int $expiryHeight)
    {
        return $this->__call('createrawtransaction', [$transactions, $addresses, $lockTime, $expiryHeight]);
    }

    /**
     * Return a JSON object representing the serialized, hex-encoded transaction.
     * <hex>
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
     * <hex>
     *
     * @param string $hex
     * @return mixed
     */
    public function decodeScript(string $hex)
    {
        return $this->__call('decodescript', [$hex]);
    }

    /**
     * Add inputs to a transaction until it has enough in value to meet its out value.
     * This will not modify existing inputs, and will add one change output to the outputs.
     * Note that inputs which were signed may need to be resigned after completion since in/outputs have been added.
     * The inputs added will not be signed, use signrawtransaction for that.
     * <hexstring>
     *
     * @param string $hexString
     * @return mixed
     */
    public function fundRawTransaction(string $hexString)
    {
        return $this->__call('fundrawtransaction', [$hexString]);
    }

    /**
     * NOTE: By default this function only works sometimes. This is when the tx is in the mempool
     * or there is an unspent output in the utxo for this transaction. To make it always work,
     * you need to maintain a transaction index, using the -txindex command line option.
     *
     * Return the raw transaction data.
     *
     * If verbose=0, returns a string that is serialized, hex-encoded data for 'txid'.
     * If verbose is non-zero, returns an Object with information about 'txid'.
     * <txid> [verbose=0]
     *
     * @param string $txId
     * @param int $verbose
     * @return mixed
     */
    public function getRawTransaction(string $txId, int $verbose = 0)
    {
        return $this->__call('getrawtransaction', [$txId, $verbose]);
    }

    /**
     * Submits raw transaction (serialized, hex-encoded) to local node and network.
     *
     * Also see createrawtransaction and signrawtransaction calls.
     * <hexstring> [allowhighfees=false]
     *
     * @param string $hexString
     * @param bool $allowHeightFees
     * @return mixed
     */
    public function sendRawTransaction(string $hexString, bool $allowHeightFees)
    {
        return $this->__call('sendrawtransaction', [$hexString, $allowHeightFees]);
    }

    /**
     * Sign inputs for raw transaction (serialized, hex-encoded).
     * The second optional argument (may be null) is an array of previous transaction outputs that
     * this transaction depends on but may not yet be in the block chain.
     * The third optional argument (may be null) is an array of base58-encoded private
     * keys that, if given, will be the only keys used to sign the transaction.
     * <hexstring> [prevtxs] [privatekeys] [sighashtype=ALL]
     *
     * @param string $hexString
     * @param array $prevTxs
     * @param array $privateKeys
     * @param string $sigHashType The signature hash type. Must be one of
     * "ALL"
     * "NONE"
     * "SINGLE"
     * "ALL|ANYONECANPAY"
     * "NONE|ANYONECANPAY"
     * "SINGLE|ANYONECANPAY"
     * @return mixed|string
     */
    public function signRawTransaction(string $hexString, array $prevTxs = [], array $privateKeys = [], string $sigHashType = 'ALL')
    {
        return $this->__call('signrawtransaction', [$hexString, $prevTxs, $privateKeys, $sigHashType]);
    }

    /**
     * Creates a multi-signature address with n signature of m keys required.
     * It returns a json object with the address and redeemScript.
     * <nrequired> <keys>
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
     * Estimates the approximate fee per kilobyte
     * needed for a transaction to begin confirmation
     * within nblocks blocks.
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
     * Estimates the approximate priority
     * a zero-fee transaction needs to begin confirmation
     * within nblocks blocks.
     * <nblocks>
     *
     * @param int $nBlocks
     * @return mixed
     */
    public function estimatePriority(int $nBlocks)
    {
        return $this->__call('estimatepriority', [$nBlocks]);
    }

    /**
     * Return information about the given Zcash address.
     * <zcashaddress>
     *
     * @param string $ZCashAddress
     * @return mixed
     */
    public function validateAddress(string $ZCashAddress)
    {
        return $this->__call('validateaddress', [$ZCashAddress]);
    }

    /**
     * Verify a signed message
     * <zcashaddress> <signature> <message>
     *
     * @param string $ZCashAddress
     * @param string $signature
     * @param string $message
     * @return mixed
     */
    public function verifyMessage(string $ZCashAddress, string $signature, string $message)
    {
        return $this->__call('verifymessage', [$ZCashAddress, $signature, $message]);
    }

    /**
     * Return information about the given z address.
     * <zaddr>
     *
     * @param string $zAddr
     * @return mixed
     */
    public function z_validateAddress(string $zAddr)
    {
        return $this->__call('z_validateaddress', [$zAddr]);
    }

    /**
     * Add a nrequired-to-sign multisignature address to the wallet.
     * Each key is a Zcash address or hex-encoded public key.
     * If 'account' is specified (DEPRECATED), assign address to that account.
     * <nrequired> <keysobject> [account]
     *
     * @param int $nRequired
     * @param array $keysObject
     * @param string $account
     * @return mixed
     */
    public function addMultiSigAddress(int $nRequired, array $keysObject, string $account)
    {
        return $this->__call('addmultisigaddress', [$nRequired, $keysObject, $account]);
    }

    /**
     * Safely copies wallet.dat to destination filename
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
     * Reveals the private key corresponding to 't-addr'.
     * Then the importprivkey can be used with this output
     * <t-addr>
     *
     * @param string $tAddr
     * @return mixed
     */
    public function dumpPrivKey(string $tAddr)
    {
        return $this->__call('dumpprivkey', [$tAddr]);
    }

    /**
     * Dumps taddr wallet keys in a human-readable format.
     * Overwriting an existing file is not permitted.
     * <filename>
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
     * <zcashaddress>
     *
     * @deprecated
     * @param string $ZCashAddress
     * @return mixed
     */
    public function getAccount(string $ZCashAddress)
    {
        return $this->__call('getaccount', [$ZCashAddress]);
    }

    /**
     * Returns the current Zcash address for receiving payments to this account.
     * <account>
     *
     * @deprecated
     * @param string $account
     * @return mixed
     */
    public function getAccountAddress(string $account)
    {
        return $this->__call('getaccountaddress', [$account]);
    }

    /**
     * Returns the list of addresses for the given account.
     * <account>
     *
     * @deprecated
     * @param string $account
     * @return mixed
     */
    public function getAddressesByAccount(string $account)
    {
        return $this->__call('getaddressesbyaccount', [$account]);
    }

    /**
     * Returns the server's total available balance.
     * [account] [minconf=1] [includeWatchonly=false]
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
     * Returns a new Zcash address for receiving payments.
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
     * Returns a new Zcash address, for receiving change.
     * This is for use with raw transactions, NOT normal use.
     *
     * @return mixed
     */
    public function getRawChangeAddress()
    {
        return $this->__call('getrawchangeaddress');
    }

    /**
     * Returns the total amount received by addresses with <account> in transactions
     * with at least [minconf] confirmations.
     * <account> [minconf=1]
     *
     * @deprecated
     * @param string $account
     * @param int $minConf
     * @return mixed
     */
    public function getReceivedByAccount(string $account, int $minConf)
    {
        return $this->__call('getreceivedbyaccount', [$account, $minConf]);
    }

    /**
     * Returns the total amount received by the given Zcash address in transactions
     * with at least minconf confirmations.
     * <zcashaddress> [minconf=1]
     *
     * @param string $ZCashAddress
     * @param int $minConf
     * @return mixed
     */
    public function getReceivedByAddress(string $ZCashAddress, int $minConf)
    {
        return $this->__call('getreceivedbyaddress', [$ZCashAddress, $minConf]);
    }

    /**
     * Get detailed information about in-wallet transaction <txid>
     * <txid> [includeWatchonly=false]
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
     * Returns an object containing various wallet state info.
     *
     * @return mixed
     */
    public function getWalletInfo()
    {
        return $this->__call('getwalletinfo');
    }

    /**
     * Adds an address or script (in hex) that can be watched as if it were in your wallet but cannot be used to spend.
     * <address> [label] [rescan=true]
     *
     * @param string $address
     * @param string $label
     * @param bool $rescan
     * @return mixed
     */
    public function importAddress(string $address, string $label = '', bool $rescan = true)
    {
        return $this->__call('importaddress', [$address, $label, $rescan]);
    }

    /**
     * Adds a private key (as returned by dumpprivkey) to your wallet.
     * <zcashprivkey> [label] [rescan=true]
     *
     * @param string $ZCashPrivKey
     * @param string $label
     * @param bool $rescan
     * @return mixed
     */
    public function importPrivKey(string $ZCashPrivKey, string $label = '', bool $rescan = true)
    {
        return $this->__call('importprivkey', [$ZCashPrivKey, $label, $rescan]);
    }

    /**
     * Imports taddr keys from a wallet dump file (see dumpwallet).
     * <filename>
     *
     * @param string $filename
     * @return mixed
     */
    public function importWallet(string $filename)
    {
        return $this->__call('importwallet', [$filename]);
    }

    /**
     * Fills the keypool.
     * [newsize=100]
     *
     * @param int $newSize
     * @return mixed
     */
    public function keyPoolRefill(int $newSize = 100)
    {
        return $this->__call('keypoolrefill', [$newSize]);
    }

    /**
     * Returns Object that has account names as keys, account balances as values.
     * [minconf=1] [includeWatchonly=false]
     *
     * @deprecated
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
     * List balances by account.
     * [minconf=1] [includeempty=false]
     *
     * @deprecated
     * @param int $minConf
     * @param bool $includeEmpty
     * @return mixed
     */
    public function listReceivedByAccount(int $minConf, bool $includeEmpty)
    {
        return $this->__call('listreceivedbyaccount', [$minConf, $includeEmpty]);
    }

    /**
     * List balances by receiving address.
     * [minconf=1] [includeempty=false] [includeWatchonly=false]
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
     * Get all transactions in blocks since block [blockhash],
     * or all transactions if omitted
     * [blockhash] [target-confirmations=1] [includeWatchonly=false]
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
     * Returns up to 'count' most recent transactions skipping the
     * first 'from' transactions for account 'account'.
     * [account=*] [count=10] [from=0] [includeWatchonly=false]
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
     * Results are an array of Objects, each of which has:
     * {txid, vout, scriptPubKey, amount, confirmations}
     * [minconf=1] [maxconf=9999999] [addresses]
     *
     * @param int $minConf
     * @param int $maxConf
     * @param array $addresses
     * @return mixed
     */
    public function listUnspent(int $minConf = 1, int $maxConf = 9999999, array $addresses = [])
    {
        return $this->__call('listunspent', [$minConf, $maxConf, $addresses]);
    }

    /**
     * Updates list of temporarily unspendable outputs.
     * Temporarily lock (unlock=false) or unlock (unlock=true) specified transaction outputs.
     * A locked transaction output will not be chosen by automatic coin selection, when spending Zcash.
     * Locks are stored in memory only. Nodes start with zero locked outputs, and the locked output list
     * is always cleared (by virtue of process exit) when a node stops or fails.
     * Also see the listunspent call
     * <unlock> <transactions>
     *
     * @param bool $unlock
     * @param array $transactions
     * @return mixed
     */
    public function lockUnspent(bool $unlock, array $transactions)
    {
        return $this->__call('lockunspent', [$unlock, $transactions]);
    }

    /**
     * Move a specified amount from one account in your wallet to another.
     * <fromaccount> <toaccount> <amount> [minconf=1] [comment]
     *
     * @deprecated
     * @param string $fromAccount
     * @param string $toAccount
     * @param float $amount
     * @param int $minConf
     * @param string $comment
     * @return mixed
     */
    public function move(string $fromAccount, string $toAccount, float $amount, int $minConf, string $comment)
    {
        return $this->__call('move', [$fromAccount, $toAccount, $amount, $minConf, $comment]);
    }

    /**
     * DEPRECATED (use sendtoaddress). Sent an amount from an account to a Zcash address.
     * The amount is a real and is rounded to the nearest 0.00000001.
     * <fromaccount> <tozcashaddress> <amount> [minconf=1] [comment]
     *
     * @param string $fromAccount
     * @param string $toZCashAddress
     * @param float $amount
     * @param int $minConf
     * @param string $comment
     * @return mixed
     */
    public function sendFrom(string $fromAccount, string $toZCashAddress, float $amount, int $minConf = 1, string $comment = '')
    {
        return $this->__call('sendfrom', [$fromAccount, $toZCashAddress, $amount, $minConf, $comment]);
    }

    /**
     * Send multiple times. Amounts are double-precision floating point numbers.
     * <fromaccount> <amounts> [minconf=1] [comment] [subtractfeefromamount]
     *
     * @param string $fromAccount
     * @param array $amounts
     * @param int $minConf
     * @param string $comment
     * @param string $subtractFeeFromAmount
     * @return mixed
     */
    public function sendMany(string $fromAccount, array $amounts, int $minConf = 1, string $comment = '', string $subtractFeeFromAmount = '')
    {
        return $this->__call('sendmany', [$fromAccount, $amounts, $minConf, $comment, $subtractFeeFromAmount]);
    }

    /**
     * Send an amount to a given address. The amount is a real and is rounded
     * to the nearest 0.00000001
     * <zcashaddress> <amount> [comment] [comment-to] [subtractfeefromamount=false]
     *
     * @param string $ZCashAddress
     * @param float $amount
     * @param string $comment
     * @param string $commentTo
     * @param bool $subtractFeeFromAmount
     * @return mixed
     */
    public function sendToAddress(string $ZCashAddress, float $amount, string $comment = '', string $commentTo = '', bool $subtractFeeFromAmount = false)
    {
        return $this->__call('sendtoaddress', [$ZCashAddress, $amount, $comment, $commentTo, $subtractFeeFromAmount]);
    }

    /**
     * Sets the account associated with the given address.
     * <zcashaddress> <account>
     *
     * @deprecated
     * @param string $ZCashAddress
     * @param string $account
     * @return mixed
     */
    public function setAccount(string $ZCashAddress, string $account)
    {
        return $this->__call('setaccount', [$ZCashAddress, $account]);
    }

    /**
     * Set the transaction fee per kB.
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
     * Sign a message with the private key of a t-addr
     * <t-addr> <message>
     *
     * @param string $tAddr
     * @param string $message
     * @return mixed
     */
    public function signMessage(string $tAddr, string $message)
    {
        return $this->__call('signmessage', [$tAddr, $message]);
    }

    /**
     * Reveals the zkey corresponding to 'zaddr'.
     * Then the z_importkey can be used with this output
     * <zaddr>
     *
     * @param string $zAddr
     * @return mixed
     */
    public function z_exportKey(string $zAddr)
    {
        return $this->__call('z_exportkey', [$zAddr]);
    }

    /**
     * Reveals the viewing key corresponding to 'zaddr'.
     * Then the z_importviewingkey can be used with this output
     * <zaddr>
     *
     * @param string $zAddr
     * @return mixed
     */
    public function z_exportViewingKey(string $zAddr)
    {
        return $this->__call('z_exportviewingkey', [$zAddr]);
    }

    /**
     * Exports all wallet keys, for taddr and zaddr, in a human-readable format.
     * Overwriting an existing file is not permitted.
     * <filename>
     *
     * @param string $filename
     * @return mixed
     */
    public function z_exportWallet(string $filename)
    {
        return $this->__call('z_exportwallet', [$filename]);
    }

    /**
     * Returns the balance of a taddr or zaddr belonging to the node’s wallet.
     *
     * CAUTION: If address is a watch-only zaddr, the returned balance may be larger than the actual balance,
     * because spends cannot be detected with incoming viewing keys.
     * <address> [minconf=1]
     *
     * @param string $address
     * @param int $minConf
     * @return mixed
     */
    public function z_getBalance(string $address, int $minConf = 1)
    {
        return $this->__call('z_getbalance', [$address, $minConf]);
    }

    /**
     * Returns a new shielded address for receiving payments.
     * With no arguments, returns a Sprout address.
     * [type=sprout]
     *
     * @param string $type
     * @return mixed
     */
    public function z_getNewAddress(string $type = 'sprout')
    {
        return $this->__call('z_getnewaddress', [$type]);
    }

    /**
     * Retrieve the result and status of an operation which has finished, and then remove the operation from memory.
     * [operationid]
     *
     * @param array $operationId
     * @return mixed
     */
    public function z_getOperationResult(array $operationId = [])
    {
        return $this->__call('z_getoperationresult', [$operationId]);
    }

    /**
     * Get operation status and any associated result or error data.
     * The operation will remain in memory.
     * [operationid]
     *
     * @param array $operationId
     * @return mixed
     */
    public function z_getOperationStatus(array $operationId)
    {
        return $this->__call('z_getoperationstatus', [$operationId]);
    }

    /**
     * Return the total value of funds stored in the node’s wallet.
     * CAUTION: If the wallet contains watch-only zaddrs, the returned private balance may be larger than the actual balance,
     * because spends cannot be detected with incoming viewing keys.
     * [minconf=1] [includeWatchonly=false]
     *
     * @param int $minConf
     * @param bool $includeWatchOnly
     * @return mixed
     */
    public function z_getTotalBalance(int $minConf = 1, bool $includeWatchOnly = false)
    {
        return $this->__call('z_gettotalbalance', [$minConf, $includeWatchOnly]);
    }

    /**
     * Adds a zkey (as returned by z_exportkey) to your wallet.
     * <zkey> [rescan=whenkeyisnew] [startHeight=0]
     *
     * @param string $zKey
     * @param string $rescan
     * @param int $startHeight
     * @return mixed
     */
    public function z_importKey(string $zKey, string $rescan = 'whenkeyisnew', int $startHeight = 0)
    {
        return $this->__call('z_importkey', [$zKey, $rescan, $startHeight]);
    }

    /**
     * Adds a viewing key (as returned by z_exportviewingkey) to your wallet.
     * <vkey> [rescan=whenkeyisnew] [startHeight=0]
     *
     * @param string $vKey
     * @param string $rescan
     * @param int $startHeight
     * @return mixed
     */
    public function z_importViewingKey(string $vKey, string $rescan = 'whenkeyisnew', int $startHeight = 0)
    {
        return $this->__call('z_importviewingkey', [$vKey, $rescan, $startHeight]);
    }

    /**
     * Imports taddr and zaddr keys from a wallet export file (see z_exportwallet).
     * <filename>
     *
     * @param string $filename
     * @return mixed
     */
    public function z_importWallet(string $filename)
    {
        return $this->__call('z_importwallet', [$filename]);
    }

    /**
     * Returns the list of Sprout and Sapling shielded addresses belonging to the wallet.
     * [includeWatchonly=false]
     *
     * @param bool $includeWatchOnly
     * @return mixed
     */
    public function z_listAddresses(bool $includeWatchOnly = false)
    {
        return $this->__call('z_listaddresses', [$includeWatchOnly]);
    }

    /**
     * Returns the list of operation ids currently known to the wallet.
     * [status=success]
     *
     * @param string $status
     * @return mixed
     */
    public function z_listOperationIds(string $status = 'success')
    {
        return $this->__call('z_listoperationids', [$status]);
    }

    /**
     * Return a list of amounts received by a zaddr belonging to the node’s wallet.
     * <address> [minconf=1]
     *
     * @param string $address
     * @param int $minConf
     * @return mixed
     */
    public function z_listReceivedByAddress(string $address, int $minConf = 1)
    {
        return $this->__call('z_listreceivedbyaddress', [$address, $minConf]);
    }

    /**
     * Returns array of unspent shielded notes with between minconf and maxconf (inclusive) confirmations.
     * Optionally filter to only include notes sent to specified addresses.
     * When minconf is 0, unspent notes with zero confirmations are returned, even though they are not immediately spendable.
     * Results are an array of Objects, each of which has:
     * {txid, jsindex, jsoutindex, confirmations, address, amount, memo}
     * [minconf=1] [maxconf=9999999] [includeWatchonly=false] [addresses]
     *
     * @param int $minConf
     * @param int $maxConf
     * @param bool $includeWatchOnly
     * @param array $addresses
     * @return mixed
     */
    public function z_listUnspent(int $minConf = 1, int $maxConf = 9999999, bool $includeWatchOnly = false, array $addresses = [])
    {
        return $this->__call('z_listunspent', [$minConf, $maxConf, $includeWatchOnly, $addresses]);
    }

    /**
     * WARNING: z_mergetoaddress is disabled.
     * To enable it, restart zcashd with the -experimentalfeatures and
     * -zmergetoaddress commandline options, or add these two lines
     * to the zcash.conf file:
     * experimentalfeatures=1
     * zmergetoaddress=1
     *
     * Merge multiple UTXOs and notes into a single UTXO or note.
     * Coinbase UTXOs are ignored; use `z_shieldcoinbase`
     * to combine those into a single note.
     *
     * This is an asynchronous operation, and UTXOs selected for merging will be locked.
     * If there is an error, they are unlocked.
     * The RPC call `listlockunspent` can be used to return a list of locked UTXOs.
     *
     * The number of UTXOs and notes selected for merging can be limited by the caller.  If the transparent limit
     * parameter is set to zero, and Overwinter is not yet active, the -mempooltxinputlimit option will determine the
     * number of UTXOs.  Any limit is constrained by the consensus rule defining a maximum transaction size of
     * 100000 bytes before Sapling, and 2000000 bytes once Sapling activates.
     * <fromaddresses> <toaddress> [fee=0.0001] [transparent_limit=50] [shielded_limit=10] [memo]
     *
     * @param array $fromAddresses
     * @param string $toAddress
     * @param float $fee
     * @param int $transparentLimit
     * @param int $shieldedLimit
     * @param string $memo
     * @return mixed
     */
    public function z_mergeToAddress(array $fromAddresses, string $toAddress, float $fee = 0.0001, int $transparentLimit = 50, int $shieldedLimit = 10, string $memo = '')
    {
        return $this->__call('z_mergetoaddress', [$fromAddresses, $toAddress, $fee, $transparentLimit, $shieldedLimit, $memo]);
    }

    /**
     * Send multiple times. Amounts are double-precision floating point numbers.
     * Change from a taddr flows to a new taddr address, while change from zaddr returns to itself.
     * When sending coinbase UTXOs to a zaddr, change is not allowed. The entire value of the UTXO(s) must be consumed.
     * Before Sapling activates, the maximum number of zaddr outputs is 54 due to transaction size limits.
     * <fromaddress> <amount> [minconf=1] [fee=0.0001]
     *
     * @param string $fromAddress
     * @param float $amount
     * @param int $minConf
     * @param float $fee
     * @return mixed
     */
    public function z_sendMany(string $fromAddress, float $amount, int $minConf = 1, float $fee = 0.0001)
    {
        return $this->__call('z_sendmany', [$fromAddress, $this->rootWallet, $amount, $minConf, $fee]);
    }

    /**
     * Shield transparent coinbase funds by sending to a shielded zaddr.  This is an asynchronous operation and utxos
     * selected for shielding will be locked.  If there is an error, they are unlocked.  The RPC call `listlockunspent`
     * can be used to return a list of locked utxos.  The number of coinbase utxos selected for shielding can be limited
     * by the caller.  If the limit parameter is set to zero, and Overwinter is not yet active, the -mempooltxinputlimit
     * option will determine the number of uxtos.  Any limit is constrained by the consensus rule defining a maximum
     * transaction size of 100000 bytes before Sapling, and 2000000 bytes once Sapling activates.
     * <fromaddress> <toaddress> [fee=0.0001] [limit=50]
     *
     * @param string $fromAddress
     * @param string $toAddress
     * @param float $fee
     * @param int $limit
     * @return mixed
     */
    public function z_shieldCoinBase(string $fromAddress, string $toAddress, float $fee = 0.0001, int $limit = 50)
    {
        return $this->__call('z_shieldcoinbase', [$fromAddress, $toAddress, $fee, $limit]);
    }

    /**
     * Runs a benchmark of the selected type samplecount times,
     * returning the running times of each sample.
     *
     * @return mixed
     */
    public function zcBenchmark()
    {
        return $this->__call('zcbenchmark');
    }

    /**
     * Splices a joinsplit into rawtx. Inputs are unilaterally confidential.
     * Outputs are confidential between sender/receiver. The vpub_old and
     * vpub_new values are globally public and move transparent value into
     * or out of the confidential value store, respectively.
     *
     * Note: The caller is responsible for delivering the output enc1 and
     * enc2 to the appropriate recipients, as well as signing rawtxout and
     * ensuring it is mined. (A future RPC call will deliver the confidential
     * payments in-band on the blockchain.)
     *
     * @deprecated
     * @return mixed
     */
    public function zcRawJoinSplit()
    {
        return $this->__call('zcrawjoinsplit');
    }

    /**
     * Generate a zcaddr which can send and receive confidential values.
     *
     * @deprecated
     * @return mixed
     */
    public function zcRawKeyGen()
    {
        return $this->__call('zcrawkeygen');
    }

    /**
     * Decrypts encryptednote and checks if the coin commitments
     * are in the blockchain as indicated by the "exists" result.
     *
     * @deprecated
     * @return mixed
     */
    public function zcRawReceive()
    {
        return $this->__call('zcrawreceive');
    }

    /**
     * Perform a joinsplit and return the JSDescription.
     *
     * @return mixed
     */
    public function zcSampleJoinSplit()
    {
        return $this->__call('zcsamplejoinsplit');
    }
}