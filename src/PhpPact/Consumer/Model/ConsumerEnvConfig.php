<?php

namespace PhpPact\Consumer\Model;

/**
 * An environment variable based consumer configuration.
 * Class ConsumerEnvConfig.
 */
class ConsumerEnvConfig extends ConsumerConfig
{
    const DEFAULT_SPECIFICATION_VERSION = '3.0.0';

    /**
     * PactEnvConfig constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $pactDir = \getenv('PACT_OUTPUT_DIR');
        if (\is_string($pactDir)) {
            $this->setPactDir($pactDir);
        }
        $this->setConsumer(\getenv('PACT_CONSUMER_NAME') ?: '');
        $this->setPactSpecificationVersion(\getenv('PACT_SPECIFICATION_VERSION') ?: static::DEFAULT_SPECIFICATION_VERSION);
    }
}
