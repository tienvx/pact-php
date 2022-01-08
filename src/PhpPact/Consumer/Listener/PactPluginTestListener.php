<?php

namespace PhpPact\Consumer\Listener;

use PhpPact\Standalone\Scripts;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;
use PHPUnit\Framework\TestSuite;

/**
 * Class PactPluginTestListener
 */
class PactPluginTestListener implements TestListener
{
    use TestListenerDefaultImplementation;

    public function startTestSuite(TestSuite $suite): void
    {
        $pluginDir = \getenv('PACT_PLUGIN_DIR');
        if ($pluginDir === false) {
            $pluginDir = realpath(Scripts::getPluginsDir());
            \putenv("PACT_PLUGIN_DIR=$pluginDir");
        }
    }
}
