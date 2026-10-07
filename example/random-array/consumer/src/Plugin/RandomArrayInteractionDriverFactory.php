<?php

namespace RandomArrayConsumer\Plugin;

use PhpPact\Consumer\Driver\Interaction\InteractionDriver;
use PhpPact\Consumer\Driver\Interaction\InteractionDriverInterface;
use PhpPact\Consumer\Factory\InteractionDriverFactoryInterface;
use PhpPact\Consumer\Service\MockServer;
use PhpPact\FFI\Client;
use PhpPact\Standalone\MockService\MockServerConfigInterface;

class RandomArrayInteractionDriverFactory implements InteractionDriverFactoryInterface
{
    public function create(MockServerConfigInterface $config): InteractionDriverInterface
    {
        $client = new Client();
        $pactDriver = new RandomArrayPactDriver($client, $config);
        $mockServer = new MockServer($client, $pactDriver, $config);

        return new InteractionDriver($client, $mockServer, $pactDriver);
    }
}
