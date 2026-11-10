<?php

declare(strict_types=1);

namespace App\Intelligence\Remote;

final readonly class PythonClassificationClientConfig
{
    private function __construct(
        public bool $enabled,
        public string $url,
        public ?string $token,
        public ?string $keyId,
        public int $connectTimeoutMs,
        public int $totalTimeoutMs,
        public int $maxRetries,
        public int $maxTextBytes,
        public int $maxTextCodePoints
    ) {
    }

    /** @param array<string, mixed> $configuration */
    public static function fromArray(array $configuration): ?self
    {
        $serviceUrl = $configuration['service_url'] ?? null;

        if (! is_string($serviceUrl) || ! self::isTrustedServiceUrl($serviceUrl)) {
            return null;
        }

        $normalizedUrl = self::normalizeServiceUrl($serviceUrl);
        $enabled = ($configuration['enabled'] ?? false) === true;

        if (! $enabled) {
            return new self(
                false,
                $normalizedUrl,
                null,
                null,
                250,
                5000,
                1,
                32768,
                8000
            );
        }

        foreach ([
            'service_url',
            'active_key',
            'service_token',
            'key_id',
            'next_service_token',
            'next_key_id',
        ] as $stringSetting) {
            if (
                array_key_exists($stringSetting, $configuration)
                && $configuration[$stringSetting] !== null
                && ! is_string($configuration[$stringSetting])
            ) {
                return null;
            }
        }

        $activeKey = $configuration['active_key'] ?? 'current';
        $currentToken = self::emptyToNull($configuration['service_token'] ?? null);
        $currentKeyId = self::emptyToNull($configuration['key_id'] ?? null);
        $nextToken = self::emptyToNull($configuration['next_service_token'] ?? null);
        $nextKeyId = self::emptyToNull($configuration['next_key_id'] ?? null);
        $revokedKeyIds = $configuration['revoked_key_ids'] ?? [];

        if (! is_string($activeKey) || ! in_array($activeKey, ['current', 'next'], true)) {
            return null;
        }

        if (
            ! self::isValidCredentialPair($currentToken, $currentKeyId)
            || $currentToken === null
            || $currentKeyId === null
        ) {
            return null;
        }

        if (! self::isValidCredentialPair($nextToken, $nextKeyId)) {
            return null;
        }

        if (
            $nextToken !== null
            && ($nextToken === $currentToken || $nextKeyId === $currentKeyId)
        ) {
            return null;
        }

        if (! is_array($revokedKeyIds)) {
            return null;
        }

        foreach ($revokedKeyIds as $revokedKeyId) {
            if (! is_string($revokedKeyId) || ! self::isValidKeyId($revokedKeyId)) {
                return null;
            }
        }

        if (count($revokedKeyIds) !== count(array_unique($revokedKeyIds))) {
            return null;
        }

        if (
            in_array($currentKeyId, $revokedKeyIds, true)
            || ($nextKeyId !== null && in_array($nextKeyId, $revokedKeyIds, true))
        ) {
            return null;
        }

        if ($activeKey === 'next' && ($nextToken === null || $nextKeyId === null)) {
            return null;
        }

        $connectTimeout = $configuration['connect_timeout_ms'] ?? 250;
        $totalTimeout = $configuration['total_timeout_ms'] ?? 5000;
        $maxRetries = $configuration['max_retries'] ?? 1;
        $maxTextBytes = $configuration['max_text_bytes'] ?? 32768;
        $maxTextCodePoints = $configuration['max_text_code_points'] ?? 8000;

        if (! self::isIntInRange($connectTimeout, 1, 250)) {
            return null;
        }

        if (! self::isIntInRange($totalTimeout, 1, 5000)) {
            return null;
        }

        if (! self::isIntInRange($maxRetries, 0, 1)) {
            return null;
        }

        if (! self::isIntInRange($maxTextBytes, 1, 32768)) {
            return null;
        }

        if (! self::isIntInRange($maxTextCodePoints, 1, 8000)) {
            return null;
        }

        return new self(
            true,
            $normalizedUrl,
            $activeKey === 'current' ? $currentToken : $nextToken,
            $activeKey === 'current' ? $currentKeyId : $nextKeyId,
            $connectTimeout,
            $totalTimeout,
            $maxRetries,
            $maxTextBytes,
            $maxTextCodePoints
        );
    }

    public function destination(): ConfiguredServiceDestination
    {
        return ConfiguredServiceDestination::fromValidatedConfiguration($this);
    }

    private static function isTrustedServiceUrl(string $url): bool
    {
        $parts = parse_url($url);

        if (
            ! is_array($parts)
            || ! isset($parts['scheme'], $parts['host'])
            || isset($parts['user'])
            || isset($parts['pass'])
            || isset($parts['query'])
            || isset($parts['fragment'])
            || (isset($parts['path']) && ! in_array($parts['path'], ['', '/'], true))
        ) {
            return false;
        }

        $scheme = strtolower($parts['scheme']);
        $rawHost = strtolower($parts['host']);

        if (str_contains($rawHost, '[') || str_contains($rawHost, ']')) {
            if (
                preg_match('/\A\[([^\[\]]+)\]\z/', $rawHost, $matches) !== 1
                || filter_var($matches[1], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false
            ) {
                return false;
            }

            $host = $matches[1];
        } else {
            $host = $rawHost;
        }

        if (! in_array($scheme, ['http', 'https'], true) || $host === '') {
            return false;
        }

        if (
            $host !== 'localhost'
            && filter_var($host, FILTER_VALIDATE_IP) === false
            && filter_var($host, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) === false
        ) {
            return false;
        }

        if (isset($parts['port']) && ($parts['port'] < 1 || $parts['port'] > 65535)) {
            return false;
        }

        return $scheme === 'https' || self::isLoopbackHost($host);
    }

    private static function normalizeServiceUrl(string $url): string
    {
        $parts = parse_url($url);
        $scheme = strtolower($parts['scheme']);
        $host = strtolower($parts['host']);
        $port = isset($parts['port']) ? ':' . $parts['port'] : '';

        return $scheme . '://' . $host . $port;
    }

    private static function isLoopbackHost(string $host): bool
    {
        if (in_array($host, ['localhost', '::1'], true)) {
            return true;
        }

        $packed = @inet_pton($host);

        if ($packed === false) {
            return false;
        }

        if (strlen($packed) === 4) {
            return ord($packed[0]) === 127;
        }

        return $packed === str_repeat("\0", 15) . "\1";
    }

    private static function isValidCredentialPair(?string $token, ?string $keyId): bool
    {
        if (($token === null) !== ($keyId === null)) {
            return false;
        }

        return $token === null
            || (self::isValidServiceToken($token) && self::isValidKeyId($keyId));
    }

    private static function isValidServiceToken(string $token): bool
    {
        if (preg_match('/\A[A-Za-z0-9_-]{43}\z/', $token) !== 1) {
            return false;
        }

        $decoded = base64_decode(strtr($token, '-_', '+/') . '=', true);

        return is_string($decoded)
            && strlen($decoded) === 32
            && rtrim(strtr(base64_encode($decoded), '+/', '-_'), '=') === $token;
    }

    private static function isValidKeyId(string $keyId): bool
    {
        return preg_match('/\A[A-Za-z0-9][A-Za-z0-9._-]{0,63}\z/', $keyId) === 1;
    }

    private static function isIntInRange(mixed $value, int $minimum, int $maximum): bool
    {
        return is_int($value) && $value >= $minimum && $value <= $maximum;
    }

    private static function emptyToNull(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return is_string($value) ? $value : null;
    }
}
