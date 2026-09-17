<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);

require $root . '/vendor/autoload.php';

if (!class_exists(\App\Intelligence\Lab\IntelligenceLab::class)) {
    throw new RuntimeException(
        'WriteZone application autoloading is unavailable.'
    );
}
