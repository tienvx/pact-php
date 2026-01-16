<?php

namespace PhpPact\Consumer\Service;

interface WaiterInterface
{
    public function waitUntil(callable $callback): bool;
}
