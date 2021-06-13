<?php

namespace PhpPact\Consumer\Model;

use PhpPact\Consumer\Exception\MessagePactFileNotWroteException;
use PhpPact\Consumer\Model\Message;
use PhpPact\Ffi\Helper;
use PhpPact\Standalone\PactConfigInterface;

class MessagePact extends AbstractPact
{
    /**
     * @param PactConfigInterface $config
     */
    public function __construct(private PactConfigInterface $config)
    {
        parent::__construct();
        $this->id = $this->ffi->pactffi_new_message_pact($config->getConsumer(), $config->getProvider());
    }

    /**
     * @param Message $message
     *
     * @return string
     */
    public function reify(Message $message): string
    {
        $message->setId($this->ffi->pactffi_new_message($this->id, ''));
        $this
            ->given($message)
            ->expectsToReceive($message)
            ->withMetadata($message)
            ->withContent($message);

        return $this->ffi->pactffi_message_reify($message->getId());
    }

    /**
     * Update a pact with the given message, or create the pact if it does not exist.
     *
     * @return bool
     */
    public function update(): bool
    {
        $error = $this->ffi->pactffi_write_message_pact_file(
            $this->id,
            $this->config->getPactDir(),
            $this->config->getPactFileWriteMode() === PactConfigInterface::MODE_OVERWRITE
        );
        $this->ffi->pactffi_free_message_pact_handle($this->id);
        if ($error) {
            $message = match ($error) {
                1 => 'The pact file was not able to be written',
                2 => 'The message pact for the given handle was not found',
            };
            throw new MessagePactFileNotWroteException($message);
        }

        return !$error;
    }

    private function given(Message $message): self
    {
        foreach ($message->getProviderStates() as $providerState) {
            foreach ($providerState->params as $key => $value) {
                $this->ffi->pactffi_message_given_with_param($message->getId(), $providerState->name, (string) $key, $value);
            }
        }

        return $this;
    }

    private function expectsToReceive(Message $message): self
    {
        $this->ffi->pactffi_message_expects_to_receive($message->getId(), $message->getDescription());

        return $this;
    }

    private function withMetadata(Message $message): self
    {
        foreach ($message->getMetadata() as $key => $value) {
            $this->ffi->pactffi_message_with_metadata($message->getId(), (string) $key, $value);
        }

        return $this;
    }

    private function withContent(Message $message): self
    {
        if (\is_string($message->getContents())) {
            $contents    = $message->getContents();
            $contentType = 'text/plain';
        } else {
            $contents    = \json_encode($message->getContents());
            $contentType = 'application/json';
        }

        $contents = Helper::getString($contents);
        $this->ffi->pactffi_message_with_contents($message->getId(), $contentType, $contents->getValue(), $contents->getSize());

        return $this;
    }
}
