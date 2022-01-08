<?php

namespace PhpPact\Consumer;

/**
 * Build a sync message and send it to the Pact Rust FFI
 * Class SyncMessageBuilder.
 */
class SyncMessageBuilder extends MessageBuilder
{
    /**
     * {@inheritdoc}
     */
    public function newInteraction(string $description = ''): self
    {
        $this->interaction = $this->ffi->pactffi_new_sync_message_interaction($this->pact, $description);

        return $this;
    }
}
