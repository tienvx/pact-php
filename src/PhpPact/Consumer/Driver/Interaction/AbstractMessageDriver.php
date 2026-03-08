<?php

namespace PhpPact\Consumer\Driver\Interaction;

use PhpPact\Consumer\Driver\Body\MessageBodyDriver;
use PhpPact\Consumer\Driver\Body\MessageBodyDriverInterface;
use PhpPact\Consumer\Driver\Pact\PactDriverInterface;
use PhpPact\Consumer\Model\Message;
use PhpPact\FFI\ClientInterface;

abstract class AbstractMessageDriver extends AbstractDriver implements SharedMessageDriverInterface
{
}
