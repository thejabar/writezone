<?php

declare(strict_types=1);

namespace App\Intelligence\Remote;

use Closure;
use JsonException;
use Throwable;

final class PythonClassificationClient
{
    private const CLASSIFICATION_PATH = '/internal/v1/classifications';
    private const TAXONOMY_VERSION = 'wz-topics-v1';
    private const SUPPORTED_LANGUAGE = 'en';
    private const RETRYABLE_TRANSPORT_FAILURES = [
        'transport_unavailable',
        'transport_failure',
        'transport_timeout',
    ];
    private const SAFE_TRANSPORT_FAILURES = [
        ...self::RETRYABLE_TRANSPORT_FAILURES,
        'response_too_large',
        'response_headers_too_large',
        'invalid_response_headers',
        'invalid_transport_request',
    ];

    private Closure $clock;
    private readonly bool $enabled;
    private readonly ?PythonClassificationClientConfig $settings;

    /**
     * @param array<string, mixed> $configuration
     * @param null|callable(): int $monotonicClock Returns nanoseconds.
     */
    public function __construct(
        private readonly HttpTransport $transport,
        array|PythonClassificationClientConfig $configuration,
        ?callable $monotonicClock = null,
        private readonly ?ClassificationResponseValidator $responseValidator = null
    ) {
        if ($configuration instanceof PythonClassificationClientConfig) {
            $this->enabled = $configuration->enabled;
            $this->settings = $configuration;
        } else {
            $this->enabled = ($configuration['enabled'] ?? false) === true;
            $this->settings = $this->enabled
                ? PythonClassificationClientConfig::fromArray($configuration)
                : null;
        }

        $this->clock = $monotonicClock === null
            ? static fn (): int => hrtime(true)
            : Closure::fromCallable($monotonicClock);
    }

    public function classify(
        string $text,
        string $language = self::SUPPORTED_LANGUAGE
    ): ClassificationOutcome {
        try {
            $startedAt = ($this->clock)();
            $requestId = self::newUuidV4();
        } catch (Throwable) {
            return ClassificationOutcome::unavailable(
                null,
                'request_initialization_failed',
                null,
                0
            );
        }

        if (! $this->enabled) {
            return $this->unavailable($requestId, 'feature_disabled', null, $startedAt);
        }

        if ($language !== self::SUPPORTED_LANGUAGE) {
            return $this->unavailable($requestId, 'unsupported_language', null, $startedAt);
        }

        $settings = $this->settings;

        if ($settings === null) {
            return $this->unavailable($requestId, 'configuration_unavailable', null, $startedAt);
        }

        $textFailure = $this->validateText($text, $settings);

        if ($textFailure !== null) {
            return $this->unavailable($requestId, $textFailure, null, $startedAt);
        }

        try {
            $body = json_encode(
                [
                    'schema_version' => 1,
                    'request_id' => $requestId,
                    'taxonomy_version' => self::TAXONOMY_VERSION,
                    'language' => self::SUPPORTED_LANGUAGE,
                    'text' => $text,
                ],
                JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
        } catch (JsonException) {
            return $this->unavailable($requestId, 'request_encoding_failed', null, $startedAt);
        }

        $deadline = $startedAt + ($settings->totalTimeoutMs * 1_000_000);
        $attempt = 0;

        while (true) {
            $remainingMilliseconds = intdiv($deadline - ($this->clock)(), 1_000_000);

            if ($remainingMilliseconds < 1) {
                return $this->unavailable(
                    $requestId,
                    'total_deadline_exceeded',
                    null,
                    $startedAt
                );
            }

            try {
                $response = $this->transport->send(
                    self::CLASSIFICATION_PATH,
                    [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json; charset=utf-8',
                        'Authorization' => 'Bearer ' . $settings->token,
                        'X-WZ-Key-Id' => $settings->keyId,
                    ],
                    $body,
                    min($settings->connectTimeoutMs, $remainingMilliseconds),
                    $remainingMilliseconds
                );
            } catch (Throwable) {
                $response = HttpTransportResponse::failure('transport_failure');
            }

            if (($this->clock)() >= $deadline) {
                return $this->unavailable(
                    $requestId,
                    'total_deadline_exceeded',
                    $response->statusCode > 0 ? $response->statusCode : null,
                    $startedAt
                );
            }

            $retryable = in_array(
                $response->failureCategory,
                self::RETRYABLE_TRANSPORT_FAILURES,
                true
            ) || (
                in_array($response->statusCode, [502, 503, 504], true)
                && self::hasSupportedJsonContentType($response)
            );

            if ($retryable && $attempt < $settings->maxRetries) {
                $attempt++;

                continue;
            }

            return $this->interpretResponse($response, $requestId, $startedAt);
        }
    }

    private function validateText(
        string $text,
        PythonClassificationClientConfig $settings
    ): ?string {
        if (preg_match('//u', $text) !== 1) {
            return 'invalid_input';
        }

        if ($text === '' || preg_match('/(*UCP)\A\s*\z/u', $text) === 1) {
            return 'invalid_input';
        }

        if (strlen($text) > $settings->maxTextBytes) {
            return 'input_too_large';
        }

        if (! function_exists('mb_strlen')) {
            return 'runtime_requirement_missing';
        }

        if (mb_strlen($text, 'UTF-8') > $settings->maxTextCodePoints) {
            return 'input_too_large';
        }

        return null;
    }

    private function interpretResponse(
        HttpTransportResponse $response,
        string $requestId,
        int $startedAt
    ): ClassificationOutcome {
        if ($response->failureCategory !== null) {
            $category = in_array(
                $response->failureCategory,
                self::SAFE_TRANSPORT_FAILURES,
                true
            ) ? $response->failureCategory : 'transport_failure';

            return $this->unavailable($requestId, $category, null, $startedAt);
        }

        if (! self::hasSupportedJsonContentType($response)) {
            return $this->unavailable(
                $requestId,
                'invalid_response',
                $response->statusCode > 0 ? $response->statusCode : null,
                $startedAt
            );
        }

        if ($response->statusCode !== 200) {
            $category = match ($response->statusCode) {
                401 => 'authentication_failed',
                502, 503, 504 => $this->validator()->isClassificationUnavailable($response->body)
                    ? 'classification_unavailable'
                    : 'upstream_unavailable',
                400, 413, 415, 422 => 'upstream_rejected_request',
                default => 'upstream_http_error',
            };

            return $this->unavailable(
                $requestId,
                $category,
                $response->statusCode > 0 ? $response->statusCode : null,
                $startedAt
            );
        }

        $classification = ValidatedClassificationResponse::fromJson(
            $response->body,
            $requestId,
            $this->validator()
        );

        if ($classification === null) {
            return $this->unavailable(
                $requestId,
                'invalid_response',
                $response->statusCode,
                $startedAt
            );
        }

        return ClassificationOutcome::fromValidatedResponse(
            $classification,
            $this->elapsedMilliseconds($startedAt)
        );
    }

    private static function hasSupportedJsonContentType(HttpTransportResponse $response): bool
    {
        $values = $response->headerValues('content-type');

        if (count($values) !== 1) {
            return false;
        }

        return preg_match(
            '/\Aapplication\/json(?:\s*;\s*charset\s*=\s*(?:utf-8|"utf-8"))?\z/i',
            trim($values[0])
        ) === 1;
    }

    private function validator(): ClassificationResponseValidator
    {
        return $this->responseValidator ?? new ClassificationResponseValidator();
    }

    private function unavailable(
        ?string $requestId,
        string $category,
        ?int $statusCode,
        int $startedAt
    ): ClassificationOutcome {
        return ClassificationOutcome::unavailable(
            $requestId,
            $category,
            $statusCode,
            $this->elapsedMilliseconds($startedAt)
        );
    }

    private function elapsedMilliseconds(int $startedAt): int
    {
        try {
            return max(0, intdiv(($this->clock)() - $startedAt, 1_000_000));
        } catch (Throwable) {
            return 0;
        }
    }

    private static function newUuidV4(): string
    {
        $bytes = random_bytes(16);
        $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
        $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);
        $hex = bin2hex($bytes);

        return substr($hex, 0, 8) . '-'
            . substr($hex, 8, 4) . '-'
            . substr($hex, 12, 4) . '-'
            . substr($hex, 16, 4) . '-'
            . substr($hex, 20, 12);
    }
}
