<?php

namespace PhpPact\Consumer;

use FFI;
use PhpPact\Consumer\Model\ConsumerConfigInterface;
use PhpPact\Installer\Exception\LibrariesNotInstalledException;
use PhpPact\Installer\Exception\NoInstallerFoundException;
use PhpPact\Installer\InstallManager;
use PhpPact\Installer\Model\Scripts;

/**
 * Class AbstractBuilder.
 */
abstract class AbstractBuilder implements BuilderInterface
{
    const SUPPORTED_PACT_SPECIFICATION_VERSIONS = [
        '1.0.0' => 1,
        '1.1.0' => 2,
        '2.0.0' => 3,
        '3.0.0' => 4,
        '4.0.0' => 5,
    ];
    const UNKNOWN_PACT_SPECIFICATION_VERSION = 0;

    protected FFI $ffi;
    protected ConsumerConfigInterface $config;
    protected Scripts $scripts;

    /**
     * @param ConsumerConfigInterface $config
     *
     * @throws LibrariesNotInstalledException
     * @throws NoInstallerFoundException
     */
    public function __construct(ConsumerConfigInterface $config)
    {
        $this->config  = $config;
        $this->scripts = (new InstallManager())->getScripts();
        $this->ffi     = FFI::cdef(\file_get_contents($this->scripts->getCode()), $this->scripts->getLibrary());
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
