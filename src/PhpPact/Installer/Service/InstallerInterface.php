<?php

namespace PhpPact\Installer\Service;

use PhpPact\Installer\Exception\FileDownloadFailureException;
use PhpPact\Installer\Model\Scripts;

/**
 * Interface BinaryDownloaderInterface.
 */
interface InstallerInterface
{
    /**
     * Verify if the downloader works for the current environment.
     *
     * @return bool
     */
    public function isEligible(): bool;

    /**
     * Download the file and install it in the necessary directory.
     *
     * @param string $destinationDir folder path to put the binaries
     *
     * @throws FileDownloadFailureException
     */
    public function install(string $destinationDir): void;

    /**
     * Get scripts.
     *
     * @param string $destinationDir folder path to get the binaries
     *
     * @return Scripts
     */
    public function getScripts(string $destinationDir): Scripts;
}
