<?php

namespace FormUrlEncodedConsumer\Tests\Service;

use PhpPact\Consumer\Matcher\Matcher;
use FormUrlEncodedConsumer\Service\HttpClientService;
use PhpPact\Consumer\InteractionBuilder;
use PhpPact\Consumer\Model\Body\Text;
use PhpPact\Consumer\Model\ConsumerRequest;
use PhpPact\Consumer\Model\ProviderResponse;
use PhpPact\Plugins\FormUrlEncoded\Factory\FormUrlEncodedInteractionDriverFactory;
use PhpPact\Standalone\MockService\MockServerConfig;
use PHPUnit\Framework\TestCase;

class HttpClientServiceTest extends TestCase
{
    public function testGetMovies()
    {
        $matcher = new Matcher(plugin: true);

        $request = new ConsumerRequest();
        $request
            ->setMethod('POST')
            ->setPath('/users')
            ->addHeader('Content-Type', 'application/x-www-form-urlencoded')
            ->addHeader('Accept', 'application/json')
            ->setBody(new Text(
                json_encode([
                    'field:fullname' => $matcher->string('User name'),
                    'field:email' => $matcher->email('user@email.test'),
                    'field:password' => $matcher->regex('user@password111', '^[\w\d@$!%*#?&^_-]{8,}$'),
                ]),
                'application/x-www-form-urlencoded'
            ))
        ;

        $response = new ProviderResponse();
        $response
            ->setStatus(201)
            ->addHeader('Content-Type', 'application/json')
            ->setBody([
                'id' => $matcher->uuid('6e58b1df-ff80-4031-b7b9-5191e4c74ee8'),
            ]);

        $config = new MockServerConfig();
        $config
            ->setConsumer('formUrlEncodedConsumer')
            ->setProvider('formUrlEncodedProvider')
            ->setPactSpecificationVersion('4.0.0')
            ->setPactDir(__DIR__.'/../../../pacts');
        if ($logLevel = \getenv('PACT_LOGLEVEL')) {
            $config->setLogLevel($logLevel);
        }
        $builder = new InteractionBuilder($config, new FormUrlEncodedInteractionDriverFactory());
        $builder
            ->given('Endpoint is protected')
            ->uponReceiving('A post request to /users')
            ->with($request)
            ->willRespondWith($response);

        $service = new HttpClientService($config->getBaseUri());
        $body = json_decode($service->createUser(), true);
        $verifyResult = $builder->verify();

        $this->assertTrue($verifyResult);
        $this->assertArrayHasKey('id', $body);
        $pattern = Matcher::UUID_V4_FORMAT;
        $this->assertEquals(1, preg_match("/{$pattern}/", $body['id']));
    }
}
