<?php

namespace Consumer\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Plugins\AreaResponse;
use Plugins\CalculatorInterface;
use Plugins\ShapeMessage;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Example HTTP Service
 * Class HttpClientService
 */
class HttpClientService implements CalculatorInterface
{
    private Client $httpClient;
    private string $baseUri;

    public function __construct(string $baseUri)
    {
        $this->httpClient = new Client();
        $this->baseUri    = $baseUri;
    }

    /**
     * Get Hello String
     *
     * @param string $name
     *
     * @return string
     *
     * @throws GuzzleException
     */
    public function getHelloString(string $name): string
    {
        $response = $this->httpClient->get(new Uri("{$this->baseUri}/hello/{$name}"), [
            'headers' => ['Content-Type' => 'application/json']
        ]);
        $body   = $response->getBody();
        $object = \json_decode($body);

        return $object->message;
    }

    /**
     * Get Goodbye String
     *
     * @param string $name
     *
     * @return string
     *
     * @throws GuzzleException
     */
    public function getGoodbyeString(string $name): string
    {
        $response = $this->httpClient->get("{$this->baseUri}/goodbye/{$name}", [
            'headers' => ['Content-Type' => 'application/json']
        ]);
        $body   = $response->getBody();
        $object = \json_decode($body);

        return $object->message;
    }

    /**
     * Get CSV file
     *
     * @return array
     *
     * @throws GuzzleException
     */
    public function getCsvFile(): array
    {
        $response = $this->httpClient->get("{$this->baseUri}/report.csv", [
            'headers' => ['Content-Type' => 'text/csv']
        ]);

        return str_getcsv($response->getBody());
    }

    /**
     * @param ShapeMessage $request
     *
     * @return AreaResponse
     *
     * @throws GuzzleException
     */
    public function calculate(ShapeMessage $request): AreaResponse
    {
        $httpResponse = $this->httpClient->post("{$this->baseUri}/calculate", [
            'headers' => ['Content-Type' => 'application/protobuf;message=ShapeMessage'],
            'body' => $request->serializeToString(),
        ]);
        $response = new AreaResponse();
        $response->mergeFromString($httpResponse->getBody()->getContents());

        return $response;
    }
}
