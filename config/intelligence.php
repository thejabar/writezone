<?php

declare(strict_types=1);

$readString = static function (string $name, ?string $default = null): ?string {
    $value = $_ENV[$name] ?? $_SERVER[$name] ?? getenv($name);

    return is_string($value) ? $value : $default;
};

$readInteger = static function (string $name, int $default) use ($readString): int {
    $raw = $readString($name, (string) $default);
    $parsed = filter_var($raw, FILTER_VALIDATE_INT);

    return $parsed === false ? 0 : $parsed;
};

$enabledValue = $readString('WRITEZONE_INTELLIGENCE_ENABLED', 'false');
$enabled = filter_var(
    $enabledValue,
    FILTER_VALIDATE_BOOLEAN,
    FILTER_NULL_ON_FAILURE
);

$revokedValue = $readString('WRITEZONE_INTELLIGENCE_REVOKED_KEY_IDS', '');
$revokedKeyIds = $revokedValue === ''
    ? []
    : array_map('trim', explode(',', $revokedValue));

return [
    'enabled' => $enabled === true,
    'service_url' => $readString(
        'WRITEZONE_INTELLIGENCE_URL',
        'http://127.0.0.1:8765'
    ),
    'active_key' => $readString('WRITEZONE_INTELLIGENCE_ACTIVE_KEY', 'current'),
    'service_token' => $readString('WRITEZONE_INTELLIGENCE_SERVICE_TOKEN'),
    'key_id' => $readString('WRITEZONE_INTELLIGENCE_KEY_ID'),
    'next_service_token' => $readString(
        'WRITEZONE_INTELLIGENCE_NEXT_SERVICE_TOKEN'
    ),
    'next_key_id' => $readString('WRITEZONE_INTELLIGENCE_NEXT_KEY_ID'),
    'revoked_key_ids' => $revokedKeyIds,
    'max_text_bytes' => $readInteger('WRITEZONE_INTELLIGENCE_MAX_TEXT_BYTES', 32768),
    'max_text_code_points' => $readInteger(
        'WRITEZONE_INTELLIGENCE_MAX_TEXT_CODE_POINTS',
        8000
    ),
    'connect_timeout_ms' => $readInteger(
        'WRITEZONE_INTELLIGENCE_CONNECT_TIMEOUT_MS',
        250
    ),
    'total_timeout_ms' => $readInteger(
        'WRITEZONE_INTELLIGENCE_TOTAL_TIMEOUT_MS',
        5000
    ),
    'max_retries' => $readInteger('WRITEZONE_INTELLIGENCE_MAX_RETRIES', 1),
];
