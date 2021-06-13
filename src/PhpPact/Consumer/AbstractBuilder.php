<?php

namespace PhpPact\Consumer;

use FFI;
use PhpPact\Standalone\Scripts;
use PhpPact\Standalone\MockService\MockServerConfigInterface;

/**
 * Class AbstractBuilder.
 */
abstract class AbstractBuilder implements BuilderInterface
{
    public const SUPPORTED_PACT_SPECIFICATION_VERSIONS = [
        '1.0.0' => 1,
        '1.1.0' => 2,
        '2.0.0' => 3,
        '3.0.0' => 4,
        '4.0.0' => 5,
    ];
    public const UNKNOWN_PACT_SPECIFICATION_VERSION = 0;

    protected FFI $ffi;
    protected MockServerConfigInterface $config;

    /**
     * @param MockServerConfigInterface $config
     */
    public function __construct(MockServerConfigInterface $config)
    {
        $this->config  = $config;
        $this->ffi     = FFI::cdef(\file_get_contents(Scripts::getCode()), Scripts::getLibrary());
        $this->ffi->pactffi_init('PACT_LOGLEVEL');
    }

    /**
     * @return int
     */
    protected function getPactSpecificationVersion(): int
    {
        return static::SUPPORTED_PACT_SPECIFICATION_VERSIONS[$this->config->getPactSpecificationVersion()] ?? static::UNKNOWN_PACT_SPECIFICATION_VERSION;
    }
}
