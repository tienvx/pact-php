<?php

namespace PhpPact\Consumer\Service;

class Waiter implements WaiterInterface
{
    public function __construct(private readonly int $timeout, private readonly int $duration = 25000)
    {
    }

    public function waitUntil(callable $callback): bool
    {
        $end = microtime(true) + $this->timeout;
        while (microtime(true) <= $end) {
            $result = $callback();
            if ($result) {
                return true;
            }
            usleep($this->duration);
        }
        return false;
    }
}
