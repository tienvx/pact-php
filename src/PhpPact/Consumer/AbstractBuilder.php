<?php

namespace PhpPact\Consumer;

use FFI;
use PhpPact\Consumer\Exception\PluginNotSupportedBySpecificationException;
use PhpPact\Standalone\Scripts;
use PhpPact\Standalone\MockService\MockServerConfigInterface;

/**
 * Class AbstractBuilder.
 */
abstract class AbstractBuilder implements BuilderInterface
{
    protected int $pact;
    protected int $interaction;
    protected FFI $ffi;
    protected MockServerConfigInterface $config;
    protected bool $usingPlugin = false;

    /**
     * @param MockServerConfigInterface $config
     */
    public function __construct(MockServerConfigInterface $config)
    {
        $this->config  = $config;
        $this->ffi     = FFI::cdef(\file_get_contents(Scripts::getCode()), Scripts::getLibrary());
        $this->ffi->pactffi_init('PACT_LOGLEVEL');
        $this->pact = $this->ffi->pactffi_new_pact($config->getConsumer(), $config->getProvider());
        $this->ffi->pactffi_with_specification($this->pact, $this->getSpecification());
    }

    /**
     * @param string $description what is received when the request is made
     *
     * @return $this
     */
    abstract public function newInteraction(string $description = ''): self;

    /**
     * @param string      $pluginName the name of the plugin to load
     * @param string|null $pluginVersion the version of the plugin to load
     *
     * @return $this
     *
     * @throws PluginNotSupportedBySpecificationException
     */
    public function usingPlugin(string $pluginName, ?string $pluginVersion = null): self
    {
        if ($this->getSpecification() < $this->ffi->PactSpecification_V4) {
            throw new PluginNotSupportedBySpecificationException(sprintf(
                'Plugin is not supported by specification %s, use %s or above',
                $this->config->getPactSpecificationVersion(),
                '4.0.0'
            ));
        }

        $this->ffi->pactffi_using_plugin($this->pact, $pluginName, $pluginVersion);
        $this->usingPlugin = true;

        return $this;
    }

    /**
     * @return int
     */
    private function getSpecification(): int
    {
        $map = [
            '1.0.0' => $this->ffi->PactSpecification_V1,
            '1.1.0' => $this->ffi->PactSpecification_V1_1,
            '2.0.0' => $this->ffi->PactSpecification_V2,
            '3.0.0' => $this->ffi->PactSpecification_V3,
            '4.0.0' => $this->ffi->PactSpecification_V4,
        ];
        return $map[$this->config->getPactSpecificationVersion()] ?? $this->ffi->PactSpecification_Unknown;
    }
}
