<?php

namespace PhpPact\Provider\Model;

use Psr\Http\Message\UriInterface;

/**
 * Configuration to use with the verifier server.
 * Interface VerifierServerConfigInterface.
 */
interface VerifierConfigInterface
{
    /**
     * @return null|string providers base path
     */
    public function getBasePath(): ?string;

    /**
     * @param string $basePath providers base path
     *
     * @return VerifierConfigInterface
     */
    public function setBasePath(string $basePath): self;

    /**
     * @return null|string Provider URI scheme
     */
    public function getScheme(): ?string;

    /**
     * @param string $scheme Provider URI scheme
     *
     * @return VerifierConfigInterface
     */
    public function setScheme(string $scheme): self;

    /**
     * @return null|string providers host
     */
    public function getHost(): ?string;

    /**
     * @param string $host providers host
     *
     * @return VerifierConfigInterface
     */
    public function setHost(string $host): self;

    /**
     * @return null|int providers port
     */
    public function getPort(): ?int;

    /**
     * @param int $port providers port
     *
     * @return VerifierConfigInterface
     */
    public function setPort(int $port): self;

    /**
     * @return null|UriInterface Base URL to setup the provider states at
     */
    public function getStateChangeUrl(): ?UriInterface;

    /**
     * @param UriInterface $stateChangeUrl Base URL to setup the provider states at
     *
     * @return VerifierConfigInterface
     */
    public function setStateChangeUrl(UriInterface $stateChangeUrl): self;

    /**
     * @return null|string name of the provider
     */
    public function getProviderName(): ?string;

    /**
     * @param string $providerName Name of the provider
     *
     * @return VerifierConfigInterface
     */
    public function setProviderName(string $providerName): self;

    /**
     * @return null|string providers version
     */
    public function getProviderVersion(): ?string;

    /**
     * @param string $providerVersion providers version
     *
     * @return VerifierConfigInterface
     */
    public function setProviderVersion(string $providerVersion): self;

    /**
     * @return null|string providers version tag
     */
    public function getProviderTags(): ?string;

    /**
     * @param string $providerTags Provider tags to use when publishing results. Accepts comma-separated values.
     *
     * @return VerifierConfigInterface
     */
    public function setProviderTags(string $providerTags): self;

    /**
     * @return null|string consumers version tags
     */
    public function getConsumerVersionTags(): ?string;

    /**
     * Consumer tags to use when fetching pacts from the Broker. Accepts comma-separated values.
     *
     * @param string $consumerVersionTags
     *
     * @return VerifierConfigInterface
     */
    public function setConsumerVersionTags(string $consumerVersionTags): self;

    /**
     * @return bool are results going to be published
     */
    public function isPublish(): bool;

    /**
     * Enables publishing of verification results back to the Pact Broker. Requires the broker-url and provider-version parameters.
     *
     * @param bool $publish
     *
     * @return VerifierConfigInterface
     */
    public function setPublish(bool $publish): self;

    /**
     * @return null|UriInterface url to the pact broker
     */
    public function getBrokerUrl(): ?UriInterface;

    /**
     * URL of the pact broker to fetch pacts from to verify (requires the provider name parameter)
     *
     * @param UriInterface $brokerUrl
     *
     * @return VerifierConfigInterface
     */
    public function setBrokerUrl(UriInterface $brokerUrl): self;

    /**
     * @return null|string token for the pact broker
     */
    public function getBrokerToken(): ?string;

    /**
     * @param null|string $brokerToken token for the pact broker
     *
     * @return VerifierConfigInterface
     */
    public function setBrokerToken(?string $brokerToken): self;

    /**
     * @return null|string username for the pact broker if secured
     */
    public function getBrokerUsername(): ?string;

    /**
     * @param string $brokerUsername username for the pact broker if secured
     *
     * @return VerifierConfigInterface
     */
    public function setBrokerUsername(string $brokerUsername): self;

    /**
     * @return null|string password for the pact broker if secured
     */
    public function getBrokerPassword(): ?string;

    /**
     * @param string $brokerPassword password for the pact broker if secured
     *
     * @return VerifierConfigInterface
     */
    public function setBrokerPassword(string $brokerPassword): self;

    /**
     * @return bool is verbosity level increased
     */
    public function isDisableSslVerification(): bool;

    /**
     * @param bool $disableSslVerification Disables validation of SSL certificates
     *
     * @return VerifierConfigInterface
     */
    public function setDisableSslVerification(bool $disableSslVerification): self;

    /**
     * @param bool $stateChangeAsQuery
     *
     * @return VerifierConfigInterface
     */
    public function setStateChangeAsQuery(bool $stateChangeAsQuery): self;

    /**
     * @return bool
     */
    public function isStateChangeAsQuery(): bool;

    /**
     * @param bool $stateChangeTeardown
     *
     * @return VerifierConfigInterface
     */
    public function setStateChangeTeardown(bool $stateChangeTeardown): self;

    /**
     * @return bool
     */
    public function isStateChangeTeardown(): bool;

    /**
     * @param bool $enablePending allow pacts which are in pending state to be verified without causing the overall task to fail
     *
     * @return VerifierConfigInterface
     */
    public function setEnablePending(bool $enablePending): self;

    /**
     * @return bool is enabled pending pacts
     */
    public function isEnablePending(): bool;

    /**
     * Allow pacts that don't match given consumer selectors (or tags) to  be verified, without causing the overall task to fail. For more information, see https://pact.io/wip
     *
     * @param string $includeWipPactsSince
     *
     * @return VerifierConfigInterface
     */
    public function setIncludeWipPactsSince(string $includeWipPactsSince): self;

    /**
     * @return null|string get start date of included WIP Pacts
     */
    public function getIncludeWipPactsSince(): ?string;

    /**
     * @return null|UriInterface
     */
    public function getBuildUrl(): ?UriInterface;

    /**
     * URL of the build to associate with the published verification results.
     *
     * @param UriInterface $buildUrl
     *
     * @return $this
     */
    public function setBuildUrl(UriInterface $buildUrl): self;

    /**
     * @param string $consumerVersionSelectors
     *
     * @return $this
     */
    public function setConsumerVersionSelectors(string $consumerVersionSelectors): self;

    /**
     * @return null|string
     */
    public function getConsumerVersionSelectors(): ?string;

    /**
     * @return null|string
     */
    public function getLogLevel(): ?string;

    /**
     * @param string $logLevel Log level (defaults to warn) [possible values: error, warn, info, debug, trace, none]
     *
     * @return $this
     */
    public function setLogLevel(string $logLevel): self;

    /**
     * Sets the HTTP request timeout in milliseconds for requests to the target API and for state change requests.
     *
     * @param int $requestTimeout
     *
     * @return $this
     */
    public function setRequestTimeout(int $requestTimeout): self;

    /**
     * @return null|int
     */
    public function getRequestTimeout(): ?int;

    /**
     * @param string ...$urls URL of pact file to verify
     *
     * @return $this
     */
    public function setUrls(string ...$urls): self;

    /**
     * @return array
     */
    public function getUrls(): array;

    /**
     * @param string ...$dirs Directory of pact files to verify
     *
     * @return $this
     */
    public function setDirs(string ...$dirs): self;

    /**
     * @return array
     */
    public function getDirs(): array;

    /**
     * @param string ...$files Pact file to verify
     *
     * @return $this
     */
    public function setFiles(string ...$files): self;

    /**
     * @return array
     */
    public function getFiles(): array;

    /**
     * @param string ...$filterConsumerNames Consumer name to filter the pacts to be verified
     *
     * @return $this
     */
    public function setFilterConsumerNames(string ...$filterConsumerNames): self;

    /**
     * @return array
     */
    public function getFilterConsumerNames(): array;

    /**
     * @param string $filterDescription Only validate interactions whose descriptions match this filter
     *
     * @return $this
     */
    public function setFilterDescription(string $filterDescription): self;

    /**
     * @return null|string
     */
    public function getFilterDescription(): ?string;

    /**
     * Only validate interactions that have no defined provider state
     *
     * @param string $filterNoState
     *
     * @return $this
     */
    public function setFilterNoState(string $filterNoState): self;

    /**
     * @return null|string
     */
    public function getFilterNoState(): ?string;

    /**
     * Only validate interactions whose provider states match this filter
     *
     * @param string $filterState
     *
     * @return $this
     */
    public function setFilterState(string $filterState): self;

    /**
     * @return null|string
     */
    public function getFilterState(): ?string;
}
