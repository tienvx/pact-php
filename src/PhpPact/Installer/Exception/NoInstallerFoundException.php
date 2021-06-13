<?php

namespace PhpPact\Installer\Exception;

use Exception;

/**
 * Unable to find a installer to get the binaries.
 * Class NoDownloaderFoundException.
 */
class NoInstallerFoundException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message, 0, null);
    }
}
