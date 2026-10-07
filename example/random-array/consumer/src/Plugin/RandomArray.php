<?php

namespace RandomArrayConsumer\Plugin;

use PhpPact\Consumer\Matcher\Model\Attributes;
use PhpPact\Consumer\Matcher\Model\Generator\JsonFormattableInterface;
use PhpPact\Consumer\Matcher\Model\GeneratorInterface;

/**
 * The RandomArray generator provided by the 'randomarray' Pact plugin.
 *
 * It is a structure generator: it expands the array it is applied to into a random number of
 * items between min and max, each a clone of the template item. Data generators on the item
 * fields then give each clone its own generated values.
 *
 * @see https://github.com/tienvx/pact-random-array-plugin
 */
class RandomArray implements GeneratorInterface, JsonFormattableInterface
{
    public function __construct(private readonly int $min = 1, private readonly int $max = 5)
    {
    }

    public function formatJson(): Attributes
    {
        return new Attributes([
            'pact:generator:type' => 'RandomArray',
            'min' => $this->min,
            'max' => $this->max,
        ]);
    }
}
