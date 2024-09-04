<?php

namespace PhpPact\Plugins\FormUrlEncoded\Driver\Pact;

use PhpPact\Plugin\Driver\Pact\AbstractPluginPactDriver;

class FormUrlEncodedPactDriver extends AbstractPluginPactDriver
{
    protected function getPluginName(): string
    {
        return 'form-urlencoded';
    }
}
