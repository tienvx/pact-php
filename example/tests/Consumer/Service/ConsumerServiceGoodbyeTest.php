<?php

namespace Consumer\Service;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use PhpPact\Consumer\InteractionBuilder;
use PhpPact\Consumer\Model\ConsumerEnvConfig;
use PhpPact\Consumer\Model\ConsumerRequest;
use PhpPact\Consumer\Model\ProviderResponse;
use PhpPact\Installer\Exception\LibrariesNotInstalledException;
use PhpPact\Installer\Exception\NoInstallerFoundException;
use PHPUnit\Framework\TestCase;

class ConsumerServiceGoodbyeTest extends TestCase
{
    /**
     * @throws GuzzleException
     * @throws NoInstallerFoundException
     * @throws LibrariesNotInstalledException
     * @throws Exception
     */
    public function testGetGoodbyeString()
    {
        $request = new ConsumerRequest();
        $request
            ->setMethod('GET')
            ->setPath('/goodbye/Bob')
            ->addHeader('Content-Type', 'application/json');

        $response = new ProviderResponse();
        $response
            ->setStatus(200)
            ->addHeader('Content-Type', 'application/json')
            ->setBody(\json_encode([
                'message' => 'Goodbye, Bob'
            ]));

        $config      = new ConsumerEnvConfig();
        $config->setProvider('someProvider');
        $builder     = new InteractionBuilder($config);
        $builder
            ->newInteraction()
            ->given('Get Goodbye')
            ->uponReceiving('A get request to /goodbye/{name}')
            ->with($request)
            ->willRespondWith($response)
            ->createMockServer();

        $service = new HttpClientService($builder->getBaseUri());
        $result  = $service->getGoodbyeString('Bob');

        $this->assertTrue($builder->verify(), 'Expects verification to pass');
        $this->assertEquals('Goodbye, Bob', $result);
    }
}
