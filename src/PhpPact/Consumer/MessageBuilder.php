<?php

namespace PhpPact\Consumer;

use Exception;
use PhpPact\Ffi\Helper;

/**
 * Build a message and send it to the Pact Rust FFI
 * Class MessageBuilder.
 */
class MessageBuilder extends AbstractBuilder
{
    protected array $callback;

    /**
     * {@inheritdoc}
     */
    public function newInteraction(string $description = ''): self
    {
        $this->interaction = $this->ffi->pactffi_new_message_interaction($this->pact, $description);

        return $this;
    }

    /**
     * Retrieve the verification call back
     *
     * @param callable     $callback
     * @param false|string $description of the callback in case of multiple
     *
     * @return MessageBuilder
     */
    public function setCallback(callable $callback, $description = false): self
    {
        if ($description) {
            $this->callback[$description] = $callback;
        } else {
            $this->callback[0] = $callback;
        }

        return $this;
    }

    /**
     * @param string $description what is given to the request
     * @param array  $params      for that request
     *
     * @return MessageBuilder
     */
    public function given(string $description, array $params = []): self
    {
        if (\count($params) > 0) {
            foreach ($params as $name => $value) {
                $this->ffi->pactffi_message_given_with_param($this->interaction, $description, (string) $name, $value);
            }
        } else {
            $this->ffi->pactffi_message_given($this->interaction, $description);
        }

        return $this;
    }

    /**
     * @param string $description what is received when the request is made
     *
     * @return MessageBuilder
     */
    public function expectsToReceive(string $description): self
    {
        $this->ffi->pactffi_message_expects_to_receive($this->interaction, $description);

        return $this;
    }

    /**
     * @param array $metadata what is the additional metadata of the message
     *
     * @return MessageBuilder
     */
    public function withMetadata(array $metadata): self
    {
        foreach ($metadata as $key => $value) {
            $this->ffi->pactffi_message_with_metadata($this->interaction, $key, $value);
        }

        return $this;
    }

    /**
     * Make the http request to the Mock Service to register the message.  Content is required.
     *
     * @param string $contents required to be in the message
     * @param string $contentType
     *
     * @return self
     */
    public function withContent(string $contents, string $contentType = 'text/plain'): self
    {
        if ($this->usingPlugin && \json_decode($contents) !== null) {
            $this->ffi->pactffi_interaction_contents($this->interaction, $this->ffi->InteractionPart_Request, $contentType, $contents);
        } else {
            $contents = Helper::getString($contents);
            $this->ffi->pactffi_message_with_contents($this->interaction, $contentType, $contents->getValue(), $contents->getSize());
        }

        return $this;
    }

    /**
     * Run reify to create an example pact from the message (i.e. create messages from matchers)
     *
     * @return string
     */
    public function reify(): string
    {
        return $this->ffi->pactffi_message_reify($this->interaction);
    }

    /**
     * Wrapper around verify()
     *
     * @param callable     $callback
     * @param false|string $description description of the pact and thus callback
     *
     * @throws Exception
     *
     * @return bool
     */
    public function verifyMessage(callable $callback, $description = false): bool
    {
        $this->setCallback($callback, $description);

        return $this->verify();
    }

    /**
     * Verify the use of the pact by calling the callback
     * It also calls finalize to write the pact
     *
     * @throws Exception if callback is not set
     *
     * @return bool
     */
    public function verify(): bool
    {
        if (\count($this->callback) < 1) {
            throw new \Exception('Callbacks need to exist to run verify.');
        }

        $contents = $this->reify();

        // call the function to actually run the logic
        try {
            foreach ($this->callback as $callback) {
                //@todo .. what do with the providerState
                \call_user_func($callback, $contents);
            }

            return !$this->ffi->pactffi_pact_handle_write_file($this->pact, $this->config->getPactDir(), false);
        } catch (\Exception $e) {
            return false;
        } finally {
            $this->ffi->pactffi_cleanup_plugins($this->pact);
            $this->ffi->pactffi_free_pact_handle($this->pact);
        }
    }
}
