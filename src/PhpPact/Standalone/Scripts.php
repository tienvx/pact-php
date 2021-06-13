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
        $extension = PHP_OS_FAMILY === 'Windows' ? 'dll' : (PHP_OS === 'Darwin' ? 'dylib' : 'so');

        return __DIR__ . "/../../../bin/pact-ffi-lib/pact.{$extension}";
    }

    /**
     * @return string
     */
    public static function getStubService(): string
    {
        $extension = PHP_OS_FAMILY === 'Windows' ? '.exe' : '';

        return __DIR__ . "/../../../bin/pact-stub-server/pact-stub-server{$extension}";
    }

    /**
     * @return string
     */
    public static function getBroker(): string
    {
        return __DIR__ . '/../../../bin/pact-ruby-standalone/bin/pact-broker' . (PHP_OS_FAMILY === 'Windows' ? '.bat' : '');
    }
}
