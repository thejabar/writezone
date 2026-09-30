<?php
declare(strict_types=1);

return [
    'directory' => BASE_PATH . '/storage/cache/rate-limit',

    'login' => [
        'max_attempts' => 5,
        'decay_seconds' => 300,
        'identity' => 'ip',
    ],

    'register' => [
        'max_attempts' => 5,
        'decay_seconds' => 900,
        'identity' => 'ip',
    ],

    'auth_action' => [
        'max_attempts' => 60,
        'decay_seconds' => 60,
        'identity' => 'user_ip',
    ],

    'lab' => [
        'max_attempts' => 20,
        'decay_seconds' => 60,
        'identity' => 'user_ip',
    ],
];
