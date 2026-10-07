<?php

namespace RandomArrayConsumer\Service;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class HttpClientService
{
    private Client $httpClient;

    public function __construct(private string $baseUri)
    {
        $this->httpClient = new Client();
    }

    /**
     * @param array<string, mixed> $body
     */
    public function createOrder(array $body): ResponseInterface
    {
        return $this->httpClient->post("{$this->baseUri}/orders", [
            'headers' => ['Accept' => 'application/json'],
            'json' => $body,
            'http_errors' => false,
        ]);
    }
}
