<?php
declare(strict_types=1);

return [
    'debug' => filter_var(
        $_ENV['APP_DEBUG'] ?? false,
        FILTER_VALIDATE_BOOLEAN
    ),
    'log_file' => BASE_PATH . '/storage/logs/app.log',
];
