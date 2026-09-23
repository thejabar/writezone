<?php

declare(strict_types=1);

define('BASE_PATH', __DIR__);

require BASE_PATH . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

$command = $argv[1] ?? 'status';

if (!in_array($command, ['status', 'baseline', 'migrate'], true)) {
    fwrite(
        STDERR,
        "Usage: php migrate.php [status|baseline|migrate]\n"
    );
    exit(2);
}

$pdo = Core\Database\Connection::getInstance();

$migrator = new Core\Database\Migrator($pdo);

try {
    match ($command) {
        'status' => $migrator->status(),
        'baseline' => $migrator->baseline(),
        'migrate' => $migrator->migrate(),
    };
} catch (Throwable $e) {
    fwrite(
        STDERR,
        "Migration error: " . $e->getMessage() . PHP_EOL
    );
    exit(1);
}
