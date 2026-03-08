<?php

namespace PhpPactTest\CompatibilitySuite\Service;

use JsonPath\JsonObject;
use PhpPact\Consumer\Model\Body\Text;
use PhpPact\SyncMessage\Model\SyncMessage;
use PhpPactTest\CompatibilitySuite\Exception\IntegrationJsonFormatException;

final class SyncMessageGeneratorBuilder implements SyncMessageGeneratorBuilderInterface
{
    public function __construct(
        private GeneratorParserInterface $parser,
        private GeneratorConverterInterface $converter
    ) {
    }

    public function buildRequest(SyncMessage $message, string $generatorsJson): void
    {
        foreach ($this->parser->parse($generatorsJson) as $generator) {
            match ($generator->getCategory()) {
                'body' => $this->applyBodyGenerator($message, $generator),
                'metadata' => $this->applyMetadataGenerator($message, $generator, true),
                default => null,
            };
        }
    }

    public function buildResponse(SyncMessage $message, string $generatorsJson): void
    {
        foreach ($this->parser->parse($generatorsJson) as $generator) {
            match ($generator->getCategory()) {
                'body' => $this->applyResponseGenerator($message, $generator),
                'metadata' => $this->applyMetadataGenerator($message, $generator, false),
                default => null,
            };
        }
    }

    private function applyBodyGenerator(SyncMessage $message, object $generator): void
    {
        $body = $message->getRequestContents();
        if ($body instanceof Text && $body->getContentType() === 'application/json') {
            $jsonObject = new JsonObject($body->getContents(), true);
            $jsonObject->{$generator->getSubCategory()} = $this->converter->convert($generator);
            $body->setContents($jsonObject);
        } else {
            throw new IntegrationJsonFormatException("Integration JSON format doesn't support non-JSON format");
        }
    }

    private function applyResponseGenerator(SyncMessage $message, object $generator): void
    {
        $responseList = $message->getResponseContentsList();
        if (!empty($responseList)) {
            $body = $responseList[array_key_last($responseList)];
            if ($body instanceof Text && $body->getContentType() === 'application/json') {
                $jsonObject = new JsonObject($body->getContents(), true);
                $jsonObject->{$generator->getSubCategory()} = $this->converter->convert($generator);
                $body->setContents($jsonObject);
            }
        }
    }

    private function applyMetadataGenerator(SyncMessage $message, object $generator, bool $isRequest): void
    {
        $key = $generator->getSubCategory();
        if (!$key) {
            return;
        }

        $matcher = $this->converter->convert($generator);

        if ($isRequest) {
            $metadata = $message->getRequestMetadata();
            $metadata[$key] = json_encode($matcher);
            $message->setRequestMetadata($metadata);
        } else {
            $metadata = $message->getResponseMetadata();
            $metadata[$key] = json_encode($matcher);
            $message->setResponeMetadata($metadata);
        }
    }
}
