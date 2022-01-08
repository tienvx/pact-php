<?php

namespace PhpPact\Consumer\Exception;

use Exception;

/**
 * Specification < 4.0 does not support plugin.
 * Class PluginNotSupportedBySpecificationException.
 */
class PluginNotSupportedBySpecificationException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message, 0, null);
    }
}
