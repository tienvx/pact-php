<?php

namespace PhpPact\Consumer\Matcher\Matchers;

use PhpPact\Consumer\Matcher\Model\MatcherInterface;

/**
 * This executes a type based match against the values, that is, they are equal if they are the same type.
 * In addition, if the values represent a collection, the length of the actual value is compared against the maximum.
 */
class MaxType implements MatcherInterface
{
    /**
     * @param array<mixed>$values
     */
    public function __construct(
        private array $values,
        private int $max,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'pact:matcher:type' => $this->getType(),
            'max'               => $this->max,
            'value'             => array_values($this->values),
        ];
    }

    public function getType(): string
    {
        return 'type';
    }
}
