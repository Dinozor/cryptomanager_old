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
        parent::__construct('test', '123456');
        $this->dataManager = $dataManager;
        $this->rootWallet = $rootWallet;
    }

    /**
     * Get a list of payment channels where the account is the source of the channel.
     * <account> [destination_account] [ledger_hash] [ledger_index] [limit=200]
     *
     * @param string $account
     * @param string $destinationAccount
     * @param string $ledgerHash
     * @param string $ledgerIndex
     * @param int $limit
     * @return mixed
     */
    public function accountChannels(string $account, string $destinationAccount = '', string $ledgerHash = '', string $ledgerIndex = '', int $limit = 200)
    {
        return $this->_call('account_channels', [$account, $destinationAccount, $ledgerHash, $ledgerIndex, $limit]);
    }

    /**
     * Get a list of currencies an account can send or receive.
     * <account> [strict=false] [ledger_hash] [ledger_index]
     *
     * @param string $account
     * @param bool $strict
     * @param string $ledgerHash
     * @param string $ledgerIndex
     * @return mixed
     */
    public function accountCurrencies(string $account, bool $strict = false, string $ledgerHash = '', string $ledgerIndex = '')
    {
        return $this->_call('account_currencies', [$account, $strict, $ledgerHash, $ledgerIndex]);
    }

    /**
     * Get basic data about an account.
     * [account] [strict=false] [ledger_hash] [ledger_index] [queue=true] [signer_lists=true]
     *
     * @param string $account
     * @param bool $strict
     * @param string $ledgerHash
     * @param string $ledgerIndex
     * @param bool $queue
     * @param bool $signerLists
     * @return mixed
     */
    public function accountInfo(string $account, bool $strict = false, string $ledgerHash = '', string $ledgerIndex = '', bool $queue = true, bool $signerLists = true)
    {
        return $this->_call('account_info', [$account, $strict, $ledgerHash, $ledgerIndex, $queue, $signerLists]);
    }

    /**
     * Get info about an account's trust lines.
     * <account> [ledger_hash] [ledger_index] [peer] [limit=200]
     *
     * @param string $account
     * @param string $ledgerHash
     * @param string $ledgerIndex
     * @param string $peer
     * @param int $limit
     * @return mixed
     */
    public function accountLines(string $account, string $ledgerHash = '', string $ledgerIndex = '', string $peer = '', int $limit = 200)
    {
        return $this->_call('account_lines', [$account, $ledgerHash, $ledgerIndex, $peer, $limit]);
    }

    /**
     * Get all ledger objects owned by an account.
     * <account> <type> [ledger_hash] [ledger_index] [limit=200]
     *
     * @param string $account
     * @param string $type - check, deposit_preauth, escrow, offer, payment_channel, signer_list, state
     * @param string $ledgerHash
     * @param string $ledgerIndex
     * @param int $limit
     * @return mixed
     */
    public function accountObjects(string $account, string $type, string $ledgerHash = '', string $ledgerIndex = '', int $limit = 200)
    {
        return $this->_call('account_objects', [$account, $type, $ledgerHash, $ledgerIndex, $limit]);
    }

    /**
     * Get info about an account's currency exchange offers.
     * <account> [ledger] [ledger_hash] [ledger_index] [limit=200]
     *
     * @param string $account
     * @param string $ledger
     * @param string $ledgerHash
     * @param string $ledgerIndex
     * @param int $limit
     * @return mixed
     */
    public function accountOffers(string $account, string $ledger = '', string $ledgerHash = '', string $ledgerIndex = '', int $limit = 200)
    {
        return $this->_call('account_offers', [$account, $ledger, $ledgerHash, $ledgerIndex, $limit]);
    }

    /**
     * Get info about an account's transactions.
     * <account> [ledger_index_min=-1] [ledger_index_max=-1] [ledger_hash] [ledger_index] [binary=false] [forward=false] [limit=200]
     *
     * @param string $account
     * @param int $ledgerIndexMin
     * @param int $ledgerIndexMax
     * @param string $ledgerHash
     * @param string $ledgerIndex
     * @param bool $binary
     * @param bool $forward
     * @param int $limit
     * @return mixed
     */
    public function accountTx(string $account, int $ledgerIndexMin = -1, int $ledgerIndexMax = -1, string $ledgerHash = '', string $ledgerIndex = '', bool $binary = false, bool $forward = false, int $limit = 200)
    {
        return $this->_call('account_tx', [$account, $ledgerIndexMin, $ledgerIndexMax, $ledgerHash, $ledgerIndex, $binary, $forward, $limit]);
    }

    /**
     * Calculate total amounts issued by an account.
     * <account> [strict=false] [hotwallet] [ledger_hash] [ledger_index]
     *
     * @param string $account
     * @param bool $strict
     * @param array $hotWallet
     * @param string $ledgerHash
     * @param string $ledgerIndex
     * @return mixed
     */
    public function gatewayBalances(string $account, bool $strict = false, array $hotWallet = [], string $ledgerHash = '', string $ledgerIndex = '')
    {
        return $this->_call('gateway_balances', [$account, $strict, $hotWallet, $ledgerHash, $ledgerIndex]);
    }

    /**
     * Get recommended changes to an account's DefaultRipple and NoRipple settings.
     * <account> [role=user] [transactions=false] [limit=300] [ledger_hash] [ledger_index]
     *
     * @param string $account
     * @param string $role
     * @param bool $transactions
     * @param string $ledgerHash
     * @param string $ledgerIndex
     * @return mixed
     */
    public function noRippleCheck(string $account, string $role = 'user', bool $transactions = false, string $ledgerHash = '', string $ledgerIndex = '')
    {
        return $this->_call('noripple_check', [$account, $role, $transactions, $ledgerHash, $ledgerIndex]);
    }

    /**
     * Retrieve information about the public ledger.
     * [ledger_hash] [ledger_index] [full=false] [accounts=false] [transactions=false] [expand=false] [owner_funds=false] [binary=false] [queue=true]
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
     * @return mixed
     */
    public function ledger(string $ledgerHash = '', string $ledgerIndex = '', bool $full = false, bool $accounts = false, bool $transactions = false, bool $expand = false, bool $ownerFunds = false, bool $binary = false, bool $queue = true)
    {
        return $this->_call('ledger', [$ledgerHash, $ledgerIndex, $full, $accounts, $transactions, $expand, $ownerFunds, $binary, $queue]);
    }

    /**
     * Get the latest closed ledger version.
     *
     * @return mixed
     */
    public function ledgerClosed()
    {
        return $this->_call('ledger_closed');
    }

    /**
     * Get the current working ledger version.
     *
     * @return mixed
     */
    public function ledgerCurrent()
    {
        return $this->_call('ledger_current');
    }

    /**
     * Get the raw contents of a ledger version.
     * [ledger_hash] [ledger_index] [binary=false] [limit=200]
     *
     * @param string $ledgerHash
     * @param string $ledgerIndex
     * @param bool $binary
     * @param int $limit
     * @return mixed
     */
    public function ledgerData(string $ledgerHash = '', string $ledgerIndex = '', bool $binary = false, int $limit = 200)
    {
        return $this->_call('ledger_data', [$ledgerHash, $ledgerIndex, $binary, $limit]);
    }

    /**
     * Get one element from a ledger version.
     * [index] [account_root] [check] [deposit_preauth] [directory] [escrow] [offer] [payment_channel] [ripple_state] [binary=true] [ledger_hash] [ledger_index]
     *
     * @param string $index
     * @param string $accountRoot
     * @param string $check
     * @param array $depositPreAuth
     * @param array $directory
     * @param array $escrow
     * @param array $offer
     * @param string $paymentChannel
     * @param array $rippleState
     * @param bool $binary
     * @param string $ledgerHash
     * @param string $ledgerIndex
     * @return mixed|string
     */
    public function ledgerEntry(string $index, string $accountRoot, string $check = '', array $depositPreAuth = [], array $directory = [], array $escrow = [], array $offer = [], string $paymentChannel = '', array $rippleState = [], bool $binary = true, string $ledgerHash = '', string $ledgerIndex = '')
    {
        return $this->_call('ledger_entry', [$index, $accountRoot, $check, $depositPreAuth, $directory, $escrow, $offer, $paymentChannel, $rippleState, $binary, $ledgerHash, $ledgerIndex]);
    }

    /**
     * Cryptographically sign a transaction.
     * <tx_json> [secret] [seed] [seed_hex] [passphrase] [key_type=secp256k1] [offline=false] [build_path=false] [fee_mult_max=10] [fee_div_max=1]
     *
     * @param array $txJson
     * @param string $secret
     * @param string $seed
     * @param string $seedHex
     * @param string $passPhrase
     * @param string $keyType
     * @param bool $offline
     * @param bool $buildPath
     * @param int $feeMultMax
     * @param int $feeDivMax
     * @return mixed
     */
    public function sign(array $txJson, string $secret = '', string $seed = '', string $seedHex = '', string $passPhrase = '', string $keyType = 'secp256k1', bool $offline = false, bool $buildPath = false, int $feeMultMax = 10, int $feeDivMax = 1)
    {
        return $this->_call('sign', [$txJson, $secret, $seed, $seedHex, $passPhrase, $keyType, $offline, $buildPath, $feeMultMax, $feeDivMax]);
    }

    /**
     * Contribute to a multi-signature.
     * <account> <tx_json> [secret] [seed] [seed_hex] [passphrase] [key_type=secp256k1]
     *
     * @param string $account
     * @param array $txJson
     * @param string $secret
     * @param string $seed
     * @param string $seedHex
     * @param string $passPhrase
     * @param string $keyType
     * @return mixed
     */
    public function signFor(string $account, array $txJson, string $secret = '', string $seed = '', string $seedHex = '', string $passPhrase = '', string $keyType = 'secp256k1')
    {
        return $this->_call('sign_for', [$account, $txJson, $secret, $seed, $seedHex, $passPhrase, $keyType]);
    }

    /**
     * Send a transaction to the network.
     * <tx_blob> [fail_hard=false]
     *
     * @param string $txBlob
     * @param bool $failHard
     * @return mixed
     */
    public function submit(string $txBlob, bool $failHard = false)
    {
        return $this->_call('submit', [$txBlob, $failHard]);
    }

    /**
     * Send a multi-signed transaction to the network.
     * <tx_blob> [fail_hard=false]
     *
     * @param string $txBlob
     * @param bool $failHard
     * @return mixed
     */
    public function submitMultiSigned(string $txBlob, bool $failHard = false)
    {
        return $this->_call('submit_multisigned', [$txBlob, $failHard]);
    }

    /**
     * Retrieve info about a transaction from a particular ledger version.
     * <tx_hash> [ledger_hash] [ledger_index]
     *
     * @param string $txHash
     * @param string $ledgerHash
     * @param string $ledgerIndex
     * @return mixed
     */
    public function transactionEntry(string $txHash, string $ledgerHash = '', string $ledgerIndex = '')
    {
        return $this->_call('transaction_entry', [$txHash, $ledgerHash, $ledgerIndex]);
    }

    /**
     * Retrieve info about a transaction from all the ledgers on hand.
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
     * Retrieve info about all recent transactions.
     * <start>
     *
     * @deprecated
     * @param int $start
     * @return mixed
     */
    public function txHistory(int $start)
    {
        return $this->_call('tx_history', [$start]);
    }

    /**
     * Get info about offers to exchange two currencies.
     * <taker_pays> <taker_gets> [ledger_hash] [ledger_index] [limit=200] [taker]
     *
     * @param array $takerPays
     * @param array $takerGets
     * @param string $ledgerHash
     * @param string $ledgerIndex
     * @param int $limit
     * @param string $taker
     * @return mixed
     */
    public function bookOffers(array $takerPays, array $takerGets, string $ledgerHash = '', string $ledgerIndex = '', int $limit = 200, string $taker = '')
    {
        return $this->_call('book_offers', [$takerPays, $takerGets, $ledgerHash, $ledgerIndex, $limit, $taker]);
    }

    /**
     * Look up whether one account is authorized to send payments directly to another.
     * <source_account> <destination_account> [ledger_hash] [ledger_index]
     *
     * @param string $sourceAccount
     * @param string $destinationAccount
     * @param string $ledgerHash
     * @param string $ledgerIndex
     * @return mixed
     */
    public function depositAuthorized(string $sourceAccount, string $destinationAccount, string $ledgerHash = '', string $ledgerIndex = '')
    {
        return $this->_call('deposit_authorized', [$sourceAccount, $destinationAccount, $ledgerHash, $ledgerIndex]);
    }

    /**
     * Find a path for payment between two accounts, once.
     * <source_account> <destination_account> [destination_amount] [send_max] [source_currencies] [ledger_hash] [ledger_index]
     *
     * @param string $sourceAccount
     * @param string $destinationAccount
     * @param string $destinationAmount
     * @param string $sendMax
     * @param array $sourceCurrencies
     * @param string $ledgerHash
     * @param string $ledgerIndex
     * @return mixed
     */
    public function ripplePathFind(string $sourceAccount, string $destinationAccount, string $destinationAmount = '', string $sendMax = '', array $sourceCurrencies = [], string $ledgerHash = '', string $ledgerIndex = '')
    {
        return $this->_call('ripple_path_find', [$sourceAccount, $destinationAccount, $destinationAmount, $sendMax, $sourceCurrencies, $ledgerHash, $ledgerIndex]);
    }

    /**
     * Sign a claim for money from a payment channel.
     * <channel_id> <secret> <amount>
     *
     * @param string $channelId
     * @param string $secret
     * @param string $amount
     * @return mixed
     */
    public function channelAuthorize(string $channelId, string $secret, string $amount)
    {
        return $this->_call('channel_authorize', [$channelId, $secret, $amount]);
    }

    /**
     * Check a payment channel claim's signature.
     * <amount> <channel_id> <public_key> <signature>
     *
     * @param string $amount
     * @param string $channelId
     * @param string $publicKey
     * @param string $signature
     * @return mixed
     */
    public function channelVerify(string $amount, string $channelId, string $publicKey, string $signature)
    {
        return $this->_call('channel_verify', [$amount, $channelId, $publicKey, $signature]);
    }

    /**
     * Listen for updates about a particular subject.
     * <url> [streams] [accounts] [accounts_proposed] [books] [url_username] [url_password]
     *
     * @param string $url
     * @param array $streams
     * @param array $accounts
     * @param array $accountsProposed
     * @param array $books
     * @param string $urlUsername
     * @param string $urlPassword
     * @return mixed
     */
    public function subscribe(string $url, array $streams = [], array $accounts = [], array $accountsProposed = [], array $books = [], string $urlUsername = '', string $urlPassword = '')
    {
        return $this->_call('subscribe', [$url, $streams, $accounts, $accountsProposed, $books, $urlUsername, $urlPassword]);
    }

    /**
     * Stop listening for updates about a particular subject.
     * [streams] [accounts] [accounts_proposed] [books]
     *
     * @param array $streams
     * @param array $accounts
     * @param array $accountsProposed
     * @param array $books
     * @return mixed
     */
    public function unsubscribe(array $streams = [], array $accounts = [], array $accountsProposed = [], array $books = [])
    {
        return $this->_call('unsubscribe', [$streams, $accounts, $accountsProposed, $books]);
    }

    /**
     * Get information about transaction cost.
     * <current_ledger_size> <current_queue_size> <drops> <expected_ledger_size> <ledger_current_index> <levels> <max_queue_size>
     *
     * @param int $currentLedgerSize
     * @param int $currentQueueSize
     * @param array $drops
     * @param int $expectedLedgerSize
     * @param float $ledgerCurrentIndex
     * @param array $levels
     * @param int $maxQueueSize
     * @return mixed
     */
    public function fee(int $currentLedgerSize, int $currentQueueSize, array $drops, int $expectedLedgerSize, float $ledgerCurrentIndex, array $levels, int $maxQueueSize)
    {
        return $this->_call('fee', [$currentLedgerSize, $currentQueueSize, $drops, $expectedLedgerSize, $ledgerCurrentIndex, $levels, $maxQueueSize]);
    }

    /**
     * Retrieve status of the server in human-readable format.
     *
     * @return mixed
     */
    public function serverInfo()
    {
        return $this->_call('server_info');
    }

    /**
     * Retrieve status of the server in machine-readable format.
     *
     * @return mixed
     */
    public function serverState()
    {
        return $this->_call('server_state');
    }

    /**
     * Confirm connectivity with the server.
     *
     * @return mixed
     */
    public function ping()
    {
        return $this->_call('ping');
    }

    /**
     * Generate a random number.
     *
     * @return mixed
     */
    public function random()
    {
        return $this->_call('random');
    }

    /**
     * Generate keys for a new account.
     * [key_type=secp256k1] [passphrase] [seed] [seed_hex]
     *
     * @param string $keyType
     * @param string $passPhrase
     * @param string $seed
     * @param string $seedHex
     * @return mixed|string
     */
    public function walletPropose(string $keyType = 'secp256k1', string $passPhrase = '', string $seed = '', string $seedHex = '')
    {
        return $this->_call('wallet_propose', [$keyType, $passPhrase, $seed, $seedHex]);
    }
}