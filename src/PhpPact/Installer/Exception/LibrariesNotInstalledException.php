<?php

namespace PhpPact\Installer\Exception;

use Exception;

/**
 * Unable to get scripts. Libraries are not installed.
 * Class BinariesNotInstalledException.
 */
class LibrariesNotInstalledException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message, 0, null);
    }
}
