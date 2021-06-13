<?php

namespace PhpPact\Consumer\Model;

/**
 * Request initiated by the consumer.
 * Class ConsumerRequest.
 */
class ConsumerRequest
{
    private string $method;
    private string $path;
    private ?string $body  = null;
    private array $headers = [];
    private array $query   = [];

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     *
     * @return ConsumerRequest
     */
    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     *
     * @return ConsumerRequest
     */
    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

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
     * @return ConsumerRequest
     */
    public function setHeaders(array $headers): self
    {
        $this->headers = [];
        foreach ($headers as $header => $values) {
            $this->addHeader($header, ...$values);
        }

        return $this;
    }

    /**
     * @param string $header
     * @param string ...$values
     *
     * @return ConsumerRequest
     */
    public function addHeader(string $header, string ...$values): self
    {
        $this->headers[$header] = $values;

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
     * @return ConsumerRequest
     */
    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return array
     */
    public function getQuery(): array
    {
        return $this->query;
    }

    /**
     * @param array $query
     *
     * @return ConsumerRequest
     */
    public function setQuery(array $query): self
    {
        $this->query = [];
        foreach ($query as $key => $values) {
            $this->addQueryParameter($key, ...$values);
        }

        return $this;
    }

    /**
     * @param string $key
     * @param string ...$values
     *
     * @return ConsumerRequest
     */
    public function addQueryParameter(string $key, string ...$values): self
    {
        $this->query[$key] = $values;

        return $this;
    }
}
