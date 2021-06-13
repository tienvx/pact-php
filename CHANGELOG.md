CHANGELOG
=========

9.0
---

* Exceptions
  * Added `InteractionRequestBodyNotAddedException`
  * Added `InteractionResponseBodyNotAddedException`
  * Added `MessagePactFileNotWroteException`
  * Added `MockServerNotStartedException`
  * Added `PactFileNotWroteException`

* Config
  * [BC BREAK] Updated `PhpPact\Standalone\MockService\MockServerConfigInterface`, removed these methods:
    * `hasCors`
    * `setCors`
    * `setHealthCheckTimeout`
    * `getHealthCheckTimeout`
    * `setHealthCheckRetrySec`
    * `getHealthCheckRetrySec`
  * Added `PhpPact\Standalone\PactConfig`
  * Moved these methods from `PhpPact\Standalone\MockService\MockServerConfigInterface` to `PhpPact\Standalone\PactConfigInterface`:
    * `getPactFileWriteMode`
    * `setPactFileWriteMode`

* Mock Server
  * [BC BREAK] Removed `PhpPact\Standalone\MockService\MockServer`
  * Removed `MockServerHttpService`
  * Removed `MockServerHttpServiceInterface`
  * Removed `HealthCheckFailedException`

* Interaction Builder
  * Added `PhpPact\Consumer\InteractionBuilder::createMockServer`
  * [BC BREAK] Removed `PhpPact\Consumer\BuilderInterface::writePact`
  * [BC BREAK] Removed `PhpPact\Consumer\InteractionBuilder::writePact`
  * [BC BREAK] Removed `PhpPact\Consumer\InteractionBuilder::finalize`
  * [BC BREAK] `PhpPact\Consumer\Model\ConsumerRequest::getQuery` now return `array` instead of `string`
  * [BC BREAK] `PhpPact\Consumer\Model\ConsumerRequest::setQuery` now accept argument `array` instead of `string`

* Message Builder
  * [BC BREAK] Removed `PhpPact\Consumer\BuilderInterface::writePact`
  * [BC BREAK] Removed `PhpPact\Consumer\MessageBuilder::writePact`
  * Rename `PhpPact\Standalone\PactMessage\PactMessage` to `PhpPact\Consumer\Model\MessagePact`

* Verifier
  * Removed `VerifierProcess`
  * Removed `ProcessRunnerFactory`
  * Removed `MessageVerifier`, use `Verifier` instead
  * [BC BREAK] Split `PhpPact\Standalone\ProviderVerifier\Model\VerifierConfig` into:
    * `PhpPact\Standalone\ProviderVerifier\Model\Config\ConsumerFilters`
    * `PhpPact\Standalone\ProviderVerifier\Model\Config\FilterInfo`
    * `PhpPact\Standalone\ProviderVerifier\Model\Config\ProviderInfo`
    * `PhpPact\Standalone\ProviderVerifier\Model\Config\ProviderState`
    * `PhpPact\Standalone\ProviderVerifier\Model\Config\PublishOptions`
    * `PhpPact\Standalone\ProviderVerifier\Model\Config\VerificationOptions`
  * [BC BREAK] Split `PhpPact\Standalone\ProviderVerifier\Model\VerifierConfigInterface` into:
    * `PhpPact\Standalone\ProviderVerifier\Model\Config\ConsumerFiltersInterface`
    * `PhpPact\Standalone\ProviderVerifier\Model\Config\FilterInfoInterface`
    * `PhpPact\Standalone\ProviderVerifier\Model\Config\ProviderInfoInterface`
    * `PhpPact\Standalone\ProviderVerifier\Model\Config\ProviderStateInterface`
    * `PhpPact\Standalone\ProviderVerifier\Model\Config\PublishOptionsInterface`
    * `PhpPact\Standalone\ProviderVerifier\Model\Config\VerificationOptionsInterface`
  * [BC BREAK] Updated `PhpPact\Standalone\ProviderVerifier\Verifier`:
    * Removed parameters for method `verify`
    * Removed methods
      * `verifyFiles`
      * `verifyAll`
      * `verifyAllForTag`
      * `verifyFromConfig`
      * `registerInstaller`
      * `getTimeoutValues`
    * Added methods
      * `addFile`
      * `addDirectory`
      * `addUrl`
      * `addBroker`

* Stub Server
  * [BC BREAK] Updated `StubServerConfigInterface`, see [pact-stub-server](https://github.com/pact-foundation/pact-stub-server)
    * Removed methods:
      * `getHost`
      * `setHost`
      * `isSecure`
      * `setSecure`
      * `getLog`
      * `setLog`
      * `getPactLocation`
      * `setPactLocation`
    * Added methods:
      * `getBrokerUrl`
        * `getToken`
      * `setBrokerUrl`
        * `setToken`
      * `setDirs`
      * `getDirs`
      * `setFiles`
      * `getFiles`
      * `setUrls`
        * `setUser`
      * `getUrls`
        * `getUser`
      * `getExtension`
      * `setExtension`
      * `getLogLevel`
      * `setLogLevel`
      * `getProviderState`
      * `setProviderState`
      * `isEmptyProviderState`
      * `setEmptyProviderState`
      * `getProviderStateHeaderName`
      * `setProviderStateHeaderName`
      * `isCors`
      * `setCors`
      * `isCorsReferer`
      * `setCorsReferer`
      * `isInsecureTls`
      * `setInsecureTls`
      * `setConsumerNames`
      * `getConsumerNames`
      * `setProviderNames`
      * `getProviderNames`

* Environments Variables
  * Removed:
    - PACT_CORS
    - PACT_MOCK_SERVER_HEALTH_CHECK_TIMEOUT
    - PACT_MOCK_SERVER_HEALTH_CHECK_RETRY_SEC
    - PACT_BROKER_SSL_VERIFY

* Removed `BrokerHttpClient`
* Removed `BrokerHttpClientInterface`
* Removed `ContractDownloader`
