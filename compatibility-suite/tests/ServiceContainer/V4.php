<?php

namespace PhpPactTest\CompatibilitySuite\ServiceContainer;

use PhpPactTest\CompatibilitySuite\Service\SyncMessageGeneratorBuilder;
use PhpPactTest\CompatibilitySuite\Service\SyncMessagePactWriter;

class V4 extends V3
{
    public function __construct()
    {
        parent::__construct();
        $this->set('sync_message_pact_writer', new SyncMessagePactWriter($this->getSpecification()));
        $this->set('sync_message_generator_builder', new SyncMessageGeneratorBuilder(
            $this->get('generator_parser'),
            $this->get('generator_converter')
        ));
    }

    protected function getSpecification(): string
    {
        return '4.0.0';
    }
}
