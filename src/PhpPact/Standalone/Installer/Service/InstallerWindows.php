<?php

namespace PhpPact\Standalone\Installer\Service;

use PhpPact\Standalone\Installer\Model\Scripts;

/**
 * Class InstallerWindows.
 */
class InstallerWindows extends AbstractInstaller
{
    public const FILES = [
        [
            'repo'          => 'pact-ruby-standalone',
            'filename'      => 'pact-' . self::PACT_RUBY_STANDALONE_VERSION . '-win32.zip',
            'version'       => self::PACT_RUBY_STANDALONE_VERSION,
            'versionPrefix' => 'v',
            'extract'       => true,
        ],
        [
            'repo'          => 'pact-stub-server',
            'filename'      => 'pact-stub-server-windows-x86_64.exe.gz',
            'version'       => self::PACT_STUB_SERVER_VERSION,
            'versionPrefix' => 'v',
            'extract'       => true,
            'extractTo'     => 'pact-stub-server.exe',
            'executable'    => true,
        ],
        [
            'repo'          => 'pact-reference',
            'filename'      => 'pact_ffi-windows-x86_64.dll.gz',
            'version'       => self::PACT_FFI_VERSION,
            'versionPrefix' => 'libpact_ffi-v',
            'extract'       => true,
            'extractTo'     => 'pact_ffi.dll',
        ],
        [
            'repo'          => 'pact-plugins',
            'filename'      => 'pact-plugin-csv-windows-x86_64.exe.gz',
            'version'       => self::PACT_CSV_PLUGIN_VERSION,
            'versionPrefix' => 'csv-plugin-',
            'moveTo'        => 'plugins' . DIRECTORY_SEPARATOR . 'csv-' . self::PACT_CSV_PLUGIN_VERSION,
            'extract'       => true,
            'extractTo'     => 'pact-plugin-csv.exe',
            'executable'    => true,
        ],
        [
            'org'           => 'pactflow',
            'repo'          => 'pact-protobuf-plugin',
            'filename'      => 'pact-protobuf-plugin-windows-x86_64.exe.gz',
            'version'       => self::PACT_PROTOBUF_PLUGIN_VERSION,
            'versionPrefix' => 'v-',
            'moveTo'        => 'plugins' . DIRECTORY_SEPARATOR . 'protobuf-' . self::PACT_PROTOBUF_PLUGIN_VERSION,
            'extract'       => true,
            'extractTo'     => 'pact-protobuf-plugin.exe',
            'executable'    => true,
        ],
        ...parent::FILES,
    ];

    /**
     * {@inheritdoc}
     */
    public function isEligible(): bool
    {
        return PHP_OS_FAMILY === 'Windows';
    }

    /**
     * {@inheritdoc}
     */
    protected function getScripts(string $destinationDir): Scripts
    {
        $destinationDir = $destinationDir . DIRECTORY_SEPARATOR;
        $binDir         = $destinationDir . 'pact' . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR;

        return new Scripts(
            $destinationDir . 'pact.h',
            $destinationDir . 'pact_ffi.dll',
            $destinationDir . 'pact-stub-server.exe',
            $binDir . 'pact-broker.bat'
        );
    }
}
