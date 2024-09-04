<?php

namespace PhpPact\Plugins\FormUrlEncoded\Factory;

use PhpPact\Consumer\Driver\Interaction\InteractionDriver;
use PhpPact\Consumer\Driver\Interaction\InteractionDriverInterface;
use PhpPact\Consumer\Driver\InteractionPart\RequestDriver;
use PhpPact\Consumer\Factory\InteractionDriverFactoryInterface;
use PhpPact\FFI\Client;
use PhpPact\Plugin\Driver\Body\PluginBodyDriver;
use PhpPact\Plugins\FormUrlEncoded\Driver\Body\FormUrlEncodedBodyDriver;
use PhpPact\Plugins\FormUrlEncoded\Driver\Pact\FormUrlEncodedPactDriver;
use PhpPact\Standalone\MockService\MockServerConfigInterface;
use PhpPact\Consumer\Service\MockServer;

class FormUrlEncodedInteractionDriverFactory implements InteractionDriverFactoryInterface
{
    public function create(MockServerConfigInterface $config): InteractionDriverInterface
    {
        $client = new Client();
        $pactDriver = new FormUrlEncodedPactDriver($client, $config);
        $mockServer = new MockServer($client, $pactDriver, $config);
        $formUrlEncodedBodyDriver = new FormUrlEncodedBodyDriver(new PluginBodyDriver($client));
        $requestDriver = new RequestDriver($client, $formUrlEncodedBodyDriver);
        $responseDriver = null;

        return new InteractionDriver($client, $mockServer, $pactDriver, $requestDriver, $responseDriver);
    }
}
