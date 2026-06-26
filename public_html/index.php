<?php

declare(strict_types=1);

use Core\Session\Session;

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/vendor/autoload.php';

Session::start();

$app = require BASE_PATH . '/bootstrap/app.php';

$app->run();