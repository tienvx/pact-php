<?php

namespace PhpPact\Installer;

use PhpPact\Installer\Exception\LibrariesNotInstalledException;
use PhpPact\Installer\Exception\NoInstallerFoundException;
use PhpPact\Installer\Model\Scripts;
use PhpPact\Installer\Service\InstallerInterface;
use PhpPact\Installer\Service\InstallerLinux;
use PhpPact\Installer\Service\InstallerMac;
use PhpPact\Installer\Service\InstallerWindows;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Manage Ruby Standalone binaries.
 * Class BinaryManager.
 */
class InstallManager
{
    /** @var InstallerInterface[] */
    private array $installers = [];

    /**
     * Destination directory for PACT folder.
     */
    private static string $destinationDir = __DIR__ . '/../../../lib';

    public function __construct()
    {
        $this
            ->registerInstaller(new InstallerWindows())
            ->registerInstaller(new InstallerMac())
            ->registerInstaller(new InstallerLinux());
    }

    /**
     * Add a single installer.
     *
     * @param InstallerInterface $installer
     *
     * @return InstallManager
     */
    public function registerInstaller(InstallerInterface $installer): self
    {
        $this->installers[] = $installer;

        return $this;
    }

    /**
     * Overwrite default installers.
     *
     * @param array $installers
     *
     * @return InstallManager
     */
    public function setInstallers(array $installers): self
    {
        $this->installers = $installers;

        return $this;
    }

    /**
     * @throws Exception\FileDownloadFailureException
     * @throws NoInstallerFoundException
     */
    public static function install(): void
    {
        $manager   = new self();
        $installer = $manager->getEligibleInstaller();

        if (\file_exists((self::$destinationDir)) === false) {
            \mkdir(self::$destinationDir);
            $installer->install(self::$destinationDir);
        }
    }

    /**
     * @throws LibrariesNotInstalledException
     * @throws NoInstallerFoundException
     *
     * @return Scripts
     */
    public function getScripts(): Scripts
    {
        $installer = $this->getEligibleInstaller();

        if (\file_exists((self::$destinationDir)) === false) {
            throw new LibrariesNotInstalledException('Unable to get scripts. Libraries are not installed');
        }

        return $installer->getScripts(self::$destinationDir);
    }

    /**
     * Uninstall.
     */
    public static function uninstall(): void
    {
        if (\file_exists(self::$destinationDir)) {
            (new Filesystem)->remove(self::$destinationDir);
        }
    }

    /**
     * Get the first installer that meets the systems eligibility.
     *
     * @throws NoInstallerFoundException
     *
     * @return InstallerInterface
     */
    private function getEligibleInstaller(): InstallerInterface
    {
        /**
         * Reverse the order of the installers so that the ones added last are checked first.
         *
         * @var InstallerInterface[]
         */
        $installers = \array_reverse($this->installers);
        foreach ($installers as $installer) {
            /** @var InstallerInterface $installer */
            if ($installer->isEligible()) {
                return $installer;
            }
        }

        throw new NoInstallerFoundException('No eligible installer found for Mock Server binaries.');
    }
}
