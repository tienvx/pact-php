<?php

namespace FormUrlEncodedConsumer\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;

class HttpClientService
{
    private Client $httpClient;

    private string $baseUri;

    public function __construct(string $baseUri)
    {
        $this->httpClient = new Client();
        $this->baseUri    = $baseUri;
    }

    public function createUser(): string
    {
        $response = $this->httpClient->post(new Uri("{$this->baseUri}/users"), [
            'form_params' => [
                'fullname' => 'First Last Name',
                'email' => 'user@example.test',
                'password' => 'very@secure&password123',
            ],
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        return $response->getBody();
    }
}
