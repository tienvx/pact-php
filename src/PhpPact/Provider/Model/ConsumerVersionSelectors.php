<?php

namespace PhpPact\Provider\Model;

/**
 * Class ConsumerVersionSelectors.
 */
class ConsumerVersionSelectors
{
    private array $selectors = [];

    public function __toString()
    {
        return \json_encode($this->selectors);
    }

    /**
     * @param null|string $tag
     * @param bool        $latest
     * @param null|string $consumer
     * @param null|string $fallbackTag
     */
    public function add(?string $tag = null, bool $latest = true, ?string $consumer = null, ?string $fallbackTag = null): void
    {
        $selector = \array_filter([
            'tag'         => $tag,
            'latest'      => $latest,
            'consumer'    => $consumer,
            'fallbackTag' => $fallbackTag,
        ]);
        if (!empty($selector)) {
            $this->selectors[] = $selector;
        }
    }
}
