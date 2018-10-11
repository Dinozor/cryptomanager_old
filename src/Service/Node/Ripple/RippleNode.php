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
     *
     * @return mixed
     */
    public function accountChannels()
    {
        return $this->_call('account_channels', []);
    }

    /**
     * Get a list of currencies an account can send or receive.
     *
     * @return mixed
     */
    public function accountCurrencies()
    {
        return $this->_call('account_currencies', []);
    }

    /**
     * Get basic data about an account.
     *
     * @return mixed
     */
    public function accountInfo()
    {
        return $this->_call('account_info', []);
    }

    /**
     * Get info about an account's trust lines.
     *
     * @return mixed
     */
    public function accountLines()
    {
        return $this->_call('account_lines', []);
    }

    /**
     * Get all ledger objects owned by an account.
     *
     * @return mixed
     */
    public function accountObjects()
    {
        return $this->_call('account_objects', []);
    }

    /**
     * Get info about an account's currency exchange offers.
     *
     * @return mixed
     */
    public function accountOffers()
    {
        return $this->_call('account_offers', []);
    }

    /**
     * Get info about an account's transactions.
     *
     * @return mixed
     */
    public function accountTx()
    {
        return $this->_call('account_tx', []);
    }

    /**
     * Calculate total amounts issued by an account.
     *
     * @return mixed
     */
    public function gatewayBalances()
    {
        return $this->_call('gateway_balances', []);
    }

    /**
     * Get recommended changes to an account's DefaultRipple and NoRipple settings.
     *
     * @return mixed
     */
    public function norippleCheck()
    {
        return $this->_call('noripple_check');
    }

    /**
     * Cryptographically sign a transaction.
     *
     * @return mixed
     */
    public function sign()
    {
        return $this->_call('sign', []);
    }

    /**
     * Contribute to a multi-signature.
     *
     * @return mixed
     */
    public function signFor()
    {
        return $this->_call('sign_for');
    }

    /**
     * Send a transaction to the network.
     *
     * @return mixed
     */
    public function submit()
    {
        return $this->_call('submit');
    }

    /**
     * Send a multi-signed transaction to the network.
     *
     * @return mixed
     */
    public function submitMultisigned()
    {
        return $this->_call('submit_multisigned');
    }

    /**
     * Retrieve info about a transaction from a particular ledger version.
     *
     * @return mixed
     */
    public function transactionEntry()
    {
        return $this->_call('transaction_entry');
    }

    /**
     * Retrieve info about a transaction from all the ledgers on hand.
     *
     * @return mixed
     */
    public function tx()
    {
        return $this->_call('tx');
    }

    /**
     * Retrieve info about all recent transactions.
     *
     * @return mixed
     */
    public function txHistory()
    {
        return $this->_call('tx_history');
    }

    /**
     * Get info about offers to exchange two currencies.
     *
     * @return mixed
     */
    public function bookOffers()
    {
        return $this->_call('book_offers');
    }

    /**
     * Look up whether one account is authorized to send payments directly to another.
     *
     * @return mixed
     */
    public function depositAuthorized()
    {
        return $this->_call('deposit_authorized');
    }

    /**
     * Find a path for a payment between two accounts and receive updates.
     *
     * @return mixed
     */
    public function pathFind()
    {
        return $this->_call('path_find');
    }

    /**
     * Find a path for payment between two accounts, once.
     *
     * @return mixed
     */
    public function ripplePathFind()
    {
        return $this->_call('ripple_path_find');
    }

    /**
     * Sign a claim for money from a payment channel.
     *
     * @return mixed
     */
    public function channelAuthorize()
    {
        return $this->_call('channel_authorize');
    }

    /**
     * Check a payment channel claim's signature.
     *
     * @return mixed
     */
    public function channelVerify()
    {
        return $this->_call('channel_verify');
    }

    /**
     * Listen for updates about a particular subject.
     *
     * @return mixed
     */
    public function subscribe()
    {
        return $this->_call('subscribe');
    }

    /**
     * Stop listening for updates about a particular subject.
     *
     * @return mixed
     */
    public function unsubscribe()
    {
        return $this->_call('unsubscribe');
    }

    /**
     * Get information about transaction cost.
     *
     * @return mixed
     */
    public function fee()
    {
        return $this->_call('fee');
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
     * Use as a proxy to running other commands.
     * Accepts the parameters for the command as a JSON value.
     * Commandline only.
     *
     * @return mixed
     */
    public function json()
    {
        return $this->_call('json');
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
}