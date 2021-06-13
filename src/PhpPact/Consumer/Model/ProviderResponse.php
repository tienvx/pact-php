<?php

namespace PhpPact\Consumer\Model;

/**
 * Response expectation that would be in response to a Consumer request from the Provider.
 * Class ProviderResponse.
 */
class ProviderResponse
{
    private int $status;
    private ?string $body  = null;
    private array $headers = [];

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return ProviderResponse
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;

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
     * @return ProviderResponse
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
     * @return ProviderResponse
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
     * @return ProviderResponse
     */
    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }
}
