<?php

namespace PhpPact\Consumer\Model;

/**
 * Consumer configuration interface to allow for simple overrides that are reusable.
 * Interface ConsumerConfigInterface.
 */
interface ConsumerConfigInterface
{
    /**
     * @return string
     */
    public function getConsumer(): string;

    /**
     * @param string $consumer consumers name
     *
     * @return ConsumerConfigInterface
     */
    public function setConsumer(string $consumer): self;

    /**
     * @return string
     */
    public function getProvider(): string;

    /**
     * @param string $provider providers name
     *
     * @return ConsumerConfigInterface
     */
    public function setProvider(string $provider): self;

    /**
     * @return string url to place the pact files when written to disk
     */
    public function getPactDir(): string;

    /**
     * @param string $pactDir url to place the pact files when written to disk
     *
     * @return ConsumerConfigInterface
     */
    public function setPactDir(string $pactDir): self;

    /**
     * @return string pact version
     */
    public function getPactSpecificationVersion(): string;

    /**
     * @param string $pactSpecificationVersion pact specification version
     *
     * @return ConsumerConfigInterface
     */
    public function setPactSpecificationVersion(string $pactSpecificationVersion): self;
}
