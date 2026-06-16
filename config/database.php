<?php

declare(strict_types=1);

return [

    'driver' => 'mysql',

    'host' => $_ENV['DB_HOST'] ?? 'localhost',

    'port' => (int) ($_ENV['DB_PORT'] ?? 3306),

    'database' => $_ENV['DB_DATABASE'] ?? '',

    'username' => $_ENV['DB_USERNAME'] ?? '',

    'password' => $_ENV['DB_PASSWORD'] ?? '',

    'charset' => 'utf8mb4',

];
