<?php

namespace PhpPact\Provider\Model;

use Psr\Http\Message\UriInterface;

/**
 * Class VerifierConfig.
 */
class VerifierConfig implements VerifierConfigInterface
{
    private ?string $basePath                 = null;
    private ?string $scheme                   = null;
    private ?string $host                     = null;
    private ?int $port                        = null;
    private ?UriInterface $stateChangeUrl     = null;
    private ?string $providerName             = null;
    private ?string $providerVersion          = null;
    private ?string $providerTags             = null;
    private ?string $consumerVersionTags      = null;
    private ?UriInterface $brokerUrl          = null;
    private ?string $brokerToken              = null;
    private ?string $brokerUsername           = null;
    private ?string $brokerPassword           = null;
    private ?string $includeWipPactsSince     = null;
    private ?UriInterface $buildUrl           = null;
    private ?string $consumerVersionSelectors = null;
    private ?string $logLevel                 = null;
    private ?int $requestTimeout              = null;

    private array $urls  = [];
    private array $dirs  = [];
    private array $files = [];

    private array $filterConsumerNames = [];
    private ?string $filterDescription = null;
    private ?string $filterNoState     = null;
    private ?string $filterState       = null;

    private bool $publish                = false;
    private bool $disableSslVerification = false;
    private bool $enablePending          = false;
    private bool $stateChangeAsQuery     = false;
    private bool $stateChangeTeardown    = false;

    /**
     * {@inheritdoc}
     */
    public function getBasePath(): ?string
    {
        return $this->basePath;
    }

    /**
     * {@inheritdoc}
     */
    public function setBasePath(string $basePath): VerifierConfigInterface
    {
        $this->basePath = $basePath;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getScheme(): ?string
    {
        return $this->scheme;
    }

    /**
     * {@inheritdoc}
     */
    public function setScheme(string $scheme): VerifierConfigInterface
    {
        $this->scheme = $scheme;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getHost(): ?string
    {
        return $this->host;
    }

    /**
     * {@inheritdoc}
     */
    public function setHost(string $host): VerifierConfigInterface
    {
        $this->host = $host;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * {@inheritdoc}
     */
    public function setPort(int $port): VerifierConfigInterface
    {
        $this->port = $port;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getStateChangeUrl(): ?UriInterface
    {
        return $this->stateChangeUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function setStateChangeUrl(UriInterface $stateChangeUrl): VerifierConfigInterface
    {
        $this->stateChangeUrl = $stateChangeUrl;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getProviderName(): ?string
    {
        return $this->providerName;
    }

    /**
     * {@inheritdoc}
     */
    public function setProviderName(string $providerName): VerifierConfigInterface
    {
        $this->providerName = $providerName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getProviderVersion(): ?string
    {
        return $this->providerVersion;
    }

    /**
     * {@inheritdoc}
     */
    public function setProviderVersion(string $providerVersion): VerifierConfigInterface
    {
        $this->providerVersion = $providerVersion;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getProviderTags(): ?string
    {
        return $this->providerTags;
    }

    /**
     * {@inheritdoc}
     */
    public function setProviderTags(string $providerTags): VerifierConfigInterface
    {
        $this->providerTags = $providerTags;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getConsumerVersionTags(): ?string
    {
        return $this->consumerVersionTags;
    }

    /**
     * @param string $consumerVersionTags
     *
     * @return VerifierConfigInterface
     */
    public function setConsumerVersionTags(string $consumerVersionTags): VerifierConfigInterface
    {
        $this->consumerVersionTags = $consumerVersionTags;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isPublish(): bool
    {
        return $this->publish;
    }

    /**
     * {@inheritdoc}
     */
    public function setPublish(bool $publish): VerifierConfigInterface
    {
        $this->publish = $publish;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBrokerUrl(): ?UriInterface
    {
        return $this->brokerUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function setBrokerUrl(UriInterface $brokerUrl): VerifierConfigInterface
    {
        $this->brokerUrl = $brokerUrl;

        return $this;
    }

    /**
     * {@inheritdoc}}
     */
    public function getBrokerToken(): ?string
    {
        return $this->brokerToken;
    }

    /**
     * {@inheritdoc }
     */
    public function setBrokerToken(?string $brokerToken): VerifierConfigInterface
    {
        $this->brokerToken = $brokerToken;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBrokerUsername(): ?string
    {
        return $this->brokerUsername;
    }

    /**
     * {@inheritdoc}
     */
    public function setBrokerUsername(string $brokerUsername): VerifierConfigInterface
    {
        $this->brokerUsername = $brokerUsername;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBrokerPassword(): ?string
    {
        return $this->brokerPassword;
    }

    /**
     * {@inheritdoc}
     */
    public function setBrokerPassword(string $brokerPassword): VerifierConfigInterface
    {
        $this->brokerPassword = $brokerPassword;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isDisableSslVerification(): bool
    {
        return $this->disableSslVerification;
    }

    /**
     * {@inheritdoc}
     */
    public function setDisableSslVerification(bool $disableSslVerification): VerifierConfigInterface
    {
        $this->disableSslVerification = $disableSslVerification;

        return $this;
    }

    /**
     * @param bool $stateChangeAsQuery
     *
     * @return VerifierConfigInterface
     */
    public function setStateChangeAsQuery(bool $stateChangeAsQuery): VerifierConfigInterface
    {
        $this->stateChangeAsQuery = $stateChangeAsQuery;

        return $this;
    }

    /**
     * @return bool
     */
    public function isStateChangeAsQuery(): bool
    {
        return $this->stateChangeAsQuery;
    }

    /**
     * @param bool $stateChangeTeardown
     *
     * @return VerifierConfigInterface
     */
    public function setStateChangeTeardown(bool $stateChangeTeardown): VerifierConfigInterface
    {
        $this->stateChangeTeardown = $stateChangeTeardown;

        return $this;
    }

    /**
     * @return bool
     */
    public function isStateChangeTeardown(): bool
    {
        return $this->stateChangeTeardown;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnablePending(): bool
    {
        return $this->enablePending;
    }

    /**
     * {@inheritdoc}
     */
    public function setEnablePending(bool $enablePending): VerifierConfigInterface
    {
        $this->enablePending = $enablePending;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setIncludeWipPactsSince(string $includeWipPactsSince): VerifierConfigInterface
    {
        $this->includeWipPactsSince = $includeWipPactsSince;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getIncludeWipPactsSince(): ?string
    {
        return $this->includeWipPactsSince;
    }

    /**
     * {@inheritdoc}
     */
    public function getBuildUrl(): ?UriInterface
    {
        return $this->buildUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function setBuildUrl(UriInterface $buildUrl): VerifierConfigInterface
    {
        $this->buildUrl = $buildUrl;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setConsumerVersionSelectors(string $consumerVersionSelectors): VerifierConfigInterface
    {
        $this->consumerVersionSelectors = $consumerVersionSelectors;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getConsumerVersionSelectors(): ?string
    {
        return $this->consumerVersionSelectors;
    }

    /**
     * {@inheritdoc}
     */
    public function setLogLevel(string $logLevel): VerifierConfigInterface
    {
        $this->logLevel = $logLevel;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLogLevel(): ?string
    {
        return $this->logLevel;
    }

    /**
     * {@inheritdoc}
     */
    public function setRequestTimeout(int $requestTimeout): VerifierConfigInterface
    {
        $this->requestTimeout = $requestTimeout;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestTimeout(): ?int
    {
        return $this->requestTimeout;
    }

    /**
     * {@inheritdoc}
     */
    public function setUrls(string ...$urls): VerifierConfigInterface
    {
        $this->urls = $urls;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrls(): array
    {
        return $this->urls;
    }

    /**
     * {@inheritdoc}
     */
    public function setDirs(string ...$dirs): VerifierConfigInterface
    {
        $this->dirs = $dirs;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDirs(): array
    {
        return $this->dirs;
    }

    /**
     * {@inheritdoc}
     */
    public function setFiles(string ...$files): VerifierConfigInterface
    {
        $this->files = $files;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * {@inheritdoc}
     */
    public function setFilterConsumerNames(string ...$filterConsumerNames): VerifierConfigInterface
    {
        $this->filterConsumerNames = $filterConsumerNames;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilterConsumerNames(): array
    {
        return $this->filterConsumerNames;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilterDescription(): ?string
    {
        return $this->filterDescription;
    }

    /**
     * {@inheritdoc}
     */
    public function setFilterDescription(string $filterDescription): VerifierConfigInterface
    {
        $this->filterDescription = $filterDescription;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilterNoState(): ?string
    {
        return $this->filterNoState;
    }

    /**
     * {@inheritdoc}
     */
    public function setFilterNoState(string $filterNoState): VerifierConfigInterface
    {
        $this->filterNoState = $filterNoState;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilterState(): ?string
    {
        return $this->filterState;
    }

    /**
     * {@inheritdoc}
     */
    public function setFilterState(string $filterState): VerifierConfigInterface
    {
        $this->filterState = $filterState;

        return $this;
    }
}
