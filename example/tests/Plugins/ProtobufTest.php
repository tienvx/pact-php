<?php

namespace Plugins;

use Consumer\Service\HttpClientService;
use GuzzleHttp\Exception\GuzzleException;
use PhpPact\Consumer\Exception\MockServerNotStartedException;
use PhpPact\Consumer\Exception\PluginNotSupportedBySpecificationException;
use PhpPact\Consumer\InteractionBuilder;
use PhpPact\Consumer\Model\ConsumerRequest;
use PhpPact\Consumer\Model\ProviderResponse;
use PhpPact\Standalone\MockService\MockServerEnvConfig;
use PHPUnit\Framework\TestCase;

class ProtobufTest extends TestCase
{
    /**
     * @throws MockServerNotStartedException
     * @throws PluginNotSupportedBySpecificationException
     * @throws GuzzleException
     */
    public function testCalculateArea()
    {
        $request = new ConsumerRequest();
        $request
            ->setMethod('POST')
            ->setPath('/calculate')
            ->setBody(\json_encode([
                'pact:proto' => __DIR__ . '/../../proto/area_calculator.proto',
                'pact:proto-service' => 'Calculator/calculate:request',
                'rectangle' => [
                    'length' => 'matching(number, 3)',
                    'width' => 'matching(number, 4)',
                ],
            ]))
            ->setContentType('application/protobuf');

        $response = new ProviderResponse();
        $response
            ->setStatus(200)
            ->setBody(\json_encode([
                'pact:proto' => __DIR__ . '/../../proto/area_calculator.proto',
                'pact:proto-service' => 'Calculator/calculate:response',
                'value' => 'matching(number, 12)',
            ]))
            ->setContentType('application/protobuf');

        $config      = new MockServerEnvConfig();
        $config->setConsumer('protobufConsumer');
        $config->setProvider('protobufProvider');
        $config->setPactSpecificationVersion('4.0.0');
        $builder     = new InteractionBuilder($config);
        $builder
            ->usingPlugin('protobuf')
            ->newInteraction()
            ->uponReceiving('request for calculate shape area')
            ->with($request)
            ->willRespondWith($response)
            ->createMockServer();

        $service = new HttpClientService($builder->getBaseUri());
        $message = (new ShapeMessage())->setRectangle((new Rectangle())->setLength(3)->setWidth(4));
        $response = $service->calculate($message);

        $this->assertTrue($builder->verify());
        $this->assertEquals(3 * 4, $response->getValue());
    }
}
