<?php

namespace App\Service\Node\Ripple;

use App\Service\Node\BaseNode;
use App\Service\NodeDataManager;

class RippleNode extends BaseNode
{
    private $rootWallet;
    private $dataManager;

    public function __construct(NodeDataManager $dataManager = null, ?string $rootWallet = null, $settings = null)
    {
        parent::__construct('', '', 'https://data.ripple.com');
        $this->dataManager = $dataManager;
        $this->rootWallet = $rootWallet;
    }

    /**
     * The account_info command retrieves information about an account,
     * its activity, and its XRP balance. All information retrieved is
     * relative to a particular version of the ledger.
     * <account> [strict=false] [ledger_hash] [ledger_index] [queue=false] [signer_lists=false]
     *
     * @param string $account
     * @param bool $strict
     * @param string $ledgerHash
     * @param string $ledgerIndex
     * @param bool $queue
     * @param bool $signerLists
     * @return mixed
     */
    public function accountInfo(string $account, bool $strict = false, string $ledgerHash = '', string $ledgerIndex = '', bool $queue = false, bool $signerLists = false)
    {
        return $this->_call('account_info', [$account, $strict, $ledgerHash, $ledgerIndex, $queue, $signerLists]);
    }

    /**
     * The account_tx method retrieves a list of transactions that involved
     * the specified account.
     * [ledger_index_min] [ledger_index_max] [ledger_hash] [ledger_index] [binary] [forward] [limit] [marker]
     *
     * @param string $account
     * @param int $ledgerIndexMin
     * @param int $ledgerIndexMax
     * @param string $ledgerHash
     * @param string $ledgerIndex
     * @param bool $binary
     * @param bool $forward
     * @param int $limit
     * @param string $marker
     * @return mixed|string
     */
    public function accountTx(string $account, int $ledgerIndexMin = -1, int $ledgerIndexMax = -1, string $ledgerHash = '', string $ledgerIndex = '', bool $binary = false, bool $forward = false, int $limit = 10, string $marker = '')
    {
        return $this->_call('account_tx', [$account, $ledgerIndexMin, $ledgerIndexMax, $ledgerHash, $ledgerIndex, $binary, $forward, $limit, $marker]);
    }

    /**
     * The tx method retrieves information on a single transaction.
     * <transaction> [binary=false]
     *
     * @param string $transaction
     * @param bool $binary
     * @return mixed
     */
    public function tx(string $transaction, bool $binary = false)
    {
        return $this->_call('tx', [$transaction, $binary]);
    }

    /**
     * The sign method takes a transaction in JSON format and a secret key,
     * and returns a signed binary representation of the transaction.
     * The result is always different, even when you provide the same
     * transaction JSON and secret key. To contribute one signature to
     * a multi-signed transaction, use the sign_for method instead.
     * <tx_json> [secret] [seed_hex] [passphrase] [key_type=secp256k1] [offline=false] [build_path=false] [fee_mult_max=100] [fee_div_max=1]
     *
     * @param array $txJson
     * @param string $secret
     * @param string $seedHex
     * @param string $passPhrase
     * @param string $keyType
     * @param bool $offline
     * @param bool $buildPath
     * @param int $feeMultMax recommended value 1000
     * @param int $feeDivMax
     * @return mixed
     */
    public function sign(array $txJson, string $secret = '', string $seedHex = '', string $passPhrase = '', string $keyType = 'secp256k1', bool $offline = false, bool $buildPath = false, int $feeMultMax = 100, int $feeDivMax = 1)
    {
        return $this->_call('sign', [$txJson, $secret, $seedHex, $passPhrase, $keyType, $offline, $buildPath, $feeMultMax, $feeDivMax]);
    }

    /**
     * The submit method applies a transaction and sends it to the network
     * to be confirmed and included in future ledgers.
     *
     * This command has two modes:
     *
     * Submit-only mode takes a signed, serialized transaction as a binary blob,
     * and submits it to the network as-is. Since signed transaction objects are
     * immutable, no part of the transaction can be modified or automatically
     * filled in after submission.
     *
     * Sign-and-submit mode takes a JSON-formatted Transaction object,
     * completes and signs the transaction in the same manner as the sign method,
     * and then submits the signed transaction. We recommend only using this mode
     * for testing and development.
     *
     * To send a transaction as robustly as possible, you should construct and sign it
     * in advance, persist it somewhere that you can access even after a power outage,
     * then submit it as a tx_blob. After submission, monitor the network with
     * the tx method command to see if the transaction was successfully applied;
     * if a restart or other problem occurs, you can safely re-submit the tx_blob
     * transaction: it won't be applied twice since it has the same sequence number
     * as the old transaction.
     * <tx_blob> [fail_hard]
     *
     * @param string $txBlob
     * @param bool $failHard
     * @return mixed|string
     */
    public function submit(string $txBlob, bool $failHard = false)
    {
        return $this->_call('submit', [$txBlob, $failHard]);
    }

    /**
     * Retrieve information about the public ledger.
     * [ledger_hash] [ledger_index] [full=false] [accounts=true] [transactions=true] [expand=false] [owner_funds=false] [binary=false] [queue=false]
     *
     * @param string $ledgerHash
     * @param string $ledgerIndex
     * @param bool $full
     * @param bool $accounts
     * @param bool $transactions
     * @param bool $expand
     * @param bool $ownerFunds
     * @param bool $binary
     * @param bool $queue
     * @return mixed|string
     */
    public function ledger(string $ledgerHash = '', string $ledgerIndex = '', bool $full = false, bool $accounts = false, bool $transactions = true, bool $expand = false, bool $ownerFunds = false, bool $binary = false, bool $queue = false)
    {
        return $this->_call('ledger', [$ledgerHash, $ledgerIndex, $full, $accounts, $transactions, $expand, $ownerFunds, $binary, $queue]);
    }

    /**
     * Use the wallet_propose method to generate a key pair and XRP Ledger address.
     * This command only generates key and address values, and does not affect
     * the XRP Ledger itself in any way. To become a funded address stored in the ledger,
     * the address must receive a Payment transaction that provides enough XRP
     * to meet the reserve requirement.
     *
     * The wallet_propose request is an admin method that cannot be run by
     * unprivileged users! (This command is restricted to protect against
     * people sniffing network traffic for account secrets, since admin
     * commands are not usually transmitted over the outside network.)
     *
     * @param array $data
     * with key type ['seed' => 'snoPBrXtMeMyMHUVTgbuqAfg1SUTb', 'key_type' => 'secp256k1']
     * no-key type ['passphrase' => 'snoPBrXtMeMyMHUVTgbuqAfg1SUTb']
     * @return mixed|string
     */
    public function walletPropose(array $data)
    {
        return $this->_call('wallet_propose', $data);
    }

    /**
     * The server_info command asks the server for a human-readable version
     * of various information about the rippled server being queried.
     *
     * @return mixed
     */
    public function serverInfo()
    {
        return $this->_call('server_info');
    }
}