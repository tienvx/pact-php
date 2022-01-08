<?php

namespace PhpPact\Standalone\Installer\Service;

use PhpPact\Standalone\Installer\Model\Scripts;

/**
 * Class InstallerMac.
 */
class InstallerMac extends AbstractInstaller
{
    public const FILES = [
        [
            'repo'          => 'pact-ruby-standalone',
            'filename'      => 'pact-' . self::PACT_RUBY_STANDALONE_VERSION . '-osx.tar.gz',
            'version'       => self::PACT_RUBY_STANDALONE_VERSION,
            'versionPrefix' => 'v',
            'extract'       => true,
        ],
        [
            'repo'          => 'pact-stub-server',
            'filename'      => 'pact-stub-server-osx-x86_64.gz',
            'version'       => self::PACT_STUB_SERVER_VERSION,
            'versionPrefix' => 'v',
            'extract'       => true,
            'extractTo'     => 'pact-stub-server',
            'executable'    => true,
        ],
        [
            'repo'          => 'pact-reference',
            'filename'      => 'libpact_ffi-osx-x86_64.dylib.gz',
            'version'       => self::PACT_FFI_VERSION,
            'versionPrefix' => 'libpact_ffi-v',
            'extract'       => true,
            'extractTo'     => 'libpact_ffi.dylib',
        ],
        [
            'repo'          => 'pact-plugins',
            'filename'      => 'pact-plugin-csv-osx-x86_64.gz',
            'version'       => self::PACT_CSV_PLUGIN_VERSION,
            'versionPrefix' => 'csv-plugin-',
            'moveTo'        => 'plugins' . DIRECTORY_SEPARATOR . 'csv-' . self::PACT_CSV_PLUGIN_VERSION,
            'extract'       => true,
            'extractTo'     => 'pact-plugin-csv',
            'executable'    => true,
        ],
        [
            'org'           => 'pactflow',
            'repo'          => 'pact-protobuf-plugin',
            'filename'      => 'pact-protobuf-plugin-osx-x86_64.gz',
            'version'       => self::PACT_PROTOBUF_PLUGIN_VERSION,
            'versionPrefix' => 'v-',
            'moveTo'        => 'plugins' . DIRECTORY_SEPARATOR . 'protobuf-' . self::PACT_PROTOBUF_PLUGIN_VERSION,
            'extract'       => true,
            'extractTo'     => 'pact-protobuf-plugin',
            'executable'    => true,
        ],
        ...parent::FILES,
    ];

    /**
     * {@inheritdoc}
     */
    public function isEligible(): bool
    {
        return PHP_OS === 'Darwin';
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
            $destinationDir . 'libpact_ffi.dylib',
            $destinationDir . 'pact-stub-server',
            $binDir . 'pact-broker'
        );
    }
}
