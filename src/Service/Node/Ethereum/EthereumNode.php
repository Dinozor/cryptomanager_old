<?php

namespace App\Service\Node\Ethereum;

use App\Service\NodeDataManager;
use Doctrine\ORM\Query\AST\Node;
use JsonRpc\Client;

class EthereumNode
{

    private const URL = "http://127.0.0.1:8545";
    private $client;
    private $lastCheckedBlock = 0;
    private $totalBlocks = 0;
    private $rootWallet = '';
    private $settings = null;
    /**
     * @var NodeDataManager
     */
    private $dataManager = null;

    public function __construct(NodeDataManager $dataManager = null, ?string $rootWallet = null, $settings = null)
    {
        $this->client = new Client(self::URL);
        $this->rootWallet = $rootWallet;
        $this->rootWallet = $this->getAccounts()[0];
        $this->dataManager = $dataManager;
    }

    public function getStatus()
    {
        // TODO: Implement getStatus() method.
    }

    public function getVersion()
    {
        return $this->_call('eth_protocolVersion');
    }

    /**
     * Returns an object with data about the sync status or false.
     * @return mixed|String
     * Object|Boolean, An object with sync status data or FALSE, when not syncing:
     * startingBlock: QUANTITY - The block at which the import started (will only be reset, after the sync reached his head)
     * currentBlock: QUANTITY - The current block, same as eth_blockNumber
     * highestBlock: QUANTITY - The estimated highest block
     */
    public function getSyncingStatus()
    {
        return $this->_call('eth_syncing');
    }

    /**
     * Returns the client coinbase address.
     * @return mixed|String
     * Returns
     * DATA, 20 bytes - the current coinbase address.
     */
    public function getCoinbaseAddress()
    {
        return $this->_call('eth_coinbase');
    }

    /**
     * Returns the number of hashes per second that the node is mining with.
     * @return mixed|String
     * Returns
     * QUANTITY - number of hashes per second.
     */
    public function getHashrate()
    {
        return $this->_call('eth_hashrate');
    }

    /**
     * Returns true if client is actively mining new blocks.
     * @return mixed|String
     * Returns
     * Boolean - returns true of the client is mining, otherwise false
     */
    public function isMining()
    {
        return $this->_call('eth_mining');
    }

    /**
     * Returns the current price per gas in wei.
     * @return mixed|String
     * Returns
     * QUANTITY - integer of the current gas price in wei.
     */
    public function getGasPrice()
    {
        return $this->_call('eth_gasPrice');
    }

    /**
     * Returns a list of addresses owned by client.
     * @return mixed|String
     * Returns
     * Array of DATA, 20 Bytes - addresses owned by the client.
     */
    public function getAccounts()
    {
        return $this->_call('eth_accounts');
    }

    /**
     * Returns the number of most recent block.
     * @return mixed|String
     * Returns
     * QUANTITY - integer of the current block number the client is on.
     */
    public function getLatestBlockNumber()
    {
        return $this->_call('eth_blockNumber');
    }

    /**
     * Returns the balance of the account of given address.
     * @param string $address , DATA, 20 Bytes - address to check for balance.
     * @param string $tag , QUANTITY|TAG - integer block number, or the string "latest", "earliest" or "pending", see the default block parameter
     * @return mixed|String
     * Returns
     * QUANTITY - integer of the current balance in wei.
     */
    public function getBalance(string $address, $tag = 'latest')
    {
        return $this->_call('eth_getBalance', [$address, $tag]);
    }

    /*
    public function getStorageAt($storage_address,int $position, $tag)
    */

    /**
     * Returns the number of transactions sent from an address.
     * @param string $address , DATA, 20 Bytes - address.
     * @param $tag , QUANTITY|TAG - integer block number, or the string "latest", "earliest" or "pending", see the default block parameter
     * @return mixed|String
     * Returns
     * QUANTITY - integer of the number of transactions send from this address.
     */
    public function getTransactionCount(string $address, $tag)
    {
        return $this->_call('eth_getTransactionCount', [$address, $tag]);
    }

    /**
     * Returns the number of transactions in a block from a block matching the given block hash.
     * @param $hash , DATA, 32 Bytes - hash of a block.
     * Returns
     * QUANTITY - integer of the number of transactions in this block.
     */
    public function getBlockTransactionCountByHash($hash)
    {
        return $this->_call('eth_getBlockTransactionCountByHash', [$hash]);
    }

    /**
     * Returns the number of transactions in a block matching the given block number.
     * @param $tag , QUANTITY|TAG - integer of a block number, or the string "earliest", "latest" or "pending", as in the default block parameter.
     * @return mixed|String
     * Returns
     * QUANTITY - integer of the number of transactions in this block.
     */
    public function getBlockTransactionCountByNumber($tag)
    {
        return $this->_call('eth_getBlockTransactionCountByNumber', [$tag]);
    }

    /**
     * Returns the number of uncles in a block from a block matching the given block hash.
     * @param $hash , DATA, 32 Bytes - hash of a block.
     * @return mixed|String
     * Returns
     * QUANTITY - integer of the number of uncles in this block.
     */
    public function getUncleCountByBlockHash($hash)
    {
        return $this->_call('eth_getUncleCountByBlockHash', [$hash]);
    }

    /**
     * Returns the number of uncles in a block from a block matching the given block number.
     * @param $tag , QUANTITY|TAG - integer of a block number, or the string "latest", "earliest" or "pending", see the default block parameter.
     * @return mixed|String
     * Returns
     * QUANTITY - integer of the number of uncles in this block.
     */
    public function getUncleCountByBlockNumber($tag)
    {
        return $this->_call('eth_getUncleCountByBlockNumber', [$tag]);
    }

    /**
     * Returns code at a given address.
     * @param $address , DATA, 20 Bytes - address.
     * @param $tag , QUANTITY|TAG - integer block number, or the string "latest", "earliest" or "pending", see the default block parameter.
     * @return mixed|String
     * Returns
     * DATA - the code from the given address.
     */
    public function getCode($address, $tag)
    {
        return $this->_call('eth_getCode', [$address, $tag]);
    }

    /**
     * The sign method calculates an Ethereum specific signature with: sign(keccak256("\x19Ethereum Signed Message:\n" + len(message) + message))).
     * By adding a prefix to the message makes the calculated signature recognisable as an Ethereum specific signature.
     * This prevents misuse where a malicious DApp can sign arbitrary data (e.g. transaction) and use the signature to impersonate the victim.
     * Note the address to sign with must be unlocked.
     * @param $address , DATA, 20 Bytes - address.
     * @param $message , DATA, N Bytes - message to sign.
     * @return mixed|String
     * Returns
     * DATA: Signature
     */
    public function sign($address, $message)
    {
        return $this->_call('eth_sign', [$address, $message]);
    }

    /**
     * Creates new message call transaction or a contract creation, if the data field contains code.
     * @param string $from , DATA, 20 Bytes - The address the transaction is send from.
     * @param string $to , DATA, 20 Bytes - (optional when creating new contract) The address the transaction is directed to.
     * @param string $b_gas , QUANTITY - (optional, default: 90000) Integer of the gas provided for the transaction execution. It will return unused gas.
     * @param string $b_gas_price , QUANTITY - (optional, default: To-Be-Determined) Integer of the gasPrice used for each paid gas
     * @param string $b_value , QUANTITY - (optional) Integer of the value sent with this transaction
     * @param string $b_data , DATA - The compiled code of a contract OR the hash of the invoked method signature and encoded parameters. For details see Ethereum Contract ABI
     * @param string $nonce , QUANTITY - (optional) Integer of a nonce. This allows to overwrite your own pending transactions that use the same nonce.
     * @return mixed|String
     * Returns
     * DATA, 32 Bytes - the transaction hash, or the zero hash if the transaction is not yet available.
     */
    public function sendTransaction(string $from, string $to, string $b_gas, string $b_gas_price, string $b_value = '', string $b_data = '', string $nonce = '')
    {
        return $this->_call('eth_sendTransaction', [
            'from' => $from,
            'to' => $to,
            'gas' => $b_gas,
            "gasPrice" => $b_gas_price,
            "value" => $b_value,
            "data" => $b_data,
            "nonce" => $nonce
        ]);
    }

    /**
     * Creates new message call transaction or a contract creation for signed transactions.
     * Use eth_getTransactionReceipt to get the contract address, after the transaction was mined, when you created a contract.
     * @param $data , DATA, The signed transaction data.
     * @return mixed|String
     * Returns
     * DATA, 32 Bytes - the transaction hash, or the zero hash if the transaction is not yet available.
     */
    public function sendRawTransaction($data)
    {
        return $this->_call('eth_sendRawTransaction', [$data]);
    }

    /**
     * Executes a new message call immediately without creating a transaction on the block chain.
     * @param string $from , DATA, 20 Bytes - (optional) The address the transaction is sent from.
     * @param string $to , DATA, 20 Bytes - The address the transaction is directed to.
     * @param string $b_gas , QUANTITY - (optional) Integer of the gas provided for the transaction execution.
     * eth_call consumes zero gas, but this parameter may be needed by some executions.
     * @param string $b_gas_price , QUANTITY - (optional) Integer of the gasPrice used for each paid gas
     * @param string $b_value , QUANTITY - (optional) Integer of the value sent with this transaction
     * @param string $b_data , DATA - (optional) Hash of the method signature and encoded parameters. For details see Ethereum Contract ABI
     * @param string $tag ,
     * QUANTITY|TAG - integer block number, or the string "latest", "earliest" or "pending", see the default block parameter
     * @return mixed|String
     * Returns
     * DATA - the return value of executed contract.
     */
    public function call(string $from, string $to, string $b_gas, string $b_gas_price, string $b_value = '', string $b_data = '', $tag = 'latest')
    {
        return $this->_call('eth_call', [
            'from' => $from,
            'to' => $to,
            'gas' => $b_gas,
            "gasPrice" => $b_gas_price,
            "value" => $b_value,
            "data" => $b_data,
            "tag" => $tag
        ]);
    }

    /**
     * Generates and returns an estimate of how much gas is necessary to allow the transaction to complete.
     * The transaction will not be added to the blockchain.
     * Note that the estimate may be significantly more than the amount of gas actually used by the transaction,
     * for a variety of reasons including EVM mechanics and node performance.
     *
     * Parameters
     * See eth_call parameters, expect that all properties are optional.
     * If no gas limit is specified geth uses the block gas limit from the pending block as an upper bound.
     * As a result the returned estimate might not be enough to executed the call/transaction when
     * the amount of gas is higher than the pending block gas limit.
     * @param string $from , DATA, 20 Bytes - (optional) The address the transaction is sent from.
     * @param string $to , DATA, 20 Bytes - The address the transaction is directed to.
     * @param string $b_gas , QUANTITY - (optional) Integer of the gas provided for the transaction execution.
     * eth_call consumes zero gas, but this parameter may be needed by some executions.
     * @param string $b_gas_price , QUANTITY - (optional) Integer of the gasPrice used for each paid gas
     * @param string $b_value , QUANTITY - (optional) Integer of the value sent with this transaction
     * @param string $b_data , DATA - (optional) Hash of the method signature and encoded parameters. For details see Ethereum Contract ABI
     * @param string $tag ,
     * QUANTITY|TAG - integer block number, or the string "latest", "earliest" or "pending", see the default block parameter
     * @return mixed|String
     * Returns
     * QUANTITY - the amount of gas used.
     */
    public function estimateGas(string $from = '', string $to = '', string $b_gas = '', string $b_gas_price = '', string $b_value = '', string $b_data = '', $tag = 'latest')
    {
        return $this->_call('eth_estimateGas', [
            [
                'from' => $from,
                'to' => $to,
                'gas' => $b_gas,
                "gasPrice" => $b_gas_price,
                "value" => $b_value,
                "data" => $b_data
            ],
            $tag
        ]);
    }

    /**
     * Returns information about a block by hash.
     * @param $hash , DATA, 32 Bytes - Hash of a block.
     * @param bool $full , Boolean - If true it returns the full transaction objects, if false only the hashes of the transactions.
     * @return mixed|String
     * Returns
     * Object - A block object, or null when no block was found:
     *
     * number: QUANTITY - the block number. null when its pending block.
     * hash: DATA, 32 Bytes - hash of the block. null when its pending block.
     * parentHash: DATA, 32 Bytes - hash of the parent block.
     * nonce: DATA, 8 Bytes - hash of the generated proof-of-work. null when its pending block.
     * sha3Uncles: DATA, 32 Bytes - SHA3 of the uncles data in the block.
     * logsBloom: DATA, 256 Bytes - the bloom filter for the logs of the block. null when its pending block.
     * transactionsRoot: DATA, 32 Bytes - the root of the transaction trie of the block.
     * stateRoot: DATA, 32 Bytes - the root of the final state trie of the block.
     * receiptsRoot: DATA, 32 Bytes - the root of the receipts trie of the block.
     * miner: DATA, 20 Bytes - the address of the beneficiary to whom the mining rewards were given.
     * difficulty: QUANTITY - integer of the difficulty for this block.
     * totalDifficulty: QUANTITY - integer of the total difficulty of the chain until this block.
     * extraData: DATA - the "extra data" field of this block.
     * size: QUANTITY - integer the size of this block in bytes.
     * gasLimit: QUANTITY - the maximum gas allowed in this block.
     * gasUsed: QUANTITY - the total used gas by all transactions in this block.
     * timestamp: QUANTITY - the unix timestamp for when the block was collated.
     * transactions: Array - Array of transaction objects, or 32 Bytes transaction hashes depending on the last given parameter.
     * uncles: Array - Array of uncle hashes.
     */
    public function getBlockByHash($hash, bool $full)
    {
        return $this->_call('eth_getBlockByHash', [$hash, $full]);
    }

    /**
     * Returns information about a block by block number.
     * @param $tag , QUANTITY|TAG - integer of a block number, or the string "earliest", "latest" or "pending", as in the default block parameter.
     * @param bool $full, Boolean - If true it returns the full transaction objects, if false only the hashes of the transactions.
     * @return mixed|String
     * Returns
        * See eth_getBlockByHash
     */
    public function getBlockByNumber($tag, bool $full)
    {
        return $this->_call('eth_getBlockByNumber', [$tag, $full]);
    }

    /**
     * Returns the information about a transaction requested by transaction hash.
     * @param string $hash, DATA, 32 Bytes - hash of a transaction
     * @return mixed|String
     * Returns
        Object - A transaction object, or null when no transaction was found:

        blockHash: DATA, 32 Bytes - hash of the block where this transaction was in. null when its pending.
        blockNumber: QUANTITY - block number where this transaction was in. null when its pending.
        from: DATA, 20 Bytes - address of the sender.
        gas: QUANTITY - gas provided by the sender.
        gasPrice: QUANTITY - gas price provided by the sender in Wei.
        hash: DATA, 32 Bytes - hash of the transaction.
        input: DATA - the data send along with the transaction.
        nonce: QUANTITY - the number of transactions made by the sender prior to this one.
        to: DATA, 20 Bytes - address of the receiver. null when its a contract creation transaction.
        transactionIndex: QUANTITY - integer of the transactions index position in the block. null when its pending.
        value: QUANTITY - value transferred in Wei.
        v: QUANTITY - ECDSA recovery id
        r: DATA, 32 Bytes - ECDSA signature r
        s: DATA, 32 Bytes - ECDSA signature s
     */
    public function getTransactionByHash(string $hash)
    {
        return $this->_call('eth_getTransactionByHash', [$hash]);
    }

    /**
     * Returns information about a transaction by block hash and transaction index position.
     * @param $hash, DATA, 32 Bytes - hash of a block.
     * @param $index, QUANTITY - integer of the transaction index position.
     * @return mixed|String
     * Returns
        See eth_getTransactionByHash
     */
    public function getTransactionByBlockHashAndIndex($hash, $index)
    {
        return $this->_call('eth_getTransactionByBlockHashAndIndex', [$hash, $index]);
    }

    /**
     * Returns information about a transaction by block number and transaction index position.
     * @param $number, QUANTITY|TAG - a block number, or the string "earliest", "latest" or "pending", as in the default block parameter.
     * @param $index, QUANTITY - the transaction index position.
     * @return mixed|String
     * Returns
        See eth_getTransactionByHash
     */
    public function getTransactionByBlockNumberAndIndex($number, $index)
    {
        return $this->_call('eth_getTransactionByBlockNumberAndIndex', [$number, $index]);
    }

    /**
     * Returns the receipt of a transaction by transaction hash.
        Note That the receipt is not available for pending transactions.
     * @param $hash, DATA, 32 Bytes - hash of a transaction
     * @return mixed|String
     * Returns
        Object - A transaction receipt object, or null when no receipt was found:

        transactionHash: DATA, 32 Bytes - hash of the transaction.
        transactionIndex: QUANTITY - integer of the transactions index position in the block.
        blockHash: DATA, 32 Bytes - hash of the block where this transaction was in.
        blockNumber: QUANTITY - block number where this transaction was in.
        from: DATA, 20 Bytes - address of the sender.
        to: DATA, 20 Bytes - address of the receiver. null when its a contract creation transaction.
        cumulativeGasUsed: QUANTITY - The total amount of gas used when this transaction was executed in the block.
        gasUsed: QUANTITY - The amount of gas used by this specific transaction alone.
        contractAddress: DATA, 20 Bytes - The contract address created, if the transaction was a contract creation, otherwise null.
        logs: Array - Array of log objects, which this transaction generated.
        logsBloom: DATA, 256 Bytes - Bloom filter for light clients to quickly retrieve related logs.

        It also returns either :

        root : DATA 32 bytes of post-transaction stateroot (pre Byzantium)
        status: QUANTITY either 1 (success) or 0 (failure)

     */
    public function eth_getTransactionReceipt($hash)
    {
        return $this->_call('eth_getTransactionReceipt', [$hash]);
    }

    /**
     * @param $fromBlock, fromBlock: QUANTITY|TAG - (optional, default: "latest") Integer block number,
     *  or "latest" for the last mined block or "pending", "earliest" for not yet mined transactions.
     * @param $toBlock, toBlock: QUANTITY|TAG - (optional, default: "latest") Integer block number,
     *  or "latest" for the last mined block or "pending", "earliest" for not yet mined transactions.
     * @param $address, address: DATA|Array, 20 Bytes - (optional) Contract address or a list of addresses from which logs should originate.
     * @param $topic, topics: Array of DATA, - (optional) Array of 32 Bytes DATA topics.
     *  Topics are order-dependent. Each topic can also be an array of DATA with "or" options.
     * @return mixed|String
     * Returns
        QUANTITY - A filter id.
     */
    public function newFilter($address, $fromBlock, $toBlock, $topic)
    {
        return $this->_call('eth_newFilter', [[
            'fromBlock' => $fromBlock,
            'address' => $address
        ]]);
    }





    public function newTransactionFilter(string $address)
    {
        return $this->_call('eth_newFilter', [[
            'address' => $address
        ]]);
    }

    public function newPendingTransactionFilter()
    {
        return $this->_call('eth_newPendingTransactionFilter');
    }

    public function getFilterChanges($filterID)
    {
        return $this->_call('eth_getFilterChanges', [$filterID]);
    }

    private function _call(string $method, $params = [])
    {
        if ($this->client->call($method, $params)) {
            return $this->client->result;
        } else {
            return $this->client->error;
        }
    }





    public function getNewAddress(string $name = null)
    {
        return $this->_call('personal_newAccount', ['']);
    }

    public function send(string $from, string $to, string $b_value, string $b_gas, string $b_gas_price, string $b_data = '')
    {
        return $this->_call('eth_sendTransaction', [
            'from' => $from,
            'to' => $to,
            'gas' => $b_gas,
            "gasPrice" => $b_gas_price,
            "value" => $b_value,
            "data" => $b_data
        ]);

        return $this->_call('eth_sendTransaction', [
            'from' => $this->rootWallet,
            'to' => $address,
            'gas' => 0x76c0,
//            "gas": "0x76c0", // 30400
            "gasPrice" => "0x9184e72a000", // 10000000000000
            "value" => "0x9184e72a", // 2441406250
            "data" => ''
        ]);
    }

    static function weiToEth($wei)
    {
        return bcdiv($wei, 1000000000000000000, 18);
    }

    static function hexToDec($hex)
    {
        if (strlen($hex) == 1) {
            return hexdec($hex);
        } else {
            $remain = substr($hex, 0, -1);
            $last = substr($hex, -1);
            return bcadd(bcmul(16, self::hexToDec($remain)), hexdec($last));
        }
    }

    private function getAllUserTransactions()
    {

    }

    private function helpListUserTransacions()
    {
//        var n = eth.blocknumber;
//
//        var txs = [];
//        for(var i = 0; i < n; i++) {
//            var block = eth.getBlock(i, true);
//            for(var j = 0; j < block.transactions; j++) {
//                if( block.transactions[j].to == the_address )
//                    txs.push(block.transactions[j]);
//            }
//        }
    }

    private function helpCheckAllBalances()
    {
//        function checkAllBalances() {
//            web3.eth.getAccounts(function(err, accounts) {
//                accounts.forEach(function(id) {
//                    web3.eth.getBalance(id, function(err, balance) {
//                        console.log("" + id + ":\tbalance: " + web3.fromWei(balance, "ether") + " ether");
//                    });
//                });
//            });
//        };
    }

    private function helpListenFutureTransactions()
    {
//        var options = {address: "0xf2cc0eeaaaed313542cb262b0b8c3972425143f0"};
//
//        var myfilter= web3.eth.filter(options);
//        console.log(myfilter);
//
//        myfilter.get(function (error, log) {
//            console.log("get error:", error);
//            console.log("get log:", log);
//        });
//
//        VM176:5 Filter {requestManager: RequestManager, options: Object, implementation: Object, filterId: null, callbacks: Array[0]…}
//        Filter {requestManager: RequestManager, options: Object, implementation: Object, filterId: null, callbacks: Array[0]…}
//        VM176:8 get error: null
//        VM176:9 get log: []
    }

    public function getName()
    {
        // TODO: Implement getName() method.
    }

    public function getCurrency()
    {
        // TODO: Implement getCurrency() method.
    }
}