<?php

namespace PhpPact\FFI;

use FFI;
use FFI\CData;
use PhpPact\FFI\Exception\HeaderNotReadException;
use PhpPact\FFI\Exception\InvalidEnumException;
use PhpPact\FFI\Exception\InvalidResultException;
use PhpPact\FFI\Model\BinaryData;
use PhpPact\FFI\Model\Result;
use PhpPact\Standalone\Installer\Model\Scripts;

class Client implements ClientInterface
{
    private FFI $ffi;

    public function __construct()
    {
        $headerFile = Scripts::getHeader();
        $code = \file_get_contents($headerFile);
        if (!is_string($code)) {
            throw new HeaderNotReadException(sprintf('Can not read header file "%s"', $headerFile));
        }
        $this->ffi = FFI::cdef($code, Scripts::getLibrary());
    }

    public function withBinaryFile(int $interaction, int $part, string $contentType, BinaryData $data): bool
    {
        $method = 'pactffi_with_binary_file';
        $result = $this->call($method, $interaction, $part, $contentType, $data->getValue(), $data->getSize());
        if (!is_bool($result)) {
            throw new InvalidResultException(sprintf('Invalid result of "%s". Expected "boolean", but got "%s"', $method, get_debug_type($result)));
        }
        return $result;
    }

    public function withBody(int $interaction, int $part, string $contentType, string $contents): bool
    {
        $method = 'pactffi_with_body';
        $result = $this->call($method, $interaction, $part, $contentType, $contents);
        if (!is_bool($result)) {
            throw new InvalidResultException(sprintf('Invalid result of "%s". Expected "boolean", but got "%s"', $method, get_debug_type($result)));
        }
        return $result;
    }

    public function withMultipartFileV2(int $interaction, int $part, string $contentType, string $path, string $name, string $boundary): Result
    {
        $method = 'pactffi_with_multipart_file_v2';
        $result = $this->call($method, $interaction, $part, $contentType, $path, $name, $boundary);
        if (!$result instanceof CData) {
            throw new InvalidResultException(sprintf('Invalid result of "%s". Expected "%s", but got "%s"', $method, CData::class, get_debug_type($result)));
        }
        if ($result->tag === $this->getEnum('StringResult_Ok')) { // @phpstan-ignore-line
            return new Result(true, $result->ok instanceof CData ? FFI::string($result->ok) : ''); // @phpstan-ignore-line
        }
        if ($result->tag === $this->getEnum('StringResult_Failed')) { // @phpstan-ignore-line
            return new Result(false, $result->failed instanceof CData ? FFI::string($result->failed) : ''); // @phpstan-ignore-line
        }
        throw new InvalidResultException(sprintf('Invalid result of "%s". Neither ok or failed', $method));
    }

    public function setKey(int $interaction, string $key): bool
    {
        $method = 'pactffi_set_key';
        $result = $this->call($method, $interaction, $key);
        if (!is_bool($result)) {
            throw new InvalidResultException(sprintf('Invalid result of "%s". Expected "boolean", but got "%s"', $method, get_debug_type($result)));
        }
        return $result;
    }

    public function setPending(int $interaction, bool $pending): bool
    {
        $method = 'pactffi_set_pending';
        $result = $this->call($method, $interaction, $pending);
        if (!is_bool($result)) {
            throw new InvalidResultException(sprintf('Invalid result of "%s". Expected "boolean", but got "%s"', $method, get_debug_type($result)));
        }
        return $result;
    }

    public function setComment(int $interaction, string $key, ?string $value): bool
    {
        $method = 'pactffi_set_comment';
        $result = $this->call($method, $interaction, $key, $value);
        if (!is_bool($result)) {
            throw new InvalidResultException(sprintf('Invalid result of "%s". Expected "boolean", but got "%s"', $method, get_debug_type($result)));
        }
        return $result;
    }

    public function addTextComment(int $interaction, string $value): bool
    {
        $method = 'pactffi_add_text_comment';
        $result = $this->call($method, $interaction, $value);
        if (!is_bool($result)) {
            throw new InvalidResultException(sprintf('Invalid result of "%s". Expected "boolean", but got "%s"', $method, get_debug_type($result)));
        }
        return $result;
    }

    public function newInteraction(int $pact, string $description): int
    {
        $method = 'pactffi_new_interaction';
        $result = $this->call($method, $pact, $description);
        if (!is_int($result)) {
            throw new InvalidResultException(sprintf('Invalid result of "%s". Expected "integer", but got "%s"', $method, get_debug_type($result)));
        }
        return $result;
    }

    public function newMessageInteraction(int $pact, string $description): int
    {
        $method = 'pactffi_new_message_interaction';
        $result = $this->call($method, $pact, $description);
        if (!is_int($result)) {
            throw new InvalidResultException(sprintf('Invalid result of "%s". Expected "integer", but got "%s"', $method, get_debug_type($result)));
        }
        return $result;
    }

    public function newSyncMessageInteraction(int $pact, string $description): int
    {
        $method = 'pactffi_new_sync_message_interaction';
        $result = $this->call($method, $pact, $description);
        if (!is_int($result)) {
            throw new InvalidResultException(sprintf('Invalid result of "%s". Expected "integer", but got "%s"', $method, get_debug_type($result)));
        }
        return $result;
    }

    public function given(int $interaction, string $name): bool
    {
        $method = 'pactffi_given';
        $result = $this->call($method, $interaction, $name);
        if (!is_bool($result)) {
            throw new InvalidResultException(sprintf('Invalid result of "%s". Expected "boolean", but got "%s"', $method, get_debug_type($result)));
        }
        return $result;
    }

    public function givenWithParam(int $interaction, string $name, string $key, string $value): bool
    {
        $method = 'pactffi_given_with_param';
        $result = $this->call($method, $interaction, $name, $key, $value);
        if (!is_bool($result)) {
            throw new InvalidResultException(sprintf('Invalid result of "%s". Expected "boolean", but got "%s"', $method, get_debug_type($result)));
        }
        return $result;
    }

    public function uponReceiving(int $interaction, string $description): bool
    {
        $method = 'pactffi_upon_receiving';
        $result = $this->call($method, $interaction, $description);
        if (!is_bool($result)) {
            throw new InvalidResultException(sprintf('Invalid result of "%s". Expected "boolean", but got "%s"', $method, get_debug_type($result)));
        }
        return $result;
    }

    public function messageExpectsToReceive(int $message, string $description): void
    {
        $method = 'pactffi_message_expects_to_receive';
        $this->call($method, $message, $description);
    }

    public function messageWithMetadataV2(int $message, string $key, string $value): void
    {
        $method = 'pactffi_message_with_metadata_v2';
        $this->call($method, $message, $key, $value);
    }

    public function messageGiven(int $message, string $name): void
    {
        $method = 'pactffi_message_given';
        $this->call($method, $message, $name);
    }

    public function messageGivenWithParam(int $message, string $name, string $key, string $value): void
    {
        $method = 'pactffi_message_given_with_param';
        $this->call($method, $message, $name, $key, $value);
    }

    public function freePactHandle(int $pact): int
    {
        $method = 'pactffi_free_pact_handle';
        $result = $this->call($method, $pact);
        if (!is_int($result)) {
            throw new InvalidResultException(sprintf('Invalid result of "%s". Expected "integer", but got "%s"', $method, get_debug_type($result)));
        }
        return $result;
    }

    public function newPact(string $consumer, string $provider): int
    {
        $method = 'pactffi_new_pact';
        $result = $this->call($method, $consumer, $provider);
        if (!is_int($result)) {
            throw new InvalidResultException(sprintf('Invalid result of "%s". Expected "integer", but got "%s"', $method, get_debug_type($result)));
        }
        return $result;
    }

    public function withSpecification(int $pact, int $specification): bool
    {
        $method = 'pactffi_with_specification';
        $result = $this->call($method, $pact, $specification);
        if (!is_bool($result)) {
            throw new InvalidResultException(sprintf('Invalid result of "%s". Expected "boolean", but got "%s"', $method, get_debug_type($result)));
        }
        return $result;
    }

    public function initWithLogLevel(string $logLevel): void
    {
        $method = 'pactffi_init_with_log_level';
        $this->call($method, $logLevel);
    }

    public function pactHandleWriteFile(int $pact, string $directory, bool $overwrite): int
    {
        $method = 'pactffi_pact_handle_write_file';
        $result = $this->call($method, $pact, $directory, $overwrite);
        if (!is_int($result)) {
            throw new InvalidResultException(sprintf('Invalid result of "%s". Expected "integer", but got "%s"', $method, get_debug_type($result)));
        }
        return $result;
    }

    public function cleanupPlugins(int $pact): void
    {
        $method = 'pactffi_cleanup_plugins';
        $this->call($method, $pact);
    }

    public function usingPlugin(int $pact, string $name, ?string $version): int
    {
        $method = 'pactffi_using_plugin';
        $result = $this->call($method, $pact, $name, $version);
        if (!is_int($result)) {
            throw new InvalidResultException(sprintf('Invalid result of "%s". Expected "integer", but got "%s"', $method, get_debug_type($result)));
        }
        return $result;
    }

    public function cleanupMockServer(int $port): bool
    {
        $method = 'pactffi_cleanup_mock_server';
        $result = $this->call($method, $port);
        if (!is_bool($result)) {
            throw new InvalidResultException(sprintf('Invalid result of "%s". Expected "boolean", but got "%s"', $method, get_debug_type($result)));
        }
        return $result;
    }

    public function mockServerMatched(int $port): bool
    {
        $method = 'pactffi_mock_server_matched';
        $result = $this->call($method, $port);
        if (!is_bool($result)) {
            throw new InvalidResultException(sprintf('Invalid result of "%s". Expected "boolean", but got "%s"', $method, get_debug_type($result)));
        }
        return $result;
    }

    public function mockServerMismatches(int $port): string
    {
        $method = 'pactffi_mock_server_mismatches';
        $result = $this->call($method, $port);
        if ($result === null) {
            return '';
        }
        if (!$result instanceof CData) {
            throw new InvalidResultException(sprintf('Invalid result of "%s". Expected "%s", but got "%s"', $method, CData::class, get_debug_type($result)));
        }
        return FFI::string($result);
    }

    public function writePactFile(int $port, string $directory, bool $overwrite): int
    {
        $method = 'pactffi_write_pact_file';
        $result = $this->call($method, $port, $directory, $overwrite);
        if (!is_int($result)) {
            throw new InvalidResultException(sprintf('Invalid result of "%s". Expected "integer", but got "%s"', $method, get_debug_type($result)));
        }
        return $result;
    }

    public function createMockServerForTransport(int $pact, string $host, int $port, string $transport, ?string $transportConfig): int
    {
        $method = 'pactffi_create_mock_server_for_transport';
        $result = $this->call($method, $pact, $host, $port, $transport, $transportConfig);
        if (!is_int($result)) {
            throw new InvalidResultException(sprintf('Invalid result of "%s". Expected "integer", but got "%s"', $method, get_debug_type($result)));
        }
        return $result;
    }

    public function getInteractionPartRequest(): int
    {
        return $this->getEnum('InteractionPart_Request');
    }

    public function getInteractionPartResponse(): int
    {
        return $this->getEnum('InteractionPart_Response');
    }

    public function getPactSpecificationV1(): int
    {
        return $this->getEnum('PactSpecification_V1');
    }

    public function getPactSpecificationV1_1(): int
    {
        return $this->getEnum('PactSpecification_V1_1');
    }

    public function getPactSpecificationV2(): int
    {
        return $this->getEnum('PactSpecification_V2');
    }

    public function getPactSpecificationV3(): int
    {
        return $this->getEnum('PactSpecification_V3');
    }

    public function getPactSpecificationV4(): int
    {
        return $this->getEnum('PactSpecification_V4');
    }

    public function getPactSpecificationUnknown(): int
    {
        return $this->getEnum('PactSpecification_Unknown');
    }

    private function getEnum(string $name): int
    {
        $value = $this->get($name);
        if (!is_int($value)) {
            throw new InvalidEnumException(sprintf('Invalid enum "%s". Expected "int", but got "%s"', $name, get_debug_type($value)));
        }
        return $value;
    }

    public function call(string $name, ...$arguments): mixed
    {
        return $this->ffi->{$name}(...$arguments);
    }

    private function get(string $name): mixed
    {
        return $this->ffi->{$name};
    }
}
