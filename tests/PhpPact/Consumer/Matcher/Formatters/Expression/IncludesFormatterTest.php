<?php

namespace PhpPactTest\Consumer\Matcher\Formatters\Expression;

use PhpPact\Consumer\Matcher\Exception\InvalidValueException;
use PhpPact\Consumer\Matcher\Exception\MatcherNotSupportedException;
use PhpPact\Consumer\Matcher\Formatters\Expression\IncludesFormatter;
use PhpPact\Consumer\Matcher\Matchers\Includes;
use PhpPact\Consumer\Matcher\Matchers\NullValue;
use PhpPact\Consumer\Matcher\Model\FormatterInterface;
use PhpPact\Consumer\Matcher\Model\MatcherInterface;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

class IncludesFormatterTest extends TestCase
{
    private FormatterInterface $formatter;

    protected function setUp(): void
    {
        $this->formatter = new IncludesFormatter();
    }

    public function testNotSupportedMatcher(): void
    {
        $matcher = new NullValue();
        $this->expectException(MatcherNotSupportedException::class);
        $this->expectExceptionMessage(sprintf('Matcher %s is not supported by %s', $matcher->getType(), $this->formatter::class));
        $this->formatter->format($matcher);
    }

    #[TestWith([new Includes("it's invalid value"), "it's invalid value"])]
    public function testInvalidString(MatcherInterface $matcher, string $value): void
    {
        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessage(sprintf('String value "%s" should not contains single quote', $value));
        $this->formatter->format($matcher);
    }

    #[TestWith([new Includes('example value'), '"matching(include, \'example value\')"'])]
    public function testFormat(MatcherInterface $matcher, string $expression): void
    {
        $this->assertSame($expression, json_encode($this->formatter->format($matcher)));
    }
}
