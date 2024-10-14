<?php

namespace PhpPact\Consumer\Matcher\Matchers;

use PhpPact\Consumer\Matcher\Generators\RandomString;
use PhpPact\Consumer\Matcher\Model\Attributes;
use PhpPact\Consumer\Matcher\Model\Expression;
use PhpPact\Consumer\Matcher\Model\Matcher\ExpressionFormattableInterface;
use PhpPact\Consumer\Matcher\Model\Matcher\JsonFormattableInterface;
use PhpPact\Consumer\Matcher\Trait\JsonFormattableTrait;

/**
 * There is no matcher for string. We re-use `type` matcher.
 */
class StringValue extends GeneratorAwareMatcher implements JsonFormattableInterface, ExpressionFormattableInterface
{
    use JsonFormattableTrait;

    public const DEFAULT_VALUE = 'some string';

    public function __construct(private ?string $value = null)
    {
        if ($value === null) {
            $this->setGenerator(new RandomString());
        }
        parent::__construct();
    }

    public function formatJson(): Attributes
    {
        return $this->mergeJson(new Attributes([
            'pact:matcher:type' => 'type',
            'value' => $this->getValue(),
        ]));
    }

    public function formatExpression(): Expression
    {
        return new Expression('matching(type, %value%)', ['value' => $this->getValue()]);
    }

    private function getValue(): string
    {
        return $this->value ?? self::DEFAULT_VALUE;
    }
}
