<?php

namespace ServerSentEventsConsumer\Tests\Service;

use PhpPact\Consumer\Driver\Enum\InteractionPart;
use PhpPact\Consumer\InteractionBuilder;
use PhpPact\Consumer\Matcher\Matcher;
use PhpPact\Consumer\Model\Body\Text;
use PhpPact\Consumer\Model\ConsumerRequest;
use PhpPact\Consumer\Model\ProviderResponse;
use PhpPact\Plugins\Sse\Factory\SseInteractionDriverFactory;
use PhpPact\Standalone\MockService\MockServerConfig;
use PHPUnit\Framework\TestCase;
use SseClient\Client;

class HttpClientServiceTest extends TestCase
{
    public function testGetEvents(): void
    {
        $matcher = new Matcher(plugin: true);

        $request = new ConsumerRequest();
        $request
            ->setMethod('GET')
            ->setPath('/events')
            ->addHeader('Accept', 'text/event-stream')
        ;

        $response = new ProviderResponse();
        $response
            ->setStatus(200)
            ->setBody(new Text(
                json_encode([
                    'csvHeaders' => false,
                    'data:1' => $matcher->like('Name'),
                    'data:2' => $matcher->number(100),
                    'data:3' => $matcher->datetime('yyyy-MM-dd', '2000-01-01'),
                ]),
                'text/event-stream'
            ))
        ;

        $config = new MockServerConfig();
        $config
            ->setConsumer('sseConsumer')
            ->setProvider('sseProvider')
            ->setPactSpecificationVersion('4.0.0')
            ->setPactDir(__DIR__.'/../../../pacts');
        if ($logLevel = \getenv('PACT_LOGLEVEL')) {
            $config->setLogLevel($logLevel);
        }
        $builder = new InteractionBuilder($config, new SseInteractionDriverFactory(InteractionPart::RESPONSE));
        $builder
            ->given('events file exist')
            ->uponReceiving('request for events')
            ->with($request)
            ->willRespondWith($response)
        ;

        $service = new Client($config->getBaseUri() . '/events');
        $events = $service->getEvents();
        $data = [
            'Name',
            100,
            '2000-01-01',
        ];

        foreach ($events as $event) {
            $expected = array_shift($data);
            $this->assertSame($expected, $event);
            if (empty($data)) {
                break;
            }
        }

        $this->assertTrue($builder->verify());
    }
}
