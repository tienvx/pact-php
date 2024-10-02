<?php

namespace PhpPact\Consumer\Matcher\Matchers;

use PhpPact\Consumer\Matcher\Formatters\Expression\DateTimeFormatter;
use PhpPact\Consumer\Matcher\Formatters\Json\HasGeneratorFormatter;
use PhpPact\Consumer\Matcher\Model\ExpressionFormatterInterface;
use PhpPact\Consumer\Matcher\Model\JsonFormatterInterface;

abstract class AbstractDateTime extends GeneratorAwareMatcher
{
    public function __construct(protected readonly string $format, private readonly ?string $value = null)
    {
        parent::__construct();
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @return array<string, string>
     */
    protected function getAttributesData(): array
    {
        return ['format' => $this->format];
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function createJsonFormatter(): JsonFormatterInterface
    {
        return new HasGeneratorFormatter();
    }

    public function createExpressionFormatter(): ExpressionFormatterInterface
    {
        return new DateTimeFormatter();
    }
}
