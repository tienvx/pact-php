<?php

namespace PhpPact\Consumer\Service;

use FFI;
use PhpPact\Config\Enum\WriteMode;
use PhpPact\Config\PactConfigInterface;
use PhpPact\Consumer\Driver\Pact\PactDriverInterface;
use PhpPact\Consumer\Exception\MockServerNotStartedException;
use PhpPact\Consumer\Exception\MockServerPactFileNotWrittenException;
use PhpPact\FFI\ClientInterface;
use PhpPact\Standalone\MockService\MockServerConfigInterface;
use PhpPact\Standalone\MockService\Model\VerifyResult;

class MockServer implements MockServerInterface
{
    private readonly WaiterInterface $waiter;

    public function __construct(
        private readonly ClientInterface $client,
        private readonly PactDriverInterface $pactDriver,
        private readonly MockServerConfigInterface $config,
        ?WaiterInterface $waiter = null
    ) {
        $this->waiter = $waiter ?? new Waiter($this->config->getWaitTimeout());
    }

    public function start(): void
    {
        $port = $this->client->createMockServerForTransport(
            $this->pactDriver->getPact()->handle,
            $this->config->getHost(),
            $this->config->getPort(),
            $this->getTransport(),
            $this->getTransportConfig()
        );

        if ($port < 0) {
            throw new MockServerNotStartedException($port);
        }
        $this->config->setPort($port);
    }

    public function verify(): VerifyResult
    {
        try {
            $result = $this->waitForMatching();

            if ($result->matched) {
                $this->writePact();
            }
            return $result;
        } finally {
            $this->cleanUp();
        }
    }

    protected function getTransport(): string
    {
        return $this->config->isSecure() ? 'https' : 'http';
    }

    protected function getTransportConfig(): ?string
    {
        return null;
    }

    public function writePact(): void
    {
        $error = $this->client->writePactFile(
            $this->config->getPort(),
            $this->config->getPactDir(),
            $this->config->getPactFileWriteMode() === WriteMode::OVERWRITE
        );
        if ($error) {
            throw new MockServerPactFileNotWrittenException($error);
        }
    }

    public function cleanUp(): void
    {
        $success = $this->client->cleanupMockServer($this->config->getPort());
        if (!$success) {
            trigger_error(sprintf("Can not clean up mock server: Mock server with the given port number '%s' does not exist, or the function panics", $this->config->getPort()), E_USER_WARNING);
        }
        $this->pactDriver->cleanUp();
    }

    private function isMatched(): bool
    {
        return $this->client->mockServerMatched($this->config->getPort());
    }

    private function getMismatches(): string
    {
        return $this->client->mockServerMismatches($this->config->getPort());
    }

    private function waitForMatching(): VerifyResult
    {
        return $this->waiter->waitUntil($this->getVerifyResult(...), $this->canStopWaiting(...));
    }

    private function getVerifyResult(): VerifyResult
    {
        $matched = $this->isMatched();
        $mismatches = $this->getMismatches();

        return new VerifyResult($matched, $mismatches);
    }

    private function canStopWaiting(VerifyResult $result): bool
    {
        return $result->matched || !empty($result->mismatches);
    }
}
