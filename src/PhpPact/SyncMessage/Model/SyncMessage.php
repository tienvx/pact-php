<?php

namespace PhpPact\SyncMessage\Model;

use JsonException;
use PhpPact\Consumer\Exception\BodyNotSupportedException;
use PhpPact\Consumer\Matcher\Model\MatcherInterface;
use PhpPact\Consumer\Model\Body\Binary;
use PhpPact\Consumer\Model\Body\Multipart;
use PhpPact\Consumer\Model\Body\Text;
use PhpPact\Consumer\Model\Message;

/**
 * Message metadata and contents to be posted to the Mock Server for PACT tests.
 */
class SyncMessage extends Message
{
    private Text|Binary|null $requestContents = null;

    /**
     * @var array<Text|Binary|null>
     */
    private $responseContentsList = [];

    /**
     * @var array<string, string>
     */
    private array $requestMetadata = [];

    /**
     * @var array<string, string>
     */
    private array $responseMetadata = [];

    public function getRequestContents(): Text|Binary|null
    {
        return $this->requestContents;
    }

    /**
     * @throws JsonException
     */
    public function setRequestContents(mixed $contents): self
    {
        $this->requestContents = $this->convertContents($contents);

        return $this;
    }

    /**
     * @return array<Text|Binary|null>
     */
    public function getResponseContentsList(): array
    {
        return $this->responseContentsList;
    }

    /**
     * @param array<mixed> $contentsList
     */
    public function setResponseContentsList(array $contentsList): self
    {
        $this->responseContentsList = [];
        foreach ($contentsList as $value) {
            $this->addResponseContents($value);
        }

        return $this;
    }

    /**
     * @throws JsonException
     */
    public function addResponseContents(mixed $contents): self
    {
        $this->responseContentsList[] = $this->convertContents($contents);

        return $this;
    }

    /**
     * @throws JsonException
     */
    private function convertContents(mixed $contents): Text|Binary|null
    {
        if (\is_string($contents)) {
            return new Text($contents, 'text/plain');
        } elseif (\is_null($contents) || $contents instanceof Text || $contents instanceof Binary) {
            return $contents;
        } elseif ($contents instanceof Multipart) {
            throw new BodyNotSupportedException('Message does not support multipart');
        } else {
            return new Text(\json_encode($contents, JSON_THROW_ON_ERROR), 'application/json');
        }
    }

    /**
     * @return array<string, string>
     */
    public function getRequestMetadata(): array
    {
        return $this->requestMetadata;
    }

    /**
     * @param array<string, string|MatcherInterface> $requestMetadata
     */
    public function setRequestMetadata(array $requestMetadata): self
    {
        $this->requestMetadata = [];
        foreach ($requestMetadata as $key => $value) {
            $this->setRequestMetadataValue($key, $value);
        }

        return $this;
    }

    /**
     * @throws JsonException
     */
    private function setRequestMetadataValue(string $key, string|MatcherInterface $value): void
    {
        $this->requestMetadata[$key] = is_string($value) ? $value : json_encode($value, JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string, string>
     */
    public function getResponseMetadata(): array
    {
        return $this->responseMetadata;
    }

    /**
     * @param array<string, string|MatcherInterface> $responseMetadata
     */
    public function setResponeMetadata(array $responseMetadata): self
    {
        $this->responseMetadata = [];
        foreach ($responseMetadata as $key => $value) {
            $this->setResponeMetadataValue($key, $value);
        }

        return $this;
    }

    /**
     * @throws JsonException
     */
    private function setResponeMetadataValue(string $key, string|MatcherInterface $value): void
    {
        $this->responseMetadata[$key] = is_string($value) ? $value : json_encode($value, JSON_THROW_ON_ERROR);
    }
}
