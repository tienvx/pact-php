<?php

namespace Provider;

use GuzzleHttp\Psr7\Uri;
use PhpPact\Installer\Exception\LibrariesNotInstalledException;
use PhpPact\Installer\Exception\NoInstallerFoundException;
use PhpPact\Provider\Model\VerifierConfig;
use PhpPact\Provider\Verifier;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

/**
 * This is an example on how you could use the included amphp/process wrapper to start your API to run PACT verification against a Provider.
 * Class PactVerifyTest
 */
class PactVerifyTest extends TestCase
{
    private Process $proxy;
    private Process $app;

    /**
     * Run the PHP build-in web server.
     */
    protected function setUp(): void
    {
        $providerPath    = __DIR__ . '/../../src/Provider';

        $this->proxy = new Process(['php', '-S', 'localhost:7202', '-t', $providerPath . '/proxy']);
        $this->proxy->start();
        $this->proxy->waitUntil(function ($type, $output) {
            return false !== \strpos($output, 'Development Server (http://localhost:7202) started');
        });

        $this->app = new Process(['php', '-S', 'localhost:7201', '-t', $providerPath . '/app']);
        $this->app->start();
        $this->app->waitUntil(function ($type, $output) {
            return false !== \strpos($output, 'Development Server (http://localhost:7201) started');
        });
    }

    /**
     * Stop the web server process once complete.
     */
    protected function tearDown(): void
    {
        $this->proxy->stop();
        $this->app->stop();
    }

    /**
     * This test will run after the web server is started.
     *
     * @throws NoInstallerFoundException
     * @throws LibrariesNotInstalledException
     */
    public function testPactVerifyConsumer()
    {
        $config = new VerifierConfig();
        $config
            ->setProviderName('someProvider') // Providers name to fetch.
            ->setProviderVersion('1.0.0') // Providers version.
            ->setHost('localhost')
            ->setPort(7202)
            ->setStateChangeUrl(new Uri('http://localhost:7202/change-state'))
            ->setDirs(__DIR__ . '/../../pacts/')
            ; // Flag the verifier service to publish the results to the Pact Broker.

        // Verify that the Consumer 'someConsumer' that is tagged with 'master' is valid.
        $verifier = new Verifier($config);

        $this->assertTrue($verifier->verify(), 'Expects verification to pass');
    }
}
