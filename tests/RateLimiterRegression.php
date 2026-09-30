<?php
declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Core\Security\RateLimiter;

$directory = sys_get_temp_dir() . '/writezone-rate-limit-' . getmypid();

if (is_dir($directory)) {
    foreach (glob($directory . '/*') ?: [] as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    rmdir($directory);
}

$limiter = new RateLimiter($directory);

$results = [];

$results[] = [
    'name' => 'First request allowed',
    'pass' => $limiter->hit('test-key', 2, 60)['allowed'] === true,
];

$second = $limiter->hit('test-key', 2, 60);

$results[] = [
    'name' => 'Second request allowed',
    'pass' => $second['allowed'] === true,
];

$third = $limiter->hit('test-key', 2, 60);

$results[] = [
    'name' => 'Third request blocked',
    'pass' => $third['allowed'] === false,
];

$results[] = [
    'name' => 'Blocked request returns zero remaining',
    'pass' => $third['remaining'] === 0,
];

$results[] = [
    'name' => 'Retry-after is positive',
    'pass' => $third['retry_after'] > 0,
];

$other = $limiter->hit('other-key', 2, 60);

$results[] = [
    'name' => 'Different key has independent limit',
    'pass' => $other['allowed'] === true,
];

$files = glob($directory . '/*') ?: [];

foreach ($files as $file) {
    if (is_file($file)) {
        unlink($file);
    }
}

if (is_dir($directory)) {
    rmdir($directory);
}

$passed = 0;
$total = count($results);

foreach ($results as $result) {
    if ($result['pass']) {
        $passed++;
        echo '[PASS] ' . $result['name'] . PHP_EOL;
    } else {
        echo '[FAIL] ' . $result['name'] . PHP_EOL;
    }
}

echo PHP_EOL . "RateLimiter: {$passed}/{$total}" . PHP_EOL;

exit($passed === $total ? 0 : 1);
