<?php

namespace PhpPact\Standalone;

/**
 * Class Scripts.
 */
class Scripts
{
    /**
     * @return string
     */
    public static function getCode(): string
    {
        return __DIR__ . '/../../../bin/pact-ffi-headers/pact.h';
    }

    /**
     * @return string
     */
    public static function getLibrary(): string
    {
        $prefix = PHP_OS_FAMILY === 'Windows' ? 'pact_ffi' : 'libpact_ffi';
        $os = PHP_OS === 'Darwin' ? 'osx' : strtolower(PHP_OS_FAMILY);
        $architecture = in_array(php_uname('m'), ['arm64', 'aarch64']) ? (PHP_OS === 'Darwin' ? 'aarch64-apple-darwin' : 'aarch64') : 'x86_64';
        $extension = PHP_OS_FAMILY === 'Windows' ? 'dll' : (PHP_OS === 'Darwin' ? 'dylib' : 'so');

        return __DIR__ . "/../../../bin/pact-ffi-lib/{$prefix}-{$os}-{$architecture}.{$extension}";
    }

    /**
     * @return string
     */
    public static function getStubService(): string
    {
        $os = PHP_OS === 'Darwin' ? 'osx' : strtolower(PHP_OS_FAMILY);
        $extension = PHP_OS_FAMILY === 'Windows' ? '.exe' : '';

        return __DIR__ . "/../../../bin/pact-stub-server/pact-stub-server-{$os}-x86_64{$extension}";
    }

    /**
     * @return string
     */
    public static function getBroker(): string
    {
        return __DIR__ . '/../../../bin/pact-ruby-standalone/bin/pact-broker' . (PHP_OS_FAMILY === 'Windows' ? '.bat' : '');
    }

    /**
     * @return string
     */
    public static function getPluginsDir(): string
    {
        return __DIR__ . '/../../../bin/pact-plugins';
    }

    /**
     * @return void
     */
    public static function makeStubServiceExecutable(): void
    {
        static::makeBinaryExecutable(static::getStubService());
    }

    /**
     * @return void
     */
    public static function makeCsvPluginExecutable(): void
    {
        static::makeBinaryExecutable(static::getPlugin('csv'));
    }

    /**
     * @return void
     */
    public static function makeProtobufPluginExecutable(): void
    {
        static::makeBinaryExecutable(static::getPlugin('protobuf'));
    }

    /**
     * @return void
     */
    public static function correctCsvPluginEntrypoint(): void
    {
        static::correctPluginEntrypoint('csv');
    }

    /**
     * @return void
     */
    public static function correctProtobufPluginEntrypoint(): void
    {
        static::correctPluginEntrypoint('protobuf');
    }

    /**
     * @param string $path
     */
    protected static function makeBinaryExecutable(string $path): void
    {
        chmod($path, 0777 & ~umask());
    }

    /**
     * @param string $name
     */
    protected static function correctPluginEntrypoint(string $name): void
    {
        $metadataPath = static::getPluginMetadata($name);
        $metadata = \json_decode(\file_get_contents($metadataPath), true);
        if (($metadata['entryPoint'] ?? null) === "pact-$name-plugin") {
            // not replaced.
            $metadata['entryPoint'] = static::getPlugin($name, true);
            \file_put_contents($metadataPath, \json_encode($metadata, JSON_PRETTY_PRINT));
        }
    }

    /**
     * @param string $name
     * @param bool   $entrypoint
     *
     * @return string
     */
    protected static function getPlugin(string $name, bool $entrypoint = false): string
    {
        $os = PHP_OS === 'Darwin' ? 'osx' : strtolower(PHP_OS_FAMILY);
        $architecture = in_array(php_uname('m'), ['arm64', 'aarch64']) ? 'aarch64' : 'x86_64';
        $extension = PHP_OS_FAMILY === 'Windows' ? '.exe' : '';

        if ($entrypoint) {
            return "bin/pact-{$name}-plugin-{$os}-{$architecture}";
        }

        return __DIR__ . "/../../../bin/pact-plugins/{$name}/bin/pact-{$name}-plugin-{$os}-{$architecture}{$extension}";
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected static function getPluginMetadata(string $name): string
    {
        return __DIR__ . "/../../../bin/pact-plugins/{$name}/pact-plugin.json";
    }
}
