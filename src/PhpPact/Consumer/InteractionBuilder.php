<?php

namespace PhpPact\Consumer;

use PhpPact\Consumer\Model\ConsumerRequest;
use PhpPact\Consumer\Model\Interaction;
use PhpPact\Consumer\Model\Pact;
use PhpPact\Consumer\Model\ProviderResponse;
use PhpPact\Standalone\MockService\MockServerConfigInterface;

/**
 * Class InteractionBuilder.
 */
class InteractionBuilder implements BuilderInterface
{
    protected Pact $pact;
    protected Interaction $interaction;

    /**
     * InteractionBuilder constructor.
     *
     * @param MockServerConfigInterface $config
     */
    public function __construct(MockServerConfigInterface $config)
    {
        $this->pact = new Pact($config);
        $this->interaction = new Interaction();
    }

    /**
     * @param string $providerState what is given to the request
     *
     * @return InteractionBuilder
     */
    public function given(string $providerState): self
    {
        $this->interaction->setProviderState($providerState);

        return $this;
    }

    /**
     * @param string $description what is received when the request is made
     *
     * @return InteractionBuilder
     */
    public function uponReceiving(string $description): self
    {
        $this->interaction->setDescription($description);

        return $this;
    }

    /**
     * @param ConsumerRequest $request mock of request sent
     *
     * @return InteractionBuilder
     */
    public function with(ConsumerRequest $request): self
    {
        $this->interaction->setRequest($request);

        return $this;
    }

    /**
     * @param ProviderResponse $response mock of response received
     *
     * @return bool returns true on success
     */
    public function willRespondWith(ProviderResponse $response): bool
    {
        $this->interaction->setResponse($response);

        return $this->pact->registerInteraction($this->interaction);
    }

    /**
     * {@inheritdoc}
     */
    public function verify(): bool
    {
        return $this->pact->verifyInteractions();
    }

    public function createMockServer(): void
    {
        $this->pact->createMockServer();
    }
}
