<?php

namespace PhpPact\Consumer\Matcher\Generators;

abstract class AbstractDateTime extends AbstractGenerator
{
    public function __construct(private readonly ?string $format = null, private readonly ?string $expression = null)
    {
    }

    /**
     * @return array<string, string>
     */
    protected function getAttributesData(): array
    {
        $data = [];
        if ($this->format !== null) {
            $data['format'] = $this->format;
        }

        if ($this->expression !== null) {
            $data['expression'] = $this->expression;
        }

        return $data;
    }
}
