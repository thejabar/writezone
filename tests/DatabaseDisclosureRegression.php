<?php

declare(strict_types=1);

$file = dirname(__DIR__) . "/core/Database/Connection.php";
$source = file_get_contents($file);

if ($source === false) {
    fwrite(STDERR, "FAIL: cannot read Connection.php\n");
    exit(1);
}

$checks = [
    "PDO message is not exposed" => !str_contains($source, "getMessage()"),
    "die is not used for DB failure" => !str_contains($source, "die("),
    "generic exception is used" => str_contains($source, "RuntimeException"),
    "generic DB message is used" => str_contains($source, "Database connection failed."),
    "PDO exception boundary exists" => str_contains($source, "PDOException"),
];

$failed = 0;
foreach ($checks as $name => $passed) {
    echo ($passed ? "[PASS] " : "[FAIL] ") . $name . "\n";
    if (!$passed) {
        $failed++;
    }
}

if ($failed > 0) {
    echo "Database Disclosure Regression: FAIL\n";
    exit(1);
}

echo "Database Disclosure Regression: PASS (" . count($checks) . "/" . count($checks) . ")\n";
