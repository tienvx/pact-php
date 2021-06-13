# CHANGELOG.md

## 8.0.0 (unreleased)

Removed:

  - `InteractionBuilder::finalize`
  - `BuilderInterface::writePact`
  - `PactConfigInterface::getLog` and `PactConfigInterface::setLog`
  - `PactConfigInterface::getLogLevel` and `PactConfigInterface::setLogLevel`
  - Environment variables:
    - PACT_LOG
    - PACT_MOCK_SERVER_HOST
    - PACT_MOCK_SERVER_PORT
    - PACT_CORS
    - PACT_MOCK_SERVER_HEALTH_CHECK_TIMEOUT
    - PACT_MOCK_SERVER_HEALTH_CHECK_RETRY_SEC
    - PACT_BROKER_SSL_VERIFY
    - PACT_PROVIDER_NAME
  - Class `StubServerHttpService`
  - Interface `StubServerHttpServiceInterface`
  - Class `ConnectionException`
  - Class `GuzzleClient`
  - Interface `ClientInterface`
  - Namespace `PhpPact\Standalone`
  - `PactMessageConfig`
  - `examples/tests/MessageProvider`

Changed:
  - `ConsumerConfig` replace `MockServerConfig`
  - `ConsumerEnvConfig` replace `MockServerEnvConfig`
  - `ConsumerRequest::setBody` only accept string
  - `ProviderResponse::setBody` only accept string
  - `ConsumerRequest::addHeader` does not accept multiple values as array
  - `ConsumerRequest::addQueryParameter` does not accept multiple values as array
  - `ProviderResponse::addHeader` does not accept multiple values as array
  - `./lib` replace `./pact` as directory to install libraries
