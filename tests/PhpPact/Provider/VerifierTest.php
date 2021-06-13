<?php

namespace PhpPact\Provider;

use GuzzleHttp\Psr7\Uri;
use PhpPact\Installer\Exception\LibrariesNotInstalledException;
use PhpPact\Installer\Exception\NoInstallerFoundException;
use PhpPact\Provider\Model\ConsumerVersionSelectors;
use PhpPact\Provider\Model\VerifierConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class VerifierTest extends TestCase
{
    /**
     * @throws NoInstallerFoundException
     * @throws LibrariesNotInstalledException
     */
    public function testGetArguments(): void
    {
        $selectors = new ConsumerVersionSelectors();
        $selectors->add('new-feature', true, null, 'master');
        $selectors->add('test');
        $selectors->add('production');
        $selectors->add('production', false, 'my-mobile-consumer');
        $config = new VerifierConfig();
        $config
            ->setScheme('http')
            ->setHost('myprovider')
            ->setPort(1234)
            ->setBasePath('/api')
            ->setStateChangeUrl(new Uri('http://someurl:1234'))
            ->setProviderName('someProvider')
            ->setProviderVersion('1.0.0')
            ->setProviderTags('prod,dev')
            ->setConsumerVersionTags('dev')
            ->setBrokerUrl(new Uri('https://testdemo.pactflow.io'))
            ->setBrokerToken('someToken')
            ->setBrokerUsername('someusername')
            ->setBrokerPassword('somepassword')
            ->setIncludeWipPactsSince('2020-01-30')
            ->setBuildUrl(new Uri('http://build.domain.com'))
            ->setConsumerVersionSelectors($selectors)
            ->setLogLevel('info')
            ->setRequestTimeout(500)
            ->setUrls('http://example.com/consumer1-provider1.json', 'http://example.com/consumer2-provider1.json')
            ->setDirs('/path/to/pacts', '/another/path/to/pacts')
            ->setFiles('consumer1-provider2.json', 'consumer2-provider2.json')
            ->setFilterConsumerNames('consumer1', 'consumer2')
            ->setFilterDescription('Send POST to create')
            ->setFilterNoState('state1')
            ->setFilterState('state2')
            ->setPublish(true)
            ->setDisableSslVerification(true)
            ->setEnablePending(true)
            ->setStateChangeAsQuery(true)
            ->setStateChangeTeardown(true);

        $verifier    = new Verifier($config);
        $arguments   = $verifier->getArguments();

        $this->assertContains('--scheme=http', $arguments);
        $this->assertContains('--hostname=myprovider', $arguments);
        $this->assertContains('--port=1234', $arguments);
        $this->assertContains('--base-path=/api', $arguments);
        $this->assertContains('--state-change-url=http://someurl:1234', $arguments);
        $this->assertContains('--provider-name=someProvider', $arguments);
        $this->assertContains('--provider-version=1.0.0', $arguments);
        $this->assertContains('--provider-tags=prod,dev', $arguments);
        $this->assertContains('--consumer-version-tags=dev', $arguments);
        $this->assertContains('--broker-url=https://testdemo.pactflow.io', $arguments);
        $this->assertContains('--token=someToken', $arguments);
        $this->assertContains('--user=someusername', $arguments);
        $this->assertContains('--password=somepassword', $arguments);
        $this->assertContains('--include-wip-pacts-since=2020-01-30', $arguments);
        $this->assertContains('--build-url=http://build.domain.com', $arguments);
        $this->assertContains('--consumer-version-selectors="[{\"tag\":\"new-feature\",\"latest\":true,\"fallbackTag\":\"master\"},{\"tag\":\"test\",\"latest\":true},{\"tag\":\"production\",\"latest\":true},{\"tag\":\"production\",\"consumer\":\"my-mobile-consumer\"}]"', $arguments);
        $this->assertContains('--loglevel=info', $arguments);
        $this->assertContains('--request-timeout=500', $arguments);
        $this->assertContains('--url=http://example.com/consumer1-provider1.json', $arguments);
        $this->assertContains('--url=http://example.com/consumer2-provider1.json', $arguments);
        $this->assertContains('--dir=/path/to/pacts', $arguments);
        $this->assertContains('--dir=/another/path/to/pacts', $arguments);
        $this->assertContains('--file=consumer1-provider2.json', $arguments);
        $this->assertContains('--file=consumer2-provider2.json', $arguments);
        $this->assertContains('--filter-consumer=consumer1', $arguments);
        $this->assertContains('--filter-consumer=consumer2', $arguments);
        $this->assertContains('--filter-description="Send POST to create"', $arguments);
        $this->assertContains('--filter-no-state=state1', $arguments);
        $this->assertContains('--filter-state=state2', $arguments);
        $this->assertContains('--disable-ssl-verification', $arguments);
        $this->assertContains('--enable-pending', $arguments);
        $this->assertContains('--publish', $arguments);
        $this->assertContains('--state-change-as-query', $arguments);
        $this->assertContains('--state-change-teardown', $arguments);
    }

    /**
     * @throws NoInstallerFoundException
     * @throws LibrariesNotInstalledException
     */
    public function testGetArgumentsEmptyConfig(): void
    {
        $this->assertEmpty((new Verifier(new VerifierConfig()))->getArguments());
    }

    /**
     * @throws NoInstallerFoundException
     * @throws LibrariesNotInstalledException
     */
    public function testVerify(): void
    {
        $provider = new Process(['php', '-S', 'localhost:8000', '-t', __DIR__ . '/../../_resources/provider/public']);
        $provider->start();
        $provider->waitUntil(function ($type, $output) {
            return false !== \strpos($output, 'Development Server (http://localhost:8000) started');
        });

        $config = new VerifierConfig();
        $config
            ->setDirs(__DIR__ . '/../../_resources/pacts')
            ->setPort(8000)
            ->setProviderName('someProvider')
            ->setProviderVersion('1.0.0');

        $verifier = new Verifier($config);

        $this->assertTrue($verifier->verify());
    }
}
