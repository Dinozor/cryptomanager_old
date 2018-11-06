<?php

namespace App\Service;

class Notifier
{
    public function notifyAccount(string $currency, string $guid, int $balance, array $transactions)
    {
        if (\count($transactions) == 0) {
            return null;
        }

        $url = getenv('IWALLET_API') . '/api/cryptomanager/account/update?api_key=' . getenv('API_KEY');
        return $this->makeRequest($url, [
            'currency' => $currency,
            'balance' => $balance,
            'guid' => $guid,
            'transactions' => $transactions,
        ]);
    }

    public function notifyTransactions(array $transactions)
    {
        $url = getenv('IWALLET_API') . '/api/cryptomanager/transactions/add?api_key=' . getenv('API_KEY');
        return $this->makeRequest($url, $transactions);
    }

    private function makeRequest(string $url, array $data)
    {
        $options = ['http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        ]];
        return @file_get_contents($url, false, stream_context_create($options));
    }
}