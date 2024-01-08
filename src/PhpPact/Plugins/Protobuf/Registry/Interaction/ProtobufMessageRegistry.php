<?php

namespace PhpPact\Plugins\Protobuf\Registry\Interaction;

use PhpPact\Consumer\Registry\Interaction\Body\BodyRegistryInterface;
use PhpPact\Consumer\Registry\Interaction\MessageRegistry;
use PhpPact\Consumer\Registry\Pact\PactRegistryInterface;
use PhpPact\FFI\ClientInterface;
use PhpPact\Plugins\Protobuf\Registry\Interaction\Body\ProtobufMessageContentsRegistry;

class ProtobufMessageRegistry extends MessageRegistry
{
    public function __construct(
        ClientInterface $client,
        PactRegistryInterface $pactRegistry,
        ?BodyRegistryInterface $messageContentsRegistry = null
    ) {
        parent::__construct($client, $pactRegistry, $messageContentsRegistry ?? new ProtobufMessageContentsRegistry($client, $this));
    }
}
