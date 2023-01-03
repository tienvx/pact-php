<?php

namespace PhpPact\Standalone\StubService;

use Amp\Process\ProcessException;
use Exception;
use PhpPact\Standalone\Installer\Model\Scripts;
use PhpPact\Standalone\Runner\ProcessRunner;

/**
 * Ruby Standalone Stub Server Wrapper
 */
class StubServer
{
    private StubServerConfigInterface $config;

    private ProcessRunner $processRunner;

    public function __construct(StubServerConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * Start the Stub Server. Verify that it is running.
     *
     * @param int $wait seconds to delay for the server to come up
     *
     * @throws Exception
     *
     * @return int process ID of the started Stub Server
     */
    public function start(int $wait = 1): int
    {
        $this->processRunner = new ProcessRunner(Scripts::getStubService(), $this->getArguments());

        $processId =  $this->processRunner->run();
        \sleep($wait); // wait for server to start

        return $processId;
    }

    /**
     * Stop the Stub Server process.
     *
     * @throws ProcessException
     *
     * @return bool Was stopping successful?
     * @throws ProcessException
     */
    public function stop(): bool
    {
        return $this->processRunner->stop();
    }

    /**
     * Build an array of command arguments.
     *
     * @return array<int, string>
     */
    private function getArguments(): array
    {
        $results = [];

        if ($this->config->getBrokerUrl() !== null) {
            $results[] = "--broker-url={$this->config->getBrokerUrl()}";
        }

        foreach ($this->config->getDirs() as $dir) {
            $results[] = "--dir={$dir}";
        }

        if ($this->config->getExtension() !== null) {
            $results[] = "--extension={$this->config->getExtension()}";
        }

        foreach ($this->config->getFiles() as $file) {
            $results[] = "--file={$file}";
        }

        if ($this->config->getLogLevel() !== null) {
            $results[] = "--loglevel={$this->config->getLogLevel()}";
        }

        if ($this->config->getPort() !== null) {
            $results[] = "--port={$this->config->getPort()}";
        }

        if ($this->config->getProviderState() !== null) {
            $results[] = "--provider-state={$this->config->getProviderState()}";
        }

        if ($this->config->getProviderStateHeaderName() !== null) {
            $results[] = "--provider-state-header-name={$this->config->getProviderStateHeaderName()}";
        }

        if ($this->config->getToken() !== null) {
            $results[] = "--token={$this->config->getToken()}";
        }

        foreach ($this->config->getUrls() as $url) {
            $results[] = "--url={$url}";
        }

        if ($this->config->getUser() !== null) {
            $results[] = "--user={$this->config->getUser()}";
        }

        if ($this->config->isCors()) {
            $results[] = '--cors';
        }

        if ($this->config->isCorsReferer()) {
            $results[] = '--cors-referer';
        }

        if ($this->config->isEmptyProviderState()) {
            $results[] = '--empty-provider-state';
        }

        if ($this->config->isInsecureTls()) {
            $results[] = '--insecure-tls';
        }

        return $results;
    }
}
