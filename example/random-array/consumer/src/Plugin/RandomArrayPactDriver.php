<?php

namespace RandomArrayConsumer\Plugin;

use PhpPact\Plugin\Driver\Pact\AbstractPluginPactDriver;

/**
 * Pact driver which loads the 'randomarray' plugin (installed under ~/.pact/plugins)
 * and records it in the pact file metadata, so the verifier loads it too.
 */
class RandomArrayPactDriver extends AbstractPluginPactDriver
{
    protected function getPluginName(): string
    {
        return 'randomarray';
    }
}
