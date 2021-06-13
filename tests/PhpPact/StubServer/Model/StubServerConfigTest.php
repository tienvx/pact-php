<?php

namespace PhpPact\StubServer\Model;

use PHPUnit\Framework\TestCase;

class StubServerConfigTest extends TestCase
{
    public function testSetters()
    {
        $pactLocation = __DIR__ . '/../../../_resources/pacts';
        $port         = 1234;
        $logLevel     = 'debug';

        $subject = (new StubServerConfig())
            ->setDirs($pactLocation)
            ->setPort($port)
            ->setLogLevel($logLevel);

        static::assertSame([$pactLocation], $subject->getDirs());
        static::assertSame($port, $subject->getPort());
        static::assertSame($logLevel, $subject->getLogLevel());
    }
}
