<?php

declare(strict_types=1);

return [
    'name' => $_ENV['SESSION_NAME'] ?? 'writezone_session',
    'lifetime' => (int) ($_ENV['SESSION_LIFETIME'] ?? 120),
    'path' => '/',
    'domain' => $_ENV['SESSION_DOMAIN'] ?? '',
    'secure' => filter_var(
        $_ENV['SESSION_SECURE'] ?? true,
        FILTER_VALIDATE_BOOLEAN
    ),
    'httponly' => true,
    'samesite' => $_ENV['SESSION_SAMESITE'] ?? 'Lax',
];
