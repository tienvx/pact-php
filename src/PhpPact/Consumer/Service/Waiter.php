<?php

namespace PhpPact\Consumer\Service;

class Waiter implements WaiterInterface
{
    public function __construct(private readonly int $timeout, private readonly int $duration = 25000)
    {
    }

    public function waitUntil(callable $callback, callable $check): mixed
    {
        $end = microtime(true) + $this->timeout;
        do {
            $result = $callback();
            if ($check($result)) {
                return $result;
            }
            usleep($this->duration);
        } while (microtime(true) <= $end);
        return $result;
    }
}
