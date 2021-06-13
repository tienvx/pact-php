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

    public static function makeStubServiceExecutable(): void
    {
        chmod(static::getStubService(), 0777 & ~umask());
    }
}
