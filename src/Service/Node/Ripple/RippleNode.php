<?php

namespace App\Service\Node\Ripple;

use App\Service\NodeDataManager;

class RippleNode
{
    private const URL = 'http://127.0.0.1:8545';
    private $rootWallet;
    private $dataManager;

    public function __construct(NodeDataManager $dataManager = null, ?string $rootWallet = null, $settings = null)
    {
        $this->dataManager = $dataManager;
        $this->rootWallet = $rootWallet;
    }

    public function _call(string $method, array $params = [])
    {
        $args = http_build_query($params);
        $url =  self::URL . "{$method}?{$args}";
        $data = @file_get_contents($url);
        return json_decode($data, true);
    }

    /**
     * Retrieve a specific Ledger by hash, index, date, or latest validated.
     * <ledger_identifier> [transactions=false] [binary=false] [expand=false]
     *
     * @param string $identifier
     * @param bool $transactions
     * @param bool $binary
     * @param bool $expand
     * @return mixed
     */
    public function getLedger(string $identifier, bool $transactions = false, bool $binary = false, bool $expand = false)
    {
        return $this->_call("/v2/ledgers/{$identifier}", [$transactions, $binary, $expand]);
    }

    /**
     * Retrieve a any validations recorded for a specific ledger hash.
     * This dataset includes ledger versions that are outside
     * the validated ledger chain. (New in v2.2.0)
     * <ledger_hash> [limit=200] [marker] [format=json]
     *
     * @param string $ledgerHash
     * @param int $limit
     * @param string $marker
     * @param string $format
     * @return mixed
     */
    public function getLedgerValidations(string $ledgerHash, int $limit = 200, string $marker = '', string $format = 'json')
    {
        return $this->_call("/v2/ledgers/{$ledgerHash}/validations", [$limit, $marker, $format]);
    }

    /**
     * Retrieve a validation vote recorded for a specific ledger
     * hash by a specific validator. This dataset includes ledger
     * versions that are outside the validated ledger chain. (New in v2.2.0)
     * <ledger_hash> <pubkey>
     *
     * @param string $ledgerHash
     * @param string $pubKey
     * @return mixed
     */
    public function getLedgerValidation(string $ledgerHash, string $pubKey)
    {
        return $this->_call("/v2/ledgers/{$ledgerHash}/validations/{$pubKey}", []);
    }

    /**
     * Retrieve a specific transaction by its identifying hash.
     * <hash> [binary=false]
     *
     * @param string $hash
     * @param bool $binary
     * @return mixed
     */
    public function getTransaction(string $hash, bool $binary = false)
    {
        return $this->_call("/v2/transactions/{$hash}", [$binary]);
    }

    /**
     * Retrieve transactions by time
     * [start] [end] [descending=false] [type] [result] [binary=false] [limit=20] [marker]
     *
     * @param string $start
     * @param string $end
     * @param bool $descending
     * @param string $type
     * @param string $result
     * @param bool $binary
     * @param int $limit
     * @param string $marker
     * @return mixed
     */
    public function getTransactions(string $start = '', string $end = '', bool $descending = false, string $type = '', string $result = '', bool $binary = false, int $limit = 20, string $marker = '')
    {
        return $this->_call('/v2/transactions/', [$start, $end, $descending, $type, $result, $binary, $limit, $marker]);
    }

    /**
     * Retrieve Payments over time, where Payments are defined as Payment type
     * transactions where the sender of the transaction is not also the destination.
     * (New in v2.0.4)
     *
     * Results can be returned as individual payments, or aggregated
     * to a specific list of intervals if currency and issuer are provided.
     * <currency> [start] [end] [descending=false] [limit=200] [marker] [format=json]
     *
     * @param string $currency
     * @param string $start
     * @param string $end
     * @param bool $descending
     * @param int $limit
     * @param string $marker
     * @param string $format
     * @return mixed
     */
    public function getPayments(string $currency = '', string $start = '', string $end = '', bool $descending = false, int $limit = 200, string $marker = '', string $format = 'json')
    {
        return $this->_call("/v2/payments/{$currency}", [$start, $end, $descending, $limit, $marker, $format]);
    }

    /**
     * Retrieve Exchanges for a given currency pair over time.
     * Results can be returned as individual exchanges or aggregated
     * to a specific list of intervals
     * <base> <counter> [start] [end] [interval] [descending=false] [reduce=false] [limit=200] [marker] [autobridged] [format=json]
     *
     * @param string $base
     * @param string $counter
     * @param string $start
     * @param string $end
     * @param string $interval
     * @param bool $descending
     * @param bool $reduce
     * @param int $limit
     * @param string $marker
     * @param bool $autoBridged
     * @param string $format
     * @return mixed
     */
    public function getExchanges(string $base, string $counter, string $start = '', string $end = '', string $interval = '', bool $descending = false, bool $reduce = false, int $limit = 200, string $marker = '', bool $autoBridged = true, string $format = 'json')
    {
        return $this->_call("/v2/exchanges/{$base}/{$counter}", [$start, $end, $interval, $descending, $reduce, $limit, $marker, $autoBridged, $format]);
    }

    /**
     * Retrieve an exchange rate for a given currency pair at a specific time.
     * <base> <counter> [date] [strict=true]
     *
     * @param string $base
     * @param string $counter
     * @param string $date
     * @param bool $strict
     * @return mixed
     */
    public function getExchangeRates(string $base, string $counter, string $date = '', bool $strict = true)
    {
        return $this->_call("/v2/exchange_rates/{$base}/{$counter}", [$date, $strict]);
    }

    /**
     * Convert an amount from one currency and issuer to another,
     * using the network exchange rates.
     * [amount] [currency] [issuer] [exchange_currency] [exchange_issuer] [date] [strict=false]
     *
     * @param float $amount
     * @param string $currency
     * @param string $issuer
     * @param string $exchangeCurrency
     * @param string $exchangeIssuer
     * @param string $date
     * @param bool $strict
     * @return mixed
     */
    public function normalize(float $amount = 0, string $currency = '', string $issuer = '', string $exchangeCurrency = '', string $exchangeIssuer = '', string $date = '', bool $strict = false)
    {
        return $this->_call('/v2/normalize', [$amount, $currency, $issuer, $exchangeCurrency, $exchangeIssuer, $date, $strict]);
    }

    /**
     * Retrieve per account per day aggregated payment summaries
     * <date> [accounts=false] [payments=false] [format=json] [limit=200] [marker]
     *
     * @param string $date
     * @param bool $accounts
     * @param bool $payments
     * @param string $format
     * @param int $limit
     * @param string $marker
     * @return mixed
     */
    public function getDailyReports(string $date, bool $accounts = false, bool $payments = false, string $format = 'json', int $limit = 200, string $marker = '')
    {
        return $this->_call("/v2/reports/{$date}", [$accounts, $payments, $format, $limit, $marker]);
    }

    /**
     * Retrieve statistics about transaction activity in the XRP Ledger,
     * divided into intervals of time.
     * [family] [metrics] [start] [end] [interval=day] [limit=200] [marker] [descending=false] [format=json]
     *
     * @param string $family
     * @param string $metrics
     * @param string $start
     * @param string $end
     * @param string $interval
     * @param int $limit
     * @param string $marker
     * @param bool $descending
     * @param string $format
     * @return mixed
     */
    public function getStats(string $family = '', string $metrics = '', string $start = '', string $end = '', string $interval = 'day', int $limit = 200, string $marker = '', bool $descending = false, string $format = 'json')
    {
        return $this->_call('/v2/stats', [$family, $metrics, $start, $end, $interval, $limit, $marker, $descending, $format]);
    }

    /**
     * Get information on which accounts are actively trading in a
     * specific currency pair.
     * <base> <counter> [period=1day] [date] [include_exchanges=false] [format=json]
     *
     * @param string $base
     * @param string $counter
     * @param string $period
     * @param string $date
     * @param bool $includeExchanges
     * @param string $format
     * @return mixed
     */
    public function getActiveAccounts(string $base, string $counter, string $period = '1day', string $date = '', bool $includeExchanges = false, string $format = 'json')
    {
        return $this->_call("/v2/active_accounts/{$base}/{$counter}", [$period, $date, $includeExchanges, $format]);
    }

    /**
     * Get aggregated exchange volume for a given time period. (New in v2.0.4)
     * [live] [exchange_currency] [exchange_issuer] [format=json]
     *
     * @param string $live
     * @param string $exchangeCurrency
     * @param string $exchangeIssuer
     * @param string $format
     * @return mixed
     */
    public function getExchangeVolume(string $live = '', string $exchangeCurrency = '', string $exchangeIssuer = '', string $format = 'json')
    {
        return $this->_call('/v2/network/exchange_volume', [$live, $exchangeCurrency, $exchangeIssuer, $format]);
    }

    /**
     * Get aggregated payment volume for a given time period. (New in v2.0.4)
     * [live] [exchange_currency] [exchange_issuer] [format=json]
     *
     * @param string $live
     * @param string $exchangeCurrency
     * @param string $exchangeIssuer
     * @param string $format
     * @return mixed
     */
    public function getPaymentVolume(string $live = '', string $exchangeCurrency = '', string $exchangeIssuer = '', string $format = 'json')
    {
        return $this->_call('/v2/network/payment_volume', [$live, $exchangeCurrency, $exchangeIssuer, $format]);
    }

    /**
     * Get aggregated exchange volume from a list of off ledger exchanges for a specified rolling interval.
     * [period=1day] [exchange_currency] [exchange_issuer]
     *
     * @param string $period
     * @param string $exchangeCurrency
     * @param string $exchangeIssuer
     * @return mixed
     */
    public function getExternalMarkets(string $period = '1day', string $exchangeCurrency = '', string $exchangeIssuer = '')
    {
        return $this->_call('/v2/network/external_markets', [$period, $exchangeCurrency, $exchangeIssuer]);
    }

    /**
     * Get information on the total amount of XRP in existence and in circulation,
     * by weekly intervals. (New in v2.2.0)
     * [start] [end] [limit=200] [marker] [descending=false] [format=json]
     *
     * @param string $start
     * @param string $end
     * @param int $limit
     * @param string $marker
     * @param bool $descending
     * @param string $format
     * @return mixed
     */
    public function getXRPDistribution(string $start = '', string $end = '', int $limit = 200, string $marker = '', bool $descending = false, string $format = 'json')
    {
        return $this->_call('/v2/network/xrp_distribution', [$start, $end, $limit, $marker, $descending, $format]);
    }

    /**
     * Returns the top currencies on the XRP Ledger, ordered from highest rank to
     * lowest. The ranking is determined by the volume and count of transactions
     * and the number of unique counterparties. By default, returns results
     * for the 30-day rolling window ending on the current date.
     * You can specify a date to get results for the 30-day window ending
     * on that date. (New in v2.1.0)
     * [date] [limit=1000] [format=json]
     *
     * @param string $date
     * @param int $limit
     * @param string $format
     * @return mixed
     */
    public function getTopCurrencies(string $date = '', int $limit = 1000, string $format = 'json')
    {
        return $this->_call("/v2/network/top_currencies/{$date}", [$limit, $format]);
    }

    /**
     * Returns the top exchange markets on the XRP Ledger, ordered from highest
     * rank to lowest. The rank is determined by the number and volume of exchanges
     * and the number of counterparties participating. By default, returns top markets
     * for the 30-day rolling window ending on the current date. You can specify
     * a date to get results for the 30-day window ending on that date. (New in v2.1.0)
     * [date] [limit=1000] [format=json]
     *
     * @param string $date
     * @param int $limit
     * @param string $format
     * @return mixed
     */
    public function getTopMarkets(string $date = '', int $limit = 1000, string $format = 'json')
    {
        return $this->_call("/v2/network/top_markets/{$date}", [$limit, $format]);
    }

    /**
     * Returns transaction cost stats per ledger, hour, or day.
     * The data shows the average, minimum, maximum, and total transaction
     * costs paid for the given interval or ledger. (New in v2.2.0)
     * [start] [end] [interval=ledger] [descending=false] [limit=200] [marker] [format=json]
     *
     * @param string $start
     * @param string $end
     * @param string $interval
     * @param bool $descending
     * @param int $limit
     * @param string $marker
     * @param string $format
     * @return mixed
     */
    public function getTransactionCosts(string $start = '', string $end = '', string $interval = 'ledger', bool $descending = false, int $limit = 200, string $marker = '', string $format = 'json')
    {
        return $this->_call('/v2/network/fees', [$start, $end, $interval, $descending, $limit, $marker, $format]);
    }

    /**
     * Returns snapshots of the metrics derived from rippled's fee command. (New in v2.3.2)
     * [start] [end] [interval=day] [descending=false] [limit=200] [marker] [format=json]
     *
     * @param string $start
     * @param string $end
     * @param string $interval
     * @param bool $descending
     * @param int $limit
     * @param string $marker
     * @param string $format
     * @return mixed
     */
    public function getFeeStats(string $start = '', string $end = '', string $interval = 'day', bool $descending = false, int $limit = 200, string $marker = '', string $format = 'json')
    {
        return $this->_call('/v2/network/fee_stats', [$start, $end, $interval, $descending, $limit, $marker, $format]);
    }

    /**
     * Get known rippled servers and peer-to-peer connections between them. (New in v2.2.0)
     * [date] [verbose=false]
     *
     * @param string $date
     * @param bool $verbose
     * @return mixed
     */
    public function getTopology(string $date = '', bool $verbose = false)
    {
        return $this->_call('/v2/network/topology', [$date, $verbose]);
    }

    /**
     * Get known rippled nodes. (This is a subset of the data returned by the
     * Get Topology method.) (New in v2.2.0)
     * [date] [verbose=false] [format=json]
     *
     * @param string $date
     * @param bool $verbose
     * @param string $format
     * @return mixed
     */
    public function getTopologyNodes(string $date = '', bool $verbose = false, string $format = 'json')
    {
        return $this->_call('/v2/network/topology/nodes', [$date, $verbose, $format]);
    }

    /**
     * Get information about a single rippled server by its node public key
     * (not validator public key). (New in v2.2.0)
     * <pubkey>
     *
     * @param string $pubKey
     * @return mixed
     */
    public function getTopologyNode(string $pubKey)
    {
        return $this->_call("/v2/network/topology/nodes/{$pubKey}");
    }

    /**
     * Get information on peer-to-peer connections between rippled servers.
     * (This is a subset of the data returned by the Get Topology method.)
     * (New in v2.2.0)
     * [date] [format=json]
     *
     * @param string $date
     * @param string $format
     * @return mixed
     */
    public function getTopologyLinks(string $date = '', string $format = 'json')
    {
        return $this->_call('/v2/network/topology/links', [$date, $format]);
    }

    /**
     * Get details of a single validator in the consensus network. (New in v2.2.0)
     * <pubkey> [format=json]
     *
     * @param string $pubKey
     * @param string $format
     * @return mixed
     */
    public function getValidator(string $pubKey, string $format = 'json')
    {
        return $this->_call("/v2/network/validators/{$pubKey}", [$format]);
    }

    /**
     * Get a list of known validators. (New in v2.2.0)
     * [format=json]
     *
     * @param string $format
     * @return mixed
     */
    public function getValidators(string $format = 'json')
    {
        return $this->_call('/v2/network/validators', [$format]);
    }

    /**
     * Retrieve validation votes signed by a specified validator,
     * including votes for ledger versions that are outside the main
     * ledger chain. (New in v2.2.0)
     * <pubkey> [start] [end] [limit=200] [marker] [format=json]
     *
     * @param string $pubKey
     * @param string $start
     * @param string $end
     * @param int $limit
     * @param string $marker
     * @param string $format
     * @return mixed
     */
    public function getValidatorValidations(string $pubKey, string $start = '', string $end = '', int $limit = 200, string $marker = '', string $format = 'json')
    {
        return $this->_call("/v2/network/validators/{$pubKey}/validations", [$start, $end, $limit, $marker, $format]);
    }

    /**
     * Retrieve validation votes, including votes for ledger versions
     * that are outside the main ledger chain. (New in v2.2.0)
     * [start] [end] [limit=200] [marker] [format=json] [descending=false]
     *
     * @param string $start
     * @param string $end
     * @param int $limit
     * @param string $marker
     * @param string $format
     * @param bool $descending
     * @return mixed
     */
    public function getValidations(string $start = '', string $end = '', int $limit = 200, string $marker = '', string $format = 'json', bool $descending = false)
    {
        return $this->_call('/v2/network/validations', [$start, $end, $limit, $marker, $format, $descending]);
    }

    /**
     * Get a single validator's validation vote stats for 24-hour intervals.
     * <pubkey> [start] [end] [format=json]
     *
     * @param string $pubKey
     * @param string $start
     * @param string $end
     * @param string $format
     * @return mixed
     */
    public function getSingleValidatorReports(string $pubKey, string $start = '', string $end = '', string $format = 'json')
    {
        return $this->_call("/v2/network/validators/{$pubKey}/reports", [$start, $end, $format]);
    }

    /**
     * Get a validation vote stats and validator information for all known validators in a 24-hour period.
     * [date] [format=json]
     *
     * @param string $date
     * @param string $format
     * @return mixed
     */
    public function getDailyValidatorReports(string $date = '', string $format = 'json')
    {
        return $this->_call('/v2/network/validator_reports', [$date, $format]);
    }

    /**
     * Reports the latest versions of rippled available from the official
     * Ripple Yum repositories. (New in v2.3.0.)
     *
     * @return mixed
     */
    public function getRippledVersions()
    {
        return $this->_call('/v2/network/rippled_versions');
    }

    /**
     * Get information about known gateways. (New in v2.0.4)
     *
     * @return mixed
     */
    public function getAllGateways()
    {
        return $this->_call('/v2/gateways/');
    }

    /**
     * Get information about a specific gateway from the Data API's
     * list of known gateways. (New in v2.0.4)
     * <gateway>
     *
     * @param string $gateway
     * @return mixed
     */
    public function getGateway(string $gateway)
    {
        return $this->_call("/v2/gateways/{$gateway}");
    }

    /**
     * Retrieve vector icons for various currencies. (New in v2.0.4)
     *
     * @param string $currencyImage
     * @return mixed
     */
    public function getCurrencyImage(string $currencyImage)
    {
        return $this->_call("/v2/currencies/{$currencyImage}");
    }

    /**
     * Retrieve information about the creation of new accounts in the XRP Ledger.
     * [start] [end] [limit=200] [marker] [descending=false] [parent] [format=json]
     *
     * @param string $start
     * @param string $end
     * @param int $limit
     * @param string $marker
     * @param bool $descending
     * @param string $parent
     * @param string $format
     * @return mixed
     */
    public function getAccounts(string $start = '', string $end = '', int $limit = 200, string $marker = '', bool $descending = false, string $parent = '', string $format = 'json')
    {
        return $this->_call('/v2/accounts', [$start, $end, $limit, $marker, $descending, $parent, $format]);
    }

    /**
     * Get creation info for a specific ripple account
     * <address>
     *
     * @param string $address
     * @return mixed
     */
    public function getAccount(string $address)
    {
        return $this->_call("/v2/accounts/{$address}");
    }

    /**
     * Get all balances held or owed by a specific XRP Ledger account.
     * <address> [ledger_index] [ledger_hash] [date] [currency] [counterparty] [limit=200] [format=json]
     *
     * @param string $address
     * @param string $ledgerIndex
     * @param string $ledgerHash
     * @param string $date
     * @param string $currency
     * @param string $counterParty
     * @param int $limit
     * @param string $format
     * @return mixed
     */
    public function getAccountBalances(string $address, string $ledgerIndex = '', string $ledgerHash = '', string $date = '', string $currency = '', string $counterParty = '', int $limit = 200, string $format = 'json')
    {
        return $this->_call("/v2/accounts/{$address}/balances", [$date, $ledgerIndex, $ledgerHash, $currency, $counterParty, $limit, $format]);
    }

    /**
     * Get orders in the order books, placed by a specific account.
     * This does not return orders that have already been filled.
     *
     * @param string $address
     * @param string $ledgerIndex
     * @param string $ledgerHash
     * @param string $date
     * @param int $limit
     * @param string $format
     * @return mixed
     */
    public function getAccountOrders(string $address, string $ledgerIndex = '', string $ledgerHash = '', string $date = '', int $limit = 200, string $format = 'json')
    {
        return $this->_call("/v2/account/{$address}/orders", [$ledgerIndex, $ledgerHash, $date, $limit, $format]);
    }

    /**
     * Retrieve a history of transactions that affected a specific account.
     * This includes all transactions the account sent,
     * payments the account received, and payments that rippled through the account.
     * <address> [start] [end] [min_sequence] [max_sequence] [type] [result] [binary] [descending] [limit] [marker]
     *
     * @param string $address
     * @param string $start
     * @param string $end
     * @param string $minSequence
     * @param string $maxSequence
     * @param string $type
     * @param string $result
     * @param bool $binary
     * @param bool $descending
     * @param int $limit
     * @param string $marker
     * @return mixed
     */
    public function getAccountTransactionHistory(string $address, string $start = '', string $end = '', string $minSequence = '', string $maxSequence = '', string $type = '', string $result = '', bool $binary = false, bool $descending = false, int $limit = 20, string $marker = '')
    {
        return $this->_call("/v2/accounts/{$address}/transactions", [$start, $end, $minSequence, $maxSequence, $type, $result, $binary, $descending, $limit, $marker]);
    }

    /**
     * Retrieve a specific transaction originating from a specified account
     * <address> <sequence> [binary=false]
     *
     * @param string $address
     * @param int $sequence
     * @param bool $binary
     * @return mixed
     */
    public function getTransactionByAccountAndSequence(string $address, int $sequence, bool $binary = false)
    {
        return $this->_call("/v2/accounts/{$address}/transactions/{$sequence}", [$binary]);
    }

    /**
     * Retrieve a payments for a specified account
     * <address> [start] [end] [type] [currency] [issuer] [source_tag] [destination_tag] [limit=200] [marker] [format=json]
     *
     * @param string $address
     * @param string $start
     * @param string $end
     * @param string $type
     * @param string $currency
     * @param string $issuer
     * @param int $sourceTag
     * @param int $destinationTag
     * @param int $limit
     * @param string $marker
     * @param string $format
     * @return mixed
     */
    public function getAccountPayments(string $address, string $start = '', string $end = '', string $type = '', string $currency = '', string $issuer = '', int $sourceTag = 0, int $destinationTag = 0, int $limit = 200, string $marker = '', string $format = 'json')
    {
        return $this->_call("/v2/accounts/{$address}/payments", [$start, $end, $type, $currency, $issuer, $sourceTag, $destinationTag, $limit, $marker, $format]);
    }

    /**
     * Retrieve Exchanges for a given account over time.
     * <address> [start] [end] [descending=false] [limit=200] [marker] [format=json]
     *
     * @param string $address
     * @param string $start
     * @param string $end
     * @param bool $descending
     * @param int $limit
     * @param string $marker
     * @param string $format
     * @return mixed
     */
    public function getAllAccountExchanges(string $address, string $start = '', string $end = '', bool $descending = false, int $limit = 200, string $marker = '', string $format = 'json')
    {
        return $this->_call("/v2/accounts/{$address}/exchanges/", [$start, $end, $descending, $limit, $marker, $format]);
    }

    /**
     * Retrieve Exchanges for a given account over time.
     * <address> <base> <counter> [start] [end] [descending=false] [limit=200] [marker] [format=json]
     *
     * @param string $address
     * @param string $base
     * @param string $counter
     * @param string $start
     * @param string $end
     * @param bool $descending
     * @param int $limit
     * @param string $marker
     * @param string $format
     * @return mixed
     */
    public function getAccountExchanges(string $address, string $base, string $counter, string $start = '', string $end = '', bool $descending = false, int $limit = 200, string $marker = '', string $format = 'json')
    {
        return $this->_call("/v2/accounts/{$address}/exchanges/{$base}/{$counter}", [$start, $end, $descending, $limit, $marker, $format]);
    }

    /**
     * Retrieve Balance changes for a given account over time.
     * <address> [currency] [counterparty] [start] [end] [descending=false] [limit=200] [marker] [format=json]
     *
     * @param string $address
     * @param string $currency
     * @param string $counterParty
     * @param string $start
     * @param string $end
     * @param bool $descending
     * @param int $limit
     * @param string $marker
     * @param string $format
     * @return mixed
     */
    public function getAccountBalanceChanges(string $address, string $currency = '', string $counterParty = '', string $start = '', string $end = '', bool $descending = false, int $limit = 200, string $marker = '', string $format = 'json')
    {
        return $this->_call("/v2/accounts/{$address}/balance_changes/", [$currency, $counterParty, $start, $end, $descending, $limit, $marker, $format]);
    }

    /**
     * Retrieve daily summaries of payment activity for an account.
     * <address> <date> [start] [end] [accounts=false] [payments=false] [descending=false] [format=json]
     *
     * @param string $address
     * @param string $date
     * @param string $start
     * @param string $end
     * @param bool $accounts
     * @param bool $payments
     * @param bool $descending
     * @param string $format
     * @return mixed
     */
    public function getAccountReports(string $address, string $date = '', string $start = '', string $end = '', bool $accounts = false, bool $payments = false, bool $descending = false, string $format = 'json')
    {
        return $this->_call("/v2/accounts/{$address}/reports/{$date}", [$start, $end, $accounts, $payments, $descending, $format]);
    }

    /**
     * Retrieve daily summaries of transaction activity for an account. (New in v2.1.0.)
     * <address> [start] [end] [descending=false] [limit=200] [marker] [format=json]
     *
     * @param string $address
     * @param string $start
     * @param string $end
     * @param bool $descending
     * @param int $limit
     * @param string $marker
     * @param string $format
     * @return mixed
     */
    public function getAccountTransactionStats(string $address, string $start = '', string $end = '', int $limit = 200, bool $descending = false, string $marker = '', string $format = 'json')
    {
        return $this->_call("/v2/accounts/{$address}/stats/transactions", [$start, $end, $limit, $descending, $marker, $format]);
    }

    /**
     * Retrieve daily summaries of transaction activity for an account. (New in v2.1.0.)
     * <address> [start] [end] [limit=200] [marker] [descending=false] [format=json]
     *
     * @param string $address
     * @param string $start
     * @param string $end
     * @param int $limit
     * @param string $marker
     * @param bool $descending
     * @param string $format
     * @return mixed
     */
    public function getAccountValueStats(string $address, string $start = '', string $end = '', int $limit = 200, string $marker = '', bool $descending = false, string $format = 'json')
    {
        return $this->_call("/v2/accounts/{$address}/stats/value", [$start, $end, $limit, $marker, $descending, $format]);
    }
}