<?php

namespace PhpPact\StubServer;

use PhpPact\StubServer\Model\StubServerConfig;
use PHPUnit\Framework\TestCase;

class StubServerTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testStartAndStop()
    {
        try {
            $pactLocation = __DIR__ . '/../../_resources/pacts';
            $port         = 7201;

            $subject = (new StubServerConfig())
                ->setDirs($pactLocation)
                ->setPort($port);

            $stubServer = new StubServer($subject);
            $pid        = $stubServer->start();
            $this->assertTrue(\is_int($pid));
        } finally {
            $result = $stubServer->stop();
            $this->assertTrue($result);
        }
    }
}
