<?php

namespace App\Service\Node;

abstract class BaseNode
{
    private const HTTP_ERRORS = [
        400 => '400 Bad Request',
        401 => '401 Unauthorized',
        403 => '403 Forbidden',
        404 => '404 Not Found',
        405 => '405 Method Not Allowed',
        406 => '406 Not Acceptable',
        408 => '408 Request Timeout',
        500 => '500 Internal Server Error',
        502 => '502 Bad Gateway',
        503 => '503 Service Unavailable'
    ];

    private $id = 0;
    private $host;
    protected $response;

    public function __construct($host)
    {
        $this->host = $host;
    }

    public function __call(string $method, array $params = [])
    {
        // The ID should be unique for each call
        $this->id++;
        $this->response = null;

        $request = json_encode([
            'jsonrpc' => '2.0',
            'method' => $method,
            'params' => array_values($params),
            'id' => $this->id,
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->host);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: text/plain']);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt_array($ch, [CURLOPT_CONNECTTIMEOUT => 8, CURLOPT_TIMEOUT => 8]);

        $this->response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (isset($this->httpErrors[$httpCode])) {
            return 'Response Http Error - ' . self::HTTP_ERRORS[$httpCode];
        }
        if (0 < curl_errno($ch)) {
            return 'Unable to connect to ' . $this->host . '. Error: ' . curl_error($ch);
        }
        curl_close($ch);

        return $this->getResponse();
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        if ($this->response == null) {
            return '';
        }
        $data = json_decode($this->response, true);
        if (isset($data['error']) && $data['error'] != '') {
            return $data['error']['message'];
        }
        return $data['result'] ?? '';
    }
}