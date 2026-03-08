<?php

namespace PhpPactTest\CompatibilitySuite\Service;

use PhpPact\SyncMessage\Model\SyncMessage;

interface SyncMessageGeneratorBuilderInterface
{
    public function buildRequest(SyncMessage $message, string $generatorsJson): void;

    public function buildResponse(SyncMessage $message, string $generatorsJson): void;
}
