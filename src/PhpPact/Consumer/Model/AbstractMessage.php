<?php

namespace PhpPact\Consumer\Model;

/**
 * Class AbstractMessage.
 */
abstract class AbstractMessage
{
    protected ?string $body       = null;
    protected string $contentType = 'text/plain';
    protected array $headers      = [];

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     *
     * @return $this
     */
    public function setHeaders(array $headers): self
    {
        $this->headers = [];
        foreach ($headers as $header => $values) {
            $this->addHeader($header, $values);
        }

        return $this;
    }

    /**
     * @param string       $header
     * @param array|string $values
     *
     * @return $this
     */
    public function addHeader(string $header, $values): self
    {
        $this->headers[$header] = [];
        if (\is_array($values)) {
            foreach ($values as $value) {
                $this->addHeaderValue($header, $value);
            }
        } else {
            $this->addHeaderValue($header, $values);
        }

        return $this;
    }

    /**
     * @return null|string
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * @param string $body
     *
     * @return $this
     */
    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @param string $contentType Will be ignored if a content type header is already set.
     *
     * @return $this
     */
    public function setContentType(string $contentType): self
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     * @param string $header
     * @param string $value
     */
    protected function addHeaderValue(string $header, string $value): void
    {
        $this->headers[$header][] = $value;
    }
}
