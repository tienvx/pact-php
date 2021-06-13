<?php

namespace Consumer\Service;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use PhpPact\Consumer\InteractionBuilder;
use PhpPact\Consumer\Matcher\Matcher;
use PhpPact\Consumer\Model\ConsumerEnvConfig;
use PhpPact\Consumer\Model\ConsumerRequest;
use PhpPact\Consumer\Model\ProviderResponse;
use PhpPact\Installer\Exception\LibrariesNotInstalledException;
use PhpPact\Installer\Exception\NoInstallerFoundException;
use PHPUnit\Framework\TestCase;

class ConsumerServiceHelloTest extends TestCase
{
    /**
     * Example PACT test.
     *
     * @throws GuzzleException
     * @throws NoInstallerFoundException
     * @throws LibrariesNotInstalledException
     * @throws Exception
     */
    public function testGetHelloString()
    {
        $matcher = new Matcher();

        // Create your expected request from the consumer.
        $request = new ConsumerRequest();
        $request
            ->setMethod('GET')
            ->setPath('/hello/Bob')
            ->addHeader('Content-Type', 'application/json');

        // Create your expected response from the provider.
        $response = new ProviderResponse();
        $response
            ->setStatus(200)
            ->addHeader('Content-Type', 'application/json')
            ->setBody(\json_encode([
                'message' => $matcher->term('Hello, Bob', '(Hello, )[A-Za-z]')
            ]));

        // Create a configuration that reflects the server that was started. You can create a custom MockServerConfigInterface if needed.
        $config  = new ConsumerEnvConfig();
        $config->setProvider('someProvider');
        $builder = new InteractionBuilder($config);
        $builder
            ->newInteraction()
            ->uponReceiving('A get request to /hello/{name}')
            ->with($request)
            ->willRespondWith($response)
            ->createMockServer();

        $service = new HttpClientService($builder->getBaseUri()); // Pass in the URL to the Mock Server.
        $result  = $service->getHelloString('Bob'); // Make the real API request against the Mock Server.

        $this->assertTrue($builder->verify(), 'Expects verification to pass');
        $this->assertEquals('Hello, Bob', $result); // Make your assertions.
    }
}
