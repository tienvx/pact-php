<?php

namespace RandomArrayConsumer\Tests\Service;

use RandomArrayConsumer\Plugin\RandomArray;
use RandomArrayConsumer\Plugin\RandomArrayInteractionDriverFactory;
use RandomArrayConsumer\Service\HttpClientService;
use PhpPact\Consumer\InteractionBuilder;
use PhpPact\Consumer\Matcher\Generators\RandomInt;
use PhpPact\Consumer\Matcher\Generators\RandomString;
use PhpPact\Consumer\Matcher\Generators\Uuid;
use PhpPact\Consumer\Matcher\Matcher;
use PhpPact\Consumer\Model\ConsumerRequest;
use PhpPact\Consumer\Model\ProviderResponse;
use PhpPact\Standalone\MockService\MockServerConfig;
use PHPUnit\Framework\TestCase;

class RandomArrayTest extends TestCase
{
    public function testCreateOrders(): void
    {
        $matcher = new Matcher();

        $request = new ConsumerRequest();
        $request
            ->setMethod('POST')
            ->setPath('/orders')
            ->addHeader('Content-Type', 'application/json')
            ->addHeader('Accept', 'application/json')
            ->setBody([
                'total' => $matcher->integerV3(24),
                'items' => $matcher->notEmpty([
                    [
                        'name' => $matcher->string('xxx')->withGenerator(new RandomString(10)),
                        'price' => $matcher->integerV3(12)->withGenerator(new RandomInt(1, 100)),
                    ],
                ])->withGenerator(new RandomArray(1, 3)),
            ]);

        $response = new ProviderResponse();
        $response
            ->setStatus(201)
            ->addHeader('Content-Type', 'application/json')
            ->setBody([
                'success' => $matcher->booleanV3(true),
                'created' => $matcher->notEmpty([
                    [
                        'id' => $matcher->uuid()->withGenerator(new Uuid()),
                        'status' => $matcher->equal('pending'),
                    ],
                ])->withGenerator(new RandomArray(4, 6)),
            ]);

        $config = new MockServerConfig();
        $config
            ->setConsumer('randomArrayConsumer')
            ->setProvider('randomArrayProvider')
            ->setPactDir(__DIR__.'/../../../pacts')
            ->setPactSpecificationVersion('4.0.0');
        if ($logLevel = \getenv('PACT_LOGLEVEL')) {
            $config->setLogLevel($logLevel);
        }
        $builder = new InteractionBuilder($config, new RandomArrayInteractionDriverFactory());
        $builder
            ->uponReceiving('A request to create orders')
            ->with($request)
            ->willRespondWith($response);

        $service = new HttpClientService($config->getBaseUri());
        $response = $service->createOrder([
            'total' => 24,
            'items' => [
                ['name' => 'xxx', 'price' => 12],
            ],
        ]);
        $verifyResult = $builder->verify();

        $statusCode = $response->getStatusCode();
        /** @var array<string, mixed> $body */
        /** @var array{success: bool, created: list<array{id: string, status: string}>} $body */
        $body = \json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertTrue($verifyResult);
        $this->assertSame(201, $statusCode);
        $this->assertTrue($body['success']);
        // The RandomArray generator expanded the response array to between 4 and 6 items,
        // each with its own generated id
        $this->assertIsArray($body['created']);
        $this->assertGreaterThanOrEqual(4, count($body['created']));
        $this->assertLessThanOrEqual(6, count($body['created']));
        $ids = array_column($body['created'], 'id');
        $this->assertSame(count($ids), count(array_unique($ids)));
        foreach ($body['created'] as $created) {
            $this->assertMatchesRegularExpression('/' . Matcher::UUID_V4_FORMAT . '/', $created['id']);
            $this->assertSame('pending', $created['status']);
        }
    }
}
