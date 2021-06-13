<?php

namespace PhpPact\Provider;

use FFI;
use PhpPact\Installer\Exception\LibrariesNotInstalledException;
use PhpPact\Installer\Exception\NoInstallerFoundException;
use PhpPact\Installer\InstallManager;
use PhpPact\Provider\Model\VerifierConfigInterface;

/**
 * Class Verifier.
 */
class Verifier
{
    protected FFI $ffi;
    protected VerifierConfigInterface $config;

    /**
     * Verifier constructor.
     *
     * @param VerifierConfigInterface $config
     *
     * @throws LibrariesNotInstalledException
     * @throws NoInstallerFoundException
     */
    public function __construct(VerifierConfigInterface $config)
    {
        $this->config   = $config;
        $scripts        = (new InstallManager())->getScripts();
        $this->ffi      = FFI::cdef(\file_get_contents($scripts->getCode()), $scripts->getLibrary());
        $this->ffi->pactffi_init('PACT_LOGLEVEL');
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        $parameters = [];

        if ($this->config->getBasePath() !== null) {
            $parameters[] = "--base-path={$this->config->getBasePath()}";
        }

        if ($this->config->getScheme() !== null) {
            $parameters[] = "--scheme={$this->config->getScheme()}";
        }

        if ($this->config->getHost() !== null) {
            $parameters[] = "--hostname={$this->config->getHost()}";
        }

        if ($this->config->getPort() !== null) {
            $parameters[] = "--port={$this->config->getPort()}";
        }

        if ($this->config->getProviderName() !== null) {
            $parameters[] = "--provider-name={$this->config->getProviderName()}";
        }

        if ($this->config->getProviderVersion() !== null) {
            $parameters[] = "--provider-version={$this->config->getProviderVersion()}";
        }

        if ($this->config->getConsumerVersionTags() !== null) {
            $parameters[] = "--consumer-version-tags={$this->config->getConsumerVersionTags()}";
        }

        if ($this->config->getProviderTags() !== null) {
            $parameters[] = "--provider-tags={$this->config->getProviderTags()}";
        }

        if ($this->config->getStateChangeUrl() !== null) {
            $parameters[] = "--state-change-url={$this->config->getStateChangeUrl()}";
        }

        if ($this->config->getBrokerUrl() !== null) {
            $parameters[] = "--broker-url={$this->config->getBrokerUrl()}";
        }

        if ($this->config->getBrokerToken() !== null) {
            $parameters[] = "--token={$this->config->getBrokerToken()}";
        }

        if ($this->config->getBrokerUsername() !== null) {
            $parameters[] = "--user={$this->config->getBrokerUsername()}";
        }

        if ($this->config->getBrokerPassword() !== null) {
            $parameters[] = "--password={$this->config->getBrokerPassword()}";
        }

        if ($this->config->getIncludeWipPactsSince() !== null) {
            $parameters[] = "--include-wip-pacts-since={$this->config->getIncludeWipPactsSince()}";
        }

        if ($this->config->getBuildUrl() !== null) {
            $parameters[] = "--build-url={$this->config->getBuildUrl()}";
        }

        if ($this->config->getConsumerVersionSelectors() !== null) {
            $selectors    = \addslashes($this->config->getConsumerVersionSelectors());
            $parameters[] = "--consumer-version-selectors=\"{$selectors}\"";
        }

        if ($this->config->getLogLevel() !== null) {
            $parameters[] = "--loglevel={$this->config->getLogLevel()}";
        }

        if ($this->config->getRequestTimeout() !== null) {
            $parameters[] = "--request-timeout={$this->config->getRequestTimeout()}";
        }

        foreach ($this->config->getUrls() as $url) {
            $parameters[] = "--url={$url}";
        }

        foreach ($this->config->getDirs() as $dir) {
            $parameters[] = "--dir={$dir}";
        }

        foreach ($this->config->getFiles() as $file) {
            $parameters[] = "--file={$file}";
        }

        foreach ($this->config->getFilterConsumerNames() as $consumer) {
            $parameters[] = "--filter-consumer={$consumer}";
        }

        if ($this->config->getFilterDescription() !== null) {
            $description  = \addslashes($this->config->getFilterDescription());
            $parameters[] = "--filter-description=\"{$description}\"";
        }

        if ($this->config->getFilterNoState() !== null) {
            $parameters[] = "--filter-no-state={$this->config->getFilterNoState()}";
        }

        if ($this->config->getFilterState() !== null) {
            $parameters[] = "--filter-state={$this->config->getFilterState()}";
        }

        if ($this->config->isDisableSslVerification() === true) {
            $parameters[] = '--disable-ssl-verification';
        }

        if ($this->config->isEnablePending() === true) {
            $parameters[] = '--enable-pending';
        }

        if ($this->config->isPublish() === true) {
            $parameters[] = '--publish';
        }

        if ($this->config->isStateChangeAsQuery() === true) {
            $parameters[] = '--state-change-as-query';
        }

        if ($this->config->isStateChangeTeardown() === true) {
            $parameters[] = '--state-change-teardown';
        }

        return $parameters;
    }

    /**
     * Verifier a provider.
     *
     * @return bool
     */
    public function verify(): bool
    {
        return !$this->ffi->pactffi_verify(\implode(PHP_EOL, $this->getArguments()));
    }
}
