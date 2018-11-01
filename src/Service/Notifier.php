<?php

namespace App\Service;

class Notifier
{
    private $currencyCode;

    public function __construct(string $currency)
    {
        $this->currencyCode = $currency;
    }

    public function notify(array $transactions)
    {
        if (\count($transactions) == 0) {
            return null;
        }

        $data = ['currency' => $this->currencyCode, 'transactions' => $transactions];
        $url = getenv('IWALLET_API') . '/api/cryptomanager/transactions/add?api_key=' . getenv('API_KEY');
        $options = ['http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        ]];
        return @file_get_contents($url, false, stream_context_create($options));
    }
}