<?php

declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

use App\Intelligence\Remote\HttpTransport;
use App\Intelligence\Remote\HttpTransportResponse;
use App\Intelligence\Remote\ClassificationOutcome;
use App\Intelligence\Remote\CurlHttpTransport;
use App\Intelligence\Remote\PythonClassificationClientConfig;
use App\Intelligence\Remote\PythonClassificationClient;
use App\Intelligence\Remote\PythonClassificationClientFactory;
use App\Intelligence\Remote\ValidatedClassificationResponse;

final class FakeIntelligenceTransport implements HttpTransport
{
    /** @var list<array{path: string, headers: array, body: string, connect_timeout_ms: int, timeout_ms: int}> */
    public array $requests = [];

    /**
     * @param list<HttpTransportResponse|Throwable|callable(string): HttpTransportResponse> $responses
     */
    public function __construct(private array $responses)
    {
    }

    public function send(
        string $path,
        array $headers,
        string $body,
        int $connectTimeoutMs,
        int $timeoutMs
    ): HttpTransportResponse {
        $this->requests[] = [
            'path' => $path,
            'headers' => $headers,
            'body' => $body,
            'connect_timeout_ms' => $connectTimeoutMs,
            'timeout_ms' => $timeoutMs,
        ];

        $response = array_shift($this->responses);

        if ($response instanceof Throwable) {
            throw $response;
        }

        if (is_callable($response)) {
            return $response($body);
        }

        return $response ?? HttpTransportResponse::failure('transport_failure');
    }
}

final class PythonClassificationClientRegression
{
    private int $passed = 0;
    private int $failed = 0;

    public function run(): int
    {
        $this->testValidRequestConstructionAndUnicode();
        $this->testUuidAndRequestBodyArePreservedAcrossRetry();
        $this->testCurrentAndNextCredentialSelection();
        $this->testMissingAndInvalidCredentialsFailClosed();
        $this->testConfigurationDefaultsAndDisabledFeature();
        $this->testDestinationPolicyFactoryAndRelativePathBoundary();
        $this->testInputValidationAndLimits();
        $this->testValidResponseAndSuccessfulAbstention();
        $this->testResponseContentTypeAndUnexpectedHeaders();
        $this->testSuccessfulOutcomeRequiresValidatedResponse();
        $this->testCompleteCurrentNextKeyRotationTransition();
        $this->test503IsUnavailableAndNeverAbstention();
        $this->testMalformedAndMismatchedResponsesAreRejected();
        $this->testResponseVersionTaxonomyAndLanguageValidation();
        $this->testInvalidLabelsAndConfidenceAreRejected();
        $this->testRetryOnlyTransientStatusesAndTransportFailures();
        $this->testConnectionTimeoutUsesApprovedBudgets();
        $this->testTotalDeadlineIncludesRetryTime();
        $this->testPermanentHttpErrorsAreNotRetried();
        $this->testConfigurationBoundsFailClosed();
        $this->testExceptionsAndContentAreNotLoggedOrReturned();
        $this->testCurlHttpTransportBoundariesAndOptions();
        $this->testCurlHeaderLimitsAndMalformedHeaders();

        echo PHP_EOL;
        echo "RESULT: {$this->passed} passed, {$this->failed} failed" . PHP_EOL;

        return $this->failed === 0 ? 0 : 1;
    }

    private function testValidRequestConstructionAndUnicode(): void
    {
        $transport = new FakeIntelligenceTransport([
            fn (string $body): HttpTransportResponse => $this->successResponse($body),
        ]);
        $client = $this->client($transport);
        $text = 'A Unicode passage: café, Ελληνικά, and 📚. https://attacker.invalid/path';
        $outcome = $client->classify($text);
        $request = $transport->requests[0] ?? [];
        $payload = json_decode($request['body'] ?? '', true);

        $this->assert($outcome->isAvailable(), 'Valid response is available.');
        $this->assert(
            preg_match('/\A[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}\z/', $outcome->requestId() ?? '') === 1,
            'Generated request ID is UUIDv4.'
        );
        $this->assert(
            ($request['path'] ?? null) === '/internal/v1/classifications'
                && ! array_key_exists('url', $request),
            'Client sends only its fixed relative route; classification text cannot supply a destination.'
        );
        $this->assert(
            ($request['headers']['Content-Type'] ?? null) === 'application/json; charset=utf-8',
            'Client sends the JSON content type.'
        );
        $this->assert(
            ($request['headers']['Authorization'] ?? null) === 'Bearer ' . $this->token('current'),
            'Client constructs the bearer authentication header.'
        );
        $this->assert(
            ($request['headers']['X-WZ-Key-Id'] ?? null) === 'current-test',
            'Client sends the key identifier header.'
        );
        $this->assert(
            is_array($payload)
                && $payload === [
                    'schema_version' => 1,
                    'request_id' => $outcome->requestId(),
                    'taxonomy_version' => 'wz-topics-v1',
                    'language' => 'en',
                    'text' => $text,
                ],
            'Request contract preserves valid Unicode text and required fields.'
        );
    }

    private function testUuidAndRequestBodyArePreservedAcrossRetry(): void
    {
        $transport = new FakeIntelligenceTransport([
            $this->jsonResponse(502, '{}'),
            fn (string $body): HttpTransportResponse => $this->successResponse($body),
        ]);
        $outcome = $this->client($transport)->classify('Retry-safe sample text.');
        $first = json_decode($transport->requests[0]['body'], true);
        $second = json_decode($transport->requests[1]['body'], true);

        $this->assert($outcome->isAvailable(), 'Transient 502 is retried once.');
        $this->assert(count($transport->requests) === 2, 'At most one retry is made.');
        $this->assert(
            $first['request_id'] === $second['request_id']
                && $transport->requests[0]['body'] === $transport->requests[1]['body'],
            'Retry preserves the exact request ID and JSON body.'
        );
    }

    private function testCurrentAndNextCredentialSelection(): void
    {
        $transport = new FakeIntelligenceTransport([
            fn (string $body): HttpTransportResponse => $this->successResponse($body),
        ]);
        $configuration = $this->configuration(['active_key' => 'next']);
        $outcome = $this->client($transport, $configuration)->classify('Rotation test.');

        $this->assert($outcome->isAvailable(), 'Next key is accepted during overlap.');
        $this->assert(
            ($transport->requests[0]['headers']['Authorization'] ?? null)
                === 'Bearer ' . $this->token('next')
                && ($transport->requests[0]['headers']['X-WZ-Key-Id'] ?? null) === 'next-test',
            'Selected next key and its identifier are sent together.'
        );
    }

    private function testMissingAndInvalidCredentialsFailClosed(): void
    {
        $cases = [
            ['service_token' => null, 'key_id' => null],
            ['service_token' => 'short', 'key_id' => 'current-test'],
            [
                'service_token' => $this->nonCanonicalToken($this->token('current')),
                'key_id' => 'current-test',
            ],
            ['service_token' => $this->token('current'), 'key_id' => 'bad key id'],
            ['next_service_token' => $this->token('current'), 'next_key_id' => 'current-test'],
            ['next_service_token' => $this->token('current'), 'next_key_id' => 'next-test'],
            ['next_service_token' => $this->token('next'), 'next_key_id' => 'current-test'],
            [
                'active_key' => 'next',
                'next_service_token' => null,
                'next_key_id' => null,
            ],
            ['revoked_key_ids' => ['current-test']],
        ];

        foreach ($cases as $overrides) {
            $transport = new FakeIntelligenceTransport([]);
            $outcome = $this->client(
                $transport,
                $this->configuration($overrides)
            )->classify('Credential check.');

            $this->assert(
                $outcome->isUnavailable()
                    && $outcome->failureCategory() === 'configuration_unavailable'
                    && $transport->requests === [],
                'Missing, malformed, conflicting, or revoked credentials fail closed.'
            );
        }
    }

    private function testConfigurationDefaultsAndDisabledFeature(): void
    {
        $values = [
            'WRITEZONE_INTELLIGENCE_ENABLED' => 'false',
            'WRITEZONE_INTELLIGENCE_URL' => 'http://127.0.0.1:8765',
            'WRITEZONE_INTELLIGENCE_ACTIVE_KEY' => 'current',
            'WRITEZONE_INTELLIGENCE_SERVICE_TOKEN' => '',
            'WRITEZONE_INTELLIGENCE_KEY_ID' => '',
            'WRITEZONE_INTELLIGENCE_NEXT_SERVICE_TOKEN' => '',
            'WRITEZONE_INTELLIGENCE_NEXT_KEY_ID' => '',
            'WRITEZONE_INTELLIGENCE_REVOKED_KEY_IDS' => '',
            'WRITEZONE_INTELLIGENCE_MAX_TEXT_BYTES' => '32768',
            'WRITEZONE_INTELLIGENCE_MAX_TEXT_CODE_POINTS' => '8000',
            'WRITEZONE_INTELLIGENCE_CONNECT_TIMEOUT_MS' => '250',
            'WRITEZONE_INTELLIGENCE_TOTAL_TIMEOUT_MS' => '5000',
            'WRITEZONE_INTELLIGENCE_MAX_RETRIES' => '1',
        ];
        $previous = [];

        foreach ($values as $name => $value) {
            $previous[$name] = [array_key_exists($name, $_ENV), $_ENV[$name] ?? null];
            $_ENV[$name] = $value;
        }

        try {
            $configuration = require dirname(__DIR__, 3) . '/config/intelligence.php';
        } finally {
            foreach ($previous as $name => [$existed, $value]) {
                if ($existed) {
                    $_ENV[$name] = $value;
                } else {
                    unset($_ENV[$name]);
                }
            }
        }

        $transport = new FakeIntelligenceTransport([]);
        $outcome = $this->client($transport, $configuration)->classify('Feature is off.');
        $this->assert(
            $configuration['enabled'] === false
                && $configuration['service_url'] === 'http://127.0.0.1:8765'
                && $configuration['max_text_bytes'] === 32768
                && $configuration['max_text_code_points'] === 8000
                && $configuration['connect_timeout_ms'] === 250
                && $configuration['total_timeout_ms'] === 5000
                && $configuration['max_retries'] === 1
                && $outcome->failureCategory() === 'feature_disabled'
                && $transport->requests === [],
            'Configuration provides nonsecret defaults and remains disabled by default.'
        );
    }

    private function testDestinationPolicyFactoryAndRelativePathBoundary(): void
    {
        $loopbackConfiguration = PythonClassificationClientConfig::fromArray(
            $this->configuration(['service_url' => 'http://127.0.0.1:8765'])
        );
        $httpsConfiguration = PythonClassificationClientConfig::fromArray(
            $this->configuration(['service_url' => 'https://intelligence.internal:9443'])
        );
        $normalizedHttpsConfiguration = PythonClassificationClientConfig::fromArray(
            $this->configuration(['service_url' => 'HTTPS://INTELLIGENCE.INTERNAL:9443/'])
        );

        $this->assert(
            $loopbackConfiguration !== null
                && $loopbackConfiguration->destination()->urlForPath('/internal/v1/classifications')
                    === 'http://127.0.0.1:8765/internal/v1/classifications',
            'Validated loopback HTTP configuration creates a normalized destination.'
        );
        $this->assert(
            $httpsConfiguration !== null
                && $httpsConfiguration->destination()->urlForPath('/internal/v1/classifications')
                    === 'https://intelligence.internal:9443/internal/v1/classifications',
            'An explicitly configured HTTPS destination remains supported.'
        );
        $this->assert(
            $normalizedHttpsConfiguration?->url === 'https://intelligence.internal:9443',
            'The configured destination normalizes scheme, hostname, and a trailing root slash.'
        );

        $rejectedDestinations = [
            ['service_url' => 'ftp://intelligence.internal'],
            ['service_url' => 'http://example.com'],
            ['service_url' => 'not-a-service-url'],
            ['service_url' => 'https://[invalid'],
            ['service_url' => 'https://invalid]'],
            ['service_url' => 'https://user:password@intelligence.internal'],
            ['service_url' => 'https://intelligence.internal/api'],
            ['service_url' => 'https://intelligence.internal?target=other'],
            ['service_url' => 'https://intelligence.internal#fragment'],
        ];

        foreach ($rejectedDestinations as $override) {
            $settings = PythonClassificationClientConfig::fromArray(
                $this->configuration($override)
            );
            $this->assert(
                $settings === null,
                'Unsupported, malformed, non-loopback HTTP, or overridden URL destinations fail closed.'
            );
        }

        $injectedTransport = new FakeIntelligenceTransport([
            fn (string $body): HttpTransportResponse => $this->successResponse($body),
        ]);
        $factoryClient = PythonClassificationClientFactory::create(
            $this->configuration(),
            $injectedTransport
        );
        $factoryOutcome = $factoryClient?->classify(
            'Factory dependency injection test.'
        );

        $this->assert(
            $factoryOutcome?->isAvailable() === true
                && ($injectedTransport->requests[0]['path'] ?? null)
                    === '/internal/v1/classifications',
            'The factory accepts an injected fake transport and keeps the fixed endpoint path.'
        );

        $disabledTransport = new FakeIntelligenceTransport([]);
        $disabledClient = PythonClassificationClientFactory::create(
            $this->configuration([
                'enabled' => false,
                'service_token' => null,
                'key_id' => null,
                'next_service_token' => null,
                'next_key_id' => null,
            ]),
            $disabledTransport
        );
        $disabledOutcome = $disabledClient?->classify('Feature remains disabled.');

        $this->assert(
            $disabledOutcome?->failureCategory() === 'feature_disabled'
                && $disabledTransport->requests === [],
            'Factory construction preserves disabled-by-default behavior without a connection.'
        );

        $invalidFactory = PythonClassificationClientFactory::create(
            $this->configuration(['service_url' => 'http://example.com']),
            new FakeIntelligenceTransport([])
        );
        $this->assert(
            $invalidFactory === null,
            'The factory does not construct a client for an unapproved destination.'
        );

        $transport = new CurlHttpTransport($httpsConfiguration->destination());
        foreach ([
            'https://attacker.invalid/path',
            '//attacker.invalid/path',
            'file:///etc/passwd',
            'ftp://attacker.invalid/path',
            '/../escape',
            '/internal/../admin',
            '/internal?target=https://attacker.invalid',
            '/internal#fragment',
            '/%2e%2e/escape',
            '/internal\\admin',
        ] as $path) {
            $response = $transport->send($path, [], 'synthetic', 250, 1000);
            $this->assert(
                $response->failureCategory === 'invalid_transport_request'
                    && $response->statusCode === 0,
                'Absolute, protocol-relative, traversal, query, fragment, and unsupported protocol targets are rejected before cURL.'
            );
        }
    }

    private function testInputValidationAndLimits(): void
    {
        $cases = [
            ['', 'invalid_input'],
            [" \t\n", 'invalid_input'],
            ["\xC3\x28", 'invalid_input'],
            [str_repeat('😀', 8193), 'input_too_large'],
            [str_repeat('😀', 8001), 'input_too_large'],
        ];

        foreach ($cases as [$text, $category]) {
            $transport = new FakeIntelligenceTransport([]);
            $outcome = $this->client($transport)->classify($text);

            $this->assert(
                $outcome->failureCategory() === $category && $transport->requests === [],
                'Invalid UTF-8, empty, whitespace, and over-limit text is not sent.'
            );
        }

        $byteLimitedTransport = new FakeIntelligenceTransport([]);
        $byteLimited = $this->client(
            $byteLimitedTransport,
            $this->configuration(['max_text_bytes' => 5])
        )->classify('ééé');
        $this->assert(
            $byteLimited->failureCategory() === 'input_too_large'
                && $byteLimitedTransport->requests === [],
            'UTF-8 byte count is enforced independently of code point count.'
        );

        $codePointLimitedTransport = new FakeIntelligenceTransport([]);
        $codePointLimited = $this->client(
            $codePointLimitedTransport,
            $this->configuration(['max_text_code_points' => 3])
        )->classify('four');
        $this->assert(
            $codePointLimited->failureCategory() === 'input_too_large'
                && $codePointLimitedTransport->requests === [],
            'Unicode code point count is enforced independently of UTF-8 byte count.'
        );

        $transport = new FakeIntelligenceTransport([]);
        $bytes = str_repeat('😀', 8193);
        $outcome = $this->client($transport)->classify($bytes);
        $this->assert(
            strlen($bytes) > 32768 && $outcome->isUnavailable(),
            'The UTF-8 byte ceiling rejects a payload above 32 KiB.'
        );
    }

    private function testValidResponseAndSuccessfulAbstention(): void
    {
        $transport = new FakeIntelligenceTransport([
            fn (string $body): HttpTransportResponse => $this->successResponse($body),
            fn (string $body): HttpTransportResponse => $this->successResponse(
                $body,
                ['abstained' => true, 'labels' => []]
            ),
        ]);
        $first = $this->client($transport)->classify('Topic request.');
        $second = $this->client($transport)->classify('A future abstention.');

        $this->assert(
            $first->isAvailable()
                && $first->isAbstained() === false
                && count($first->classification()['labels']) === 1,
            'Valid classification labels are returned only after schema validation.'
        );
        $this->assert(
            $second->isAvailable() && $second->isAbstained() === true,
            'A valid empty-label response is represented as successful abstention.'
        );
    }

    private function testResponseContentTypeAndUnexpectedHeaders(): void
    {
        foreach ([
            ['application/json'],
            ['Application/JSON; Charset="UTF-8"'],
            ['application/json ; charset = utf-8'],
        ] as $contentTypes) {
            $transport = new FakeIntelligenceTransport([
                fn (string $body): HttpTransportResponse => $this->successResponse(
                    $body,
                    [],
                    ['Content-Type' => $contentTypes]
                ),
            ]);
            $outcome = $this->client($transport)->classify('JSON media type.');

            $this->assert(
                $outcome->isAvailable(),
                'application/json with an optional supported UTF-8 charset is accepted.'
            );
        }

        $invalidHeaders = [
            [],
            ['Content-Type' => ['text/plain']],
            ['Content-Type' => ['application/problem+json']],
            ['Content-Type' => ['application/json; charset=iso-8859-1']],
            ['Content-Type' => ['application/json', 'application/json']],
        ];

        foreach ($invalidHeaders as $headers) {
            $transport = new FakeIntelligenceTransport([
                fn (string $body): HttpTransportResponse => $this->successResponse(
                    $body,
                    [],
                    $headers
                ),
            ]);
            $outcome = $this->client($transport)->classify('Reject invalid media type.');

            $this->assert(
                $outcome->isUnavailable()
                    && $outcome->failureCategory() === 'invalid_response'
                    && $outcome->classification() === null,
                'Missing, duplicate, unsupported, or incorrectly encoded media types fail unavailable.'
            );
        }

        $privateHeader = 'SYNTHETIC_HEADER_VALUE_NOT_FOR_OUTCOME';
        $transport = new FakeIntelligenceTransport([
            fn (string $body): HttpTransportResponse => $this->successResponse(
                $body,
                [],
                [
                    'Content-Type' => ['application/json'],
                    'X-Synthetic-Private' => [$privateHeader],
                ]
            ),
        ]);
        $outcome = $this->client($transport)->classify('Ignore unrelated response headers.');
        $visibleOutcome = var_export([
            $outcome->classification(),
            $outcome->failureCategory(),
            $outcome->requestId(),
        ], true);

        $this->assert(
            $outcome->isAvailable() && ! str_contains($visibleOutcome, $privateHeader),
            'Unrelated response headers are not forwarded through the outcome.'
        );
    }

    private function testSuccessfulOutcomeRequiresValidatedResponse(): void
    {
        $requestId = '550e8400-e29b-41d4-a716-446655440000';
        $malformed = json_encode([
            'schema_version' => 1,
            'request_id' => $requestId,
            'service_version' => '0.7.0',
            'taxonomy_version' => 'wz-topics-v1',
            'language' => 'en',
            'abstained' => false,
            'labels' => [['id' => 'not-a-topic', 'confidence' => 4]],
        ], JSON_THROW_ON_ERROR);
        $validated = ValidatedClassificationResponse::fromJson($malformed, $requestId);

        $wrongTypeRejected = false;

        try {
            ClassificationOutcome::fromValidatedResponse([], 0);
        } catch (TypeError) {
            $wrongTypeRejected = true;
        }

        $validBody = json_encode([
            'schema_version' => 1,
            'request_id' => $requestId,
            'service_version' => '0.7.0',
            'taxonomy_version' => 'wz-topics-v1',
            'language' => 'en',
            'abstained' => false,
            'labels' => [['id' => 'science', 'confidence' => 0.9]],
        ], JSON_THROW_ON_ERROR);
        $validResponse = ValidatedClassificationResponse::fromJson($validBody, $requestId);
        $validatedOutcome = $validResponse === null
            ? null
            : ClassificationOutcome::fromValidatedResponse($validResponse, -1);

        $this->assert(
            ! method_exists(ClassificationOutcome::class, 'success')
                && $validated === null
                && $wrongTypeRejected
                && $validatedOutcome?->isAvailable() === true
                && $validatedOutcome->requestId() === $requestId
                && $validatedOutcome->httpStatus() === 200
                && $validatedOutcome->latencyMs() === 0,
            'The public API cannot create success from arbitrary arrays or malformed protocol data.'
        );
    }

    private function testCompleteCurrentNextKeyRotationTransition(): void
    {
        $acceptedByPython = [
            'current-test' => $this->token('current'),
            'next-test' => $this->token('next'),
        ];
        $currentConfiguration = $this->configuration();
        $currentRequest = $this->rotationRequest($currentConfiguration);
        $this->assert(
            $this->requestMatchesAcceptedKey($currentRequest, $acceptedByPython),
            'Step 1: Python overlap accepts current key A and next key B.'
        );

        $nextConfiguration = $this->configuration(['active_key' => 'next']);
        $nextRequest = $this->rotationRequest($nextConfiguration);
        $this->assert(
            $this->requestMatchesAcceptedKey($nextRequest, $acceptedByPython)
                && ($nextRequest['headers']['X-WZ-Key-Id'] ?? null) === 'next-test',
            'Step 2: PHP selects key B while Python accepts both credentials.'
        );

        $acceptedByPython = ['next-test' => $this->token('next')];
        $stillSelectedNextRequest = $this->rotationRequest($nextConfiguration);
        $this->assert(
            $this->requestMatchesAcceptedKey($stillSelectedNextRequest, $acceptedByPython),
            'Steps 3-4: Python promotes B and PHP continues using B without an overlap gap.'
        );

        $promotedPhpConfiguration = $this->configuration([
            'active_key' => 'current',
            'service_token' => $this->token('next'),
            'key_id' => 'next-test',
            'next_service_token' => null,
            'next_key_id' => null,
        ]);
        $promotedRequest = $this->rotationRequest($promotedPhpConfiguration);
        $this->assert(
            $this->requestMatchesAcceptedKey($promotedRequest, $acceptedByPython),
            'Step 5: PHP removes obsolete key A and keeps B as its current credential.'
        );

        $revokedConfiguration = array_replace(
            $promotedPhpConfiguration,
            ['revoked_key_ids' => ['current-test']]
        );
        $afterRevocationRequest = $this->rotationRequest($revokedConfiguration);
        $this->assert(
            $this->requestMatchesAcceptedKey($afterRevocationRequest, $acceptedByPython)
                && ($afterRevocationRequest['headers']['X-WZ-Key-Id'] ?? null) === 'next-test',
            'Steps 6-7: Python revokes A and authentication continues with B.'
        );
    }

    /** @return array{url: string, headers: array, body: string, connect_timeout_ms: int, timeout_ms: int} */
    private function rotationRequest(array $configuration): array
    {
        $transport = new FakeIntelligenceTransport([
            fn (string $body): HttpTransportResponse => $this->successResponse($body),
        ]);
        $outcome = $this->client($transport, $configuration)->classify('Synthetic key rotation.');

        $this->assert($outcome->isAvailable(), 'Synthetic rotation request receives valid protocol data.');

        return $transport->requests[0] ?? [];
    }

    /** @param array<string, string> $accepted */
    private function requestMatchesAcceptedKey(array $request, array $accepted): bool
    {
        $keyId = $request['headers']['X-WZ-Key-Id'] ?? null;
        $authorization = $request['headers']['Authorization'] ?? null;

        return is_string($keyId)
            && isset($accepted[$keyId])
            && is_string($authorization)
            && hash_equals('Bearer ' . $accepted[$keyId], $authorization);
    }

    private function test503IsUnavailableAndNeverAbstention(): void
    {
        $unavailableBody = '{"error":{"code":"classification_unavailable"}}';
        $transport = new FakeIntelligenceTransport([
            $this->jsonResponse(503, $unavailableBody),
            $this->jsonResponse(503, $unavailableBody),
        ]);
        $outcome = $this->client($transport)->classify('No model is available yet.');

        $this->assert(
            $outcome->isUnavailable()
                && $outcome->failureCategory() === 'classification_unavailable'
                && $outcome->isAbstained() === null
                && $outcome->classification() === null,
            'HTTP 503 classification_unavailable stays unavailable after one retry.'
        );
        $this->assert(count($transport->requests) === 2, '503 receives only one retry.');
    }

    private function testMalformedAndMismatchedResponsesAreRejected(): void
    {
        $cases = [
            $this->jsonResponse(200, '{malformed'),
            $this->jsonResponse(200, '[]'),
            fn (string $body): HttpTransportResponse => $this->successResponse(
                $body,
                ['request_id' => '550e8400-e29b-41d4-a716-446655440000']
            ),
            fn (string $body): HttpTransportResponse => $this->successResponse(
                $body,
                ['schema_version' => 2]
            ),
            fn (string $body): HttpTransportResponse => $this->successResponse(
                $body,
                ['unexpected' => 'field']
            ),
        ];

        foreach ($cases as $response) {
            $transport = new FakeIntelligenceTransport([$response]);
            $outcome = $this->client($transport)->classify('Malformed response test.');
            $this->assert(
                $outcome->isUnavailable()
                    && $outcome->failureCategory() === 'invalid_response'
                    && $outcome->classification() === null,
                'Malformed, mismatched, or structurally unexpected response is rejected.'
            );
        }
    }

    private function testResponseVersionTaxonomyAndLanguageValidation(): void
    {
        $cases = [
            ['service_version' => ''],
            ['service_version' => '01.2.3'],
            ['service_version' => '1.2'],
            ['service_version' => str_repeat('1', 65)],
            ['taxonomy_version' => 'wz-topics-v2'],
            ['language' => 'fr'],
        ];

        foreach ($cases as $overrides) {
            $transport = new FakeIntelligenceTransport([
                fn (string $body): HttpTransportResponse => $this->successResponse($body, $overrides),
            ]);
            $outcome = $this->client($transport)->classify('Contract validation.');
            $this->assert(
                $outcome->failureCategory() === 'invalid_response',
                'Invalid service version, taxonomy, or language is rejected.'
            );
        }

        $transport = new FakeIntelligenceTransport([]);
        $outcome = $this->client($transport)->classify('English only.', 'fr');
        $this->assert(
            $outcome->failureCategory() === 'unsupported_language'
                && $transport->requests === [],
            'Unsupported request language is rejected before transmission.'
        );
    }

    private function testInvalidLabelsAndConfidenceAreRejected(): void
    {
        $cases = [
            ['labels' => [['id' => 'unknown', 'confidence' => 0.8]]],
            ['labels' => [
                ['id' => 'science', 'confidence' => 0.8],
                ['id' => 'science', 'confidence' => 0.7],
            ]],
            ['labels' => [['id' => 'science', 'confidence' => -0.1]]],
            ['labels' => [['id' => 'science', 'confidence' => 1.1]]],
            ['labels' => [['id' => 'science', 'confidence' => '0.8']]],
            ['labels' => [['id' => 'science', 'confidence' => true]]],
            ['labels' => [
                ['id' => 'science', 'confidence' => 0.4],
                ['id' => 'education', 'confidence' => 0.8],
            ]],
            ['labels' => [
                ['id' => 'science', 'confidence' => 0.9],
                ['id' => 'education', 'confidence' => 0.8],
                ['id' => 'history', 'confidence' => 0.7],
                ['id' => 'health', 'confidence' => 0.6],
            ]],
            ['abstained' => true],
            ['abstained' => false, 'labels' => []],
            ['labels' => [['id' => 'science', 'confidence' => 0.8, 'extra' => 1]]],
        ];

        foreach ($cases as $overrides) {
            $transport = new FakeIntelligenceTransport([
                fn (string $body): HttpTransportResponse => $this->successResponse($body, $overrides),
            ]);
            $outcome = $this->client($transport)->classify('Invalid labels test.');
            $this->assert(
                $outcome->failureCategory() === 'invalid_response',
                'Unknown, duplicate, unordered, excessive, or invalid-confidence labels are rejected.'
            );
        }
    }

    private function testRetryOnlyTransientStatusesAndTransportFailures(): void
    {
        foreach ([502, 503, 504] as $status) {
            $transport = new FakeIntelligenceTransport([
                $this->jsonResponse($status, '{}'),
                fn (string $body): HttpTransportResponse => $this->successResponse($body),
            ]);
            $outcome = $this->client($transport)->classify('Transient retry.');
            $this->assert(
                $outcome->isAvailable() && count($transport->requests) === 2,
                "Transient HTTP {$status} response retries once."
            );
        }

        $transport = new FakeIntelligenceTransport([
            HttpTransportResponse::failure('transport_timeout'),
            fn (string $body): HttpTransportResponse => $this->successResponse($body),
        ]);
        $outcome = $this->client($transport)->classify('Transport retry.');
        $this->assert(
            $outcome->isAvailable() && count($transport->requests) === 2,
            'Connection failures and timeouts retry once.'
        );

        $transport = new FakeIntelligenceTransport([
            new RuntimeException('SENSITIVE_FAKE_TRANSPORT_EXCEPTION'),
            new RuntimeException('SECOND_FAKE_TRANSPORT_EXCEPTION'),
            fn (string $body): HttpTransportResponse => $this->successResponse($body),
        ]);
        $outcome = $this->client($transport)->classify('Failure cap.');
        $this->assert(
            $outcome->isUnavailable() && count($transport->requests) === 2,
            'Thrown transport errors cannot cause more than one retry.'
        );

        $oversizedResponse = new FakeIntelligenceTransport([
            HttpTransportResponse::failure('response_too_large'),
            fn (string $body): HttpTransportResponse => $this->successResponse($body),
        ]);
        $oversizedOutcome = $this->client($oversizedResponse)->classify('Do not retry large response.');
        $this->assert(
            $oversizedOutcome->failureCategory() === 'response_too_large'
                && count($oversizedResponse->requests) === 1,
            'Oversized transport responses are not retried.'
        );
    }

    private function testConnectionTimeoutUsesApprovedBudgets(): void
    {
        $transport = new FakeIntelligenceTransport([
            fn (string $body): HttpTransportResponse => $this->successResponse($body),
        ]);
        $this->client($transport)->classify('Timeout budget.');
        $request = $transport->requests[0];

        $this->assert(
            $request['connect_timeout_ms'] === 250
                && $request['timeout_ms'] > 0
                && $request['timeout_ms'] <= 5000,
            'Transport receives a 250 ms connect timeout and five-second deadline.'
        );
    }

    private function testTotalDeadlineIncludesRetryTime(): void
    {
        $now = 0;
        $clock = static function () use (&$now): int {
            return $now;
        };
        $transport = new FakeIntelligenceTransport([
            function (string $body) use (&$now): HttpTransportResponse {
                $now += 4_800_000_000;

                return $this->jsonResponse(503, '{}');
            },
            function (string $body) use (&$now): HttpTransportResponse {
                $now += 100_000_000;

                return $this->successResponse($body);
            },
        ]);
        $outcome = $this->client($transport, null, $clock)->classify('Deadline accounting.');

        $this->assert(
            $outcome->isAvailable()
                && count($transport->requests) === 2
                && $transport->requests[1]['timeout_ms'] <= 200
                && $transport->requests[1]['connect_timeout_ms'] <= 200,
            'Retry timeout is reduced to the remaining total deadline.'
        );

        $now = 0;
        $lateTransport = new FakeIntelligenceTransport([
            function (string $body) use (&$now): HttpTransportResponse {
                $now = 5_001_000_000;

                return $this->successResponse($body);
            },
        ]);
        $lateOutcome = $this->client($lateTransport, null, $clock)->classify('Late response.');
        $this->assert(
            $lateOutcome->failureCategory() === 'total_deadline_exceeded'
                && count($lateTransport->requests) === 1,
            'A late response is rejected and does not start another attempt.'
        );
    }

    private function testPermanentHttpErrorsAreNotRetried(): void
    {
        foreach ([301, 302, 307, 308, 400, 401, 403, 413, 415, 422, 429, 500] as $status) {
            $transport = new FakeIntelligenceTransport([
                $this->jsonResponse($status, '{"private":"ignored"}'),
                fn (string $body): HttpTransportResponse => $this->successResponse($body),
            ]);
            $outcome = $this->client($transport)->classify('Permanent error.');
            $this->assert(
                $outcome->isUnavailable() && count($transport->requests) === 1,
                "Permanent HTTP {$status} response is not retried."
            );
        }
    }

    private function testConfigurationBoundsFailClosed(): void
    {
        $cases = [
            ['service_url' => 'http://example.com'],
            ['service_url' => 'http://user:pass@127.0.0.1:8765'],
            ['service_url' => 'http://127.0.0.1:8765?target=other'],
            ['connect_timeout_ms' => 251],
            ['total_timeout_ms' => 5001],
            ['max_retries' => 2],
            ['max_text_bytes' => 32769],
            ['max_text_code_points' => 8001],
            ['revoked_key_ids' => ['current-test', 'current-test']],
        ];

        foreach ($cases as $overrides) {
            $transport = new FakeIntelligenceTransport([]);
            $outcome = $this->client(
                $transport,
                $this->configuration($overrides)
            )->classify('Configuration bounds.');
            $this->assert(
                $outcome->failureCategory() === 'configuration_unavailable'
                    && $transport->requests === [],
                'Untrusted URL, duplicate revoked ID, or excess setting fails closed.'
            );
        }

        $remoteTlsTransport = new FakeIntelligenceTransport([
            fn (string $body): HttpTransportResponse => $this->successResponse($body),
        ]);
        $tlsOutcome = $this->client(
            $remoteTlsTransport,
            $this->configuration(['service_url' => 'https://intelligence.internal'])
        )->classify('Protected HTTPS target.');
        $this->assert(
            $tlsOutcome->isAvailable(),
            'A trusted HTTPS service URL is accepted for verified TLS transport.'
        );
    }

    private function testExceptionsAndContentAreNotLoggedOrReturned(): void
    {
        $marker = 'SENSITIVE_SYNTHETIC_USER_TEXT_AND_SECRET_4e12';
        $transport = new FakeIntelligenceTransport([
            new RuntimeException($marker),
            new RuntimeException('SECOND_' . $marker),
        ]);
        $logPath = tempnam(sys_get_temp_dir(), 'wz-intelligence-log-');
        $previousErrorLog = ini_get('error_log');

        if ($logPath === false) {
            $this->assert(false, 'Temporary log capture is available.');

            return;
        }

        ini_set('error_log', $logPath);
        $bufferLevel = ob_get_level();
        ob_start();

        try {
            $outcome = $this->client($transport)->classify('Safe test content.');
            $output = (string) ob_get_clean();
            $log = (string) file_get_contents($logPath);
        } finally {
            ini_set('error_log', is_string($previousErrorLog) ? $previousErrorLog : '');
            while (ob_get_level() > $bufferLevel) {
                ob_end_clean();
            }
        }

        $visibleOutcome = var_export($outcome->failureCategory(), true);

        $this->assert(
            $output === ''
                && $log === ''
                && $outcome->failureCategory() === 'transport_failure'
                && ! str_contains($visibleOutcome, $marker),
            'Transport exception messages and request content are not emitted or returned.'
        );

        $upstreamMarker = 'SENSITIVE_FAKE_UPSTREAM_RESPONSE_6f98';
        $upstream = new FakeIntelligenceTransport([
            $this->jsonResponse(500, $upstreamMarker),
        ]);
        $previousErrorLog = ini_get('error_log');
        ini_set('error_log', $logPath);

        try {
            $upstreamOutcome = $this->client($upstream)->classify('Upstream error.');
            $upstreamLog = (string) file_get_contents($logPath);
        } finally {
            ini_set('error_log', is_string($previousErrorLog) ? $previousErrorLog : '');
            unlink($logPath);
        }

        $this->assert(
            $upstreamOutcome->failureCategory() === 'upstream_http_error'
                && $upstreamOutcome->classification() === null
                && $upstreamLog === '',
            'Raw upstream response bodies are neither returned nor logged.'
        );
    }

    private function testCurlHttpTransportBoundariesAndOptions(): void
    {
        if (! function_exists('curl_init') || ! function_exists('proc_open')) {
            $this->assert(false, 'cURL and process support are available for isolated transport fixtures.');

            return;
        }

        $fixture = $this->startLoopbackHttpFixture();

        if ($fixture === null) {
            $this->assert(false, 'A temporary loopback HTTP fixture starts successfully.');

            return;
        }

        [$baseUrl, $fixturePath, $process, $pipes, $logPath] = $fixture;

        try {
            $destinationSettings = PythonClassificationClientConfig::fromArray(
                $this->configuration(['service_url' => $baseUrl])
            );

            if ($destinationSettings === null) {
                $this->assert(false, 'The loopback fixture is accepted by destination policy.');

                return;
            }

            $transport = new CurlHttpTransport($destinationSettings->destination());
            $response = $transport->send(
                '/headers',
                ['Accept' => 'application/json'],
                'synthetic transport body',
                250,
                1000
            );
            $securityOptionsMethod = (new ReflectionClass(CurlHttpTransport::class))
                ->getMethod('securityOptions');
            $securityOptionsMethod->setAccessible(true);
            $capturedOptions = $securityOptionsMethod->invoke(null);
            $protocolOption = defined('CURLOPT_PROTOCOLS_STR')
                ? constant('CURLOPT_PROTOCOLS_STR')
                : CURLOPT_PROTOCOLS;
            $expectedProtocols = defined('CURLOPT_PROTOCOLS_STR')
                ? 'http,https'
                : (CURLPROTO_HTTP | CURLPROTO_HTTPS);

            $this->assert(
                $response->statusCode === 200
                    && $response->body === '{}'
                    && $response->headerValues('content-type') === ['application/json; charset=UTF-8']
                    && $response->headerValues('x-synthetic-fixture') === ['fixture-only'],
                'Direct cURL transport preserves response status, body, and bounded header values.'
            );
            $this->assert(
                ($capturedOptions[CURLOPT_SSL_VERIFYPEER] ?? null) === true
                    && ($capturedOptions[CURLOPT_SSL_VERIFYHOST] ?? null) === 2,
                'Direct cURL configuration enables peer and host TLS verification.'
            );
            $this->assert(
                ($capturedOptions[CURLOPT_PROXY] ?? null) === ''
                    && ($capturedOptions[CURLOPT_FOLLOWLOCATION] ?? null) === false
                    && ($capturedOptions[CURLOPT_MAXREDIRS] ?? null) === 0,
                'Direct cURL configuration disables proxies and redirect following.'
            );
            $this->assert(
                ($capturedOptions[$protocolOption] ?? null) === $expectedProtocols,
                'Direct cURL configuration permits only HTTP and HTTPS protocols.'
            );

            $exactSize = $transport->send('/exact-size', [], 'synthetic', 250, 1000);
            $this->assert(
                $exactSize->failureCategory === null && strlen($exactSize->body) === 65536,
                'The direct cURL response accepts exactly 64 KiB.'
            );

            $oversized = $transport->send('/oversized', [], 'synthetic', 250, 1000);
            $this->assert(
                $oversized->failureCategory === 'response_too_large'
                    && $oversized->body === ''
                    && $oversized->statusCode === 0,
                'The direct cURL response rejects an actual body above 64 KiB safely.'
            );

            $redirect = $transport->send('/redirect', [], 'synthetic', 250, 1000);
            $this->assert(
                $redirect->statusCode === 302 && $redirect->body === 'redirect fixture',
                'The direct cURL transport returns a redirect response without following it.'
            );

            $unexpectedStatus = $transport->send('/status', [], 'synthetic', 250, 1000);
            $this->assert(
                $unexpectedStatus->statusCode === 418
                    && $unexpectedStatus->failureCategory === null,
                'The direct cURL transport preserves an unexpected HTTP status for client policy.'
            );

            $restrictedProtocol = $transport->send(
                'file://' . $fixturePath,
                [],
                'synthetic',
                250,
                1000
            );
            $this->assert(
                $restrictedProtocol->failureCategory === 'invalid_transport_request'
                    && $restrictedProtocol->body === '',
                'The bound transport rejects file URLs before they can reach cURL.'
            );

            $productionClient = PythonClassificationClientFactory::create(
                $this->configuration(['service_url' => $baseUrl])
            );
            $productionOutcome = $productionClient?->classify(
                'Synthetic content sent only to the local fixture.'
            );
            $this->assert(
                $productionOutcome?->isAvailable() === true,
                'The factory-bound production cURL transport reaches only its configured loopback fixture.'
            );

            $closedSocket = @stream_socket_server('tcp://127.0.0.1:0', $errno, $error);
            $closedAddress = is_resource($closedSocket)
                ? stream_socket_get_name($closedSocket, false)
                : false;

            if (is_resource($closedSocket)) {
                fclose($closedSocket);
            }

            if (is_string($closedAddress)) {
                $closedSettings = PythonClassificationClientConfig::fromArray(
                    $this->configuration(['service_url' => 'http://' . $closedAddress])
                );
                $closedTransport = $closedSettings === null
                    ? null
                    : new CurlHttpTransport($closedSettings->destination());
                $transportFailure = $closedTransport?->send(
                    '/unavailable',
                    [],
                    'synthetic',
                    100,
                    250
                );
                $this->assert(
                    $transportFailure?->failureCategory === 'transport_failure'
                        && $transportFailure->body === '',
                    'A real refused loopback connection maps to a safe transport category.'
                );
            } else {
                $this->assert(false, 'A closed loopback socket is available for transport-error coverage.');
            }
        } finally {
            if (is_resource($process)) {
                proc_terminate($process);
            }

            foreach ($pipes as $pipe) {
                if (is_resource($pipe)) {
                    fclose($pipe);
                }
            }

            if (is_resource($process)) {
                proc_close($process);
            }

            @unlink($fixturePath);
            @unlink($logPath);
        }
    }

    private function testCurlHeaderLimitsAndMalformedHeaders(): void
    {
        if (! function_exists('curl_init') || ! function_exists('proc_open')) {
            $this->assert(false, 'cURL and process support are available for raw header fixtures.');

            return;
        }

        $fixture = $this->startRawHeaderFixture();

        if ($fixture === null) {
            $this->assert(false, 'A temporary raw loopback header fixture starts successfully.');

            return;
        }

        [$baseUrl, $fixturePath, $process, $pipes, $logPath] = $fixture;

        try {
            $settings = PythonClassificationClientConfig::fromArray(
                $this->configuration(['service_url' => $baseUrl])
            );

            if ($settings === null) {
                $this->assert(false, 'Raw loopback fixture destination passes configured URL policy.');

                return;
            }

            $transport = new CurlHttpTransport($settings->destination());
            $oversized = $transport->send(
                '/oversized-headers',
                [],
                'synthetic',
                250,
                1000
            );
            $this->assert(
                $oversized->failureCategory === 'response_headers_too_large'
                    && $oversized->body === ''
                    && $oversized->statusCode === 0,
                'A raw response whose aggregate headers exceed 16 KiB is rejected safely.'
            );

            $malformed = $transport->send(
                '/malformed-headers',
                [],
                'synthetic',
                250,
                1000
            );
            $this->assert(
                in_array(
                    $malformed->failureCategory,
                    ['invalid_response_headers', 'transport_failure'],
                    true
                )
                    && $malformed->body === '',
                'A malformed raw response header fails without returning response data.'
            );
        } finally {
            if (is_resource($process)) {
                proc_terminate($process);
            }

            foreach ($pipes as $pipe) {
                if (is_resource($pipe)) {
                    fclose($pipe);
                }
            }

            if (is_resource($process)) {
                proc_close($process);
            }

            @unlink($fixturePath);
            @unlink($logPath);
        }
    }

    /**
     * @return array{string, string, resource, array<int, resource>, string}|null
     */
    private function startLoopbackHttpFixture(): ?array
    {
        $reservation = @stream_socket_server('tcp://127.0.0.1:0', $errno, $error);

        if (! is_resource($reservation)) {
            return null;
        }

        $address = stream_socket_get_name($reservation, false);
        fclose($reservation);

        if (! is_string($address)) {
            return null;
        }

        $fixturePath = tempnam(sys_get_temp_dir(), 'wz-intelligence-http-');
        $logPath = tempnam(sys_get_temp_dir(), 'wz-intelligence-http-log-');

        if ($fixturePath === false || $logPath === false) {
            if (is_string($fixturePath)) {
                @unlink($fixturePath);
            }
            if (is_string($logPath)) {
                @unlink($logPath);
            }

            return null;
        }

        $fixtureSource = <<<'PHP'
<?php
declare(strict_types=1);

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
header('Content-Type: application/json; charset=UTF-8');

if ($path === '/headers') {
    header('X-Synthetic-Fixture: fixture-only');
    echo '{}';
} elseif ($path === '/internal/v1/classifications') {
    $request = json_decode((string) file_get_contents('php://input'), true);
    echo json_encode([
        'schema_version' => 1,
        'request_id' => $request['request_id'],
        'service_version' => '0.7.0',
        'taxonomy_version' => 'wz-topics-v1',
        'language' => 'en',
        'abstained' => false,
        'labels' => [['id' => 'science', 'confidence' => 0.9]],
    ], JSON_THROW_ON_ERROR);
} elseif ($path === '/exact-size') {
    echo str_repeat('x', 65536);
} elseif ($path === '/oversized') {
    echo str_repeat('x', 65537);
} elseif ($path === '/redirect') {
    http_response_code(302);
    header('Location: /headers');
    echo 'redirect fixture';
} elseif ($path === '/status') {
    http_response_code(418);
    echo '{}';
} else {
    echo '{}';
}
PHP;

        if (file_put_contents($fixturePath, $fixtureSource) === false) {
            @unlink($fixturePath);
            @unlink($logPath);

            return null;
        }

        $pipes = [];
        $process = @proc_open(
            [PHP_BINARY, '-S', $address, $fixturePath],
            [
                0 => ['pipe', 'r'],
                1 => ['file', $logPath, 'a'],
                2 => ['file', $logPath, 'a'],
            ],
            $pipes
        );

        if (! is_resource($process)) {
            @unlink($fixturePath);
            @unlink($logPath);

            return null;
        }

        $ready = false;

        for ($attempt = 0; $attempt < 100; $attempt++) {
            $connection = @stream_socket_client('tcp://' . $address, $errno, $error, 0.1);

            if (is_resource($connection)) {
                fclose($connection);
                $ready = true;

                break;
            }

            $status = proc_get_status($process);

            if (! ($status['running'] ?? false)) {
                break;
            }

            usleep(10000);
        }

        if (! $ready) {
            proc_terminate($process);
            foreach ($pipes as $pipe) {
                if (is_resource($pipe)) {
                    fclose($pipe);
                }
            }
            proc_close($process);
            @unlink($fixturePath);
            @unlink($logPath);

            return null;
        }

        return ['http://' . $address, $fixturePath, $process, $pipes, $logPath];
    }

    /**
     * @return array{string, string, resource, array<int, resource>, string}|null
     */
    private function startRawHeaderFixture(): ?array
    {
        $reservation = @stream_socket_server('tcp://127.0.0.1:0', $errno, $error);

        if (! is_resource($reservation)) {
            return null;
        }

        $address = stream_socket_get_name($reservation, false);
        fclose($reservation);

        if (! is_string($address)) {
            return null;
        }

        $fixturePath = tempnam(sys_get_temp_dir(), 'wz-intelligence-raw-http-');
        $logPath = tempnam(sys_get_temp_dir(), 'wz-intelligence-raw-http-log-');

        if ($fixturePath === false || $logPath === false) {
            if (is_string($fixturePath)) {
                @unlink($fixturePath);
            }
            if (is_string($logPath)) {
                @unlink($logPath);
            }

            return null;
        }

        $fixtureSource = <<<'PHP'
<?php
declare(strict_types=1);

$listener = @stream_socket_server('tcp://' . ($argv[1] ?? ''));
if (! is_resource($listener)) {
    exit(2);
}

$handled = 0;
while ($handled < 2) {
    $connection = @stream_socket_accept($listener, 10);
    if (! is_resource($connection)) {
        break;
    }

    $requestLine = fgets($connection);
    if (! is_string($requestLine)) {
        fclose($connection);
        continue;
    }

    while (($line = fgets($connection)) !== false && $line !== "\r\n") {
    }

    $requestParts = explode(' ', $requestLine);
    $path = $requestParts[1] ?? '/';
    $handled++;

    if ($path === '/oversized-headers') {
        fwrite($connection, "HTTP/1.1 200 OK\r\n");
        for ($index = 0; $index < 180; $index++) {
            fwrite($connection, 'X-Fill-' . $index . ': ' . str_repeat('x', 100) . "\r\n");
        }
        fwrite($connection, "Content-Type: application/json\r\nContent-Length: 2\r\nConnection: close\r\n\r\n{}");
    } else {
        fwrite($connection, "HTTP/1.1 200 OK\r\nBad Header: invalid\r\nContent-Type: application/json\r\nContent-Length: 2\r\nConnection: close\r\n\r\n{}");
    }

    fclose($connection);
}

fclose($listener);
PHP;

        if (file_put_contents($fixturePath, $fixtureSource) === false) {
            @unlink($fixturePath);
            @unlink($logPath);

            return null;
        }

        $pipes = [];
        $process = @proc_open(
            [PHP_BINARY, $fixturePath, $address],
            [
                0 => ['pipe', 'r'],
                1 => ['file', $logPath, 'a'],
                2 => ['file', $logPath, 'a'],
            ],
            $pipes
        );

        if (! is_resource($process)) {
            @unlink($fixturePath);
            @unlink($logPath);

            return null;
        }

        $ready = false;

        for ($attempt = 0; $attempt < 100; $attempt++) {
            $connection = @stream_socket_client('tcp://' . $address, $errno, $error, 0.1);

            if (is_resource($connection)) {
                fclose($connection);
                $ready = true;

                break;
            }

            $status = proc_get_status($process);

            if (! ($status['running'] ?? false)) {
                break;
            }

            usleep(10000);
        }

        if (! $ready) {
            proc_terminate($process);
            foreach ($pipes as $pipe) {
                if (is_resource($pipe)) {
                    fclose($pipe);
                }
            }
            proc_close($process);
            @unlink($fixturePath);
            @unlink($logPath);

            return null;
        }

        return ['http://' . $address, $fixturePath, $process, $pipes, $logPath];
    }

    private function client(
        FakeIntelligenceTransport $transport,
        ?array $configuration = null,
        ?callable $clock = null
    ): PythonClassificationClient {
        return new PythonClassificationClient(
            $transport,
            $configuration ?? $this->configuration(),
            $clock
        );
    }

    private function configuration(array $overrides = []): array
    {
        return array_replace(
            [
                'enabled' => true,
                'service_url' => 'http://127.0.0.1:8765',
                'active_key' => 'current',
                'service_token' => $this->token('current'),
                'key_id' => 'current-test',
                'next_service_token' => $this->token('next'),
                'next_key_id' => 'next-test',
                'revoked_key_ids' => [],
                'connect_timeout_ms' => 250,
                'total_timeout_ms' => 5000,
                'max_retries' => 1,
                'max_text_bytes' => 32768,
                'max_text_code_points' => 8000,
            ],
            $overrides
        );
    }

    private function token(string $label): string
    {
        return rtrim(
            strtr(base64_encode(hash('sha256', 'synthetic-' . $label, true)), '+/', '-_'),
            '='
        );
    }

    private function nonCanonicalToken(string $token): string
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';
        $index = strpos($alphabet, substr($token, -1));

        if (! is_int($index)) {
            return 'short';
        }

        return substr($token, 0, -1) . $alphabet[$index | 1];
    }

    private function successResponse(
        string $requestBody,
        array $overrides = [],
        array $headers = ['Content-Type' => ['application/json']]
    ): HttpTransportResponse {
        $request = json_decode($requestBody, true, 16, JSON_THROW_ON_ERROR);
        $response = array_replace(
            [
                'schema_version' => 1,
                'request_id' => $request['request_id'],
                'service_version' => '0.7.0',
                'taxonomy_version' => 'wz-topics-v1',
                'language' => 'en',
                'abstained' => false,
                'labels' => [
                    ['id' => 'science', 'confidence' => 0.91],
                ],
            ],
            $overrides
        );

        return new HttpTransportResponse(
            200,
            json_encode($response, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
            null,
            $headers
        );
    }

    /** @param array<string, list<string>|string> $headers */
    private function jsonResponse(int $statusCode, string $body, array $headers = []): HttpTransportResponse
    {
        return new HttpTransportResponse(
            $statusCode,
            $body,
            null,
            $headers === [] ? ['Content-Type' => ['application/json']] : $headers
        );
    }

    private function assert(bool $condition, string $message): void
    {
        if ($condition) {
            $this->passed++;
            echo "[PASS] {$message}" . PHP_EOL;

            return;
        }

        $this->failed++;
        echo "[FAIL] {$message}" . PHP_EOL;
    }
}

exit((new PythonClassificationClientRegression())->run());
