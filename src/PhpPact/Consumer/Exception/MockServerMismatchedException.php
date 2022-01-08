<?php

namespace PhpPact\Consumer\Exception;

use Exception;

/**
 * Mock server is not matched.
 * Class MockServerMismatchedException.
 */
class MockServerMismatchedException extends Exception
{
    public function __construct(string $mismatches)
    {
        parent::__construct($mismatches, 0, null);
    }
}
