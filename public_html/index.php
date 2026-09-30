<?php

declare(strict_types=1);

use Core\Error\ErrorHandler;
use Core\Session\Session;

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

$debug = filter_var(
    $_ENV['APP_DEBUG'] ?? false,
    FILTER_VALIDATE_BOOLEAN
);

if ($debug) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
}

ErrorHandler::register();

Session::start();

$app = require BASE_PATH . '/bootstrap/app.php';

$app->run();
