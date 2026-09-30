<?php
declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

define('BASE_PATH', dirname(__DIR__));

$_ENV['APP_DEBUG'] = 'false';

$logFile = BASE_PATH . '/storage/logs/app.log';

if (is_file($logFile)) {
    unlink($logFile);
}

use Core\Error\ErrorHandler;

$passed = 0;
$failed = 0;

function check(bool $condition, string $label): void
{
    global $passed, $failed;

    if ($condition) {
        $passed++;
        echo "PASS: {$label}\n";
    } else {
        $failed++;
        echo "FAIL: {$label}\n";
    }
}

ErrorHandler::register();

ob_start();

ErrorHandler::handleThrowable(
    new RuntimeException('R1.7_TEST_INTERNAL_SECRET')
);

$output = ob_get_clean();

check(
    $output === 'Internal Server Error.',
    'Production response is generic'
);

check(
    ! str_contains($output, 'R1.7_TEST_INTERNAL_SECRET'),
    'Production response does not expose exception message'
);

check(
    is_file($logFile),
    'Exception is logged'
);

$log = is_file($logFile)
    ? (string) file_get_contents($logFile)
    : '';

check(
    str_contains($log, 'R1.7_TEST_INTERNAL_SECRET'),
    'Log contains diagnostic exception message'
);

check(
    str_contains($log, 'RuntimeException'),
    'Log contains exception type'
);

echo "Error Handler Regression: {$passed}/" . ($passed + $failed) . " passed\n";

exit($failed === 0 ? 0 : 1);
