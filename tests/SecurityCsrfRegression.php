<?php

declare(strict_types=1);

$root = dirname(__DIR__);

define('BASE_PATH', $root);

require $root . '/vendor/autoload.php';

use App\Middleware\CsrfMiddleware;
use Core\Http\Request;
use Core\Security\Csrf;
use Core\Session\Session;

function check(bool $condition, string $message): void
{
    if (! $condition) {
        throw new RuntimeException("FAIL: {$message}");
    }

    echo "PASS: {$message}\n";
}

if (session_status() !== PHP_SESSION_NONE) {
    session_destroy();
}

Session::start();

Session::forget('_csrf_token');

$token = Csrf::token();

check(
    is_string($token) && preg_match('/^[a-f0-9]{64}$/', $token) === 1,
    'token is 64 hexadecimal characters'
);

check(
    Csrf::token() === $token,
    'token remains stable within the session'
);

check(
    Csrf::validate($token),
    'correct token validates'
);

check(
    ! Csrf::validate(str_repeat('0', 64)),
    'incorrect token is rejected'
);

check(
    ! Csrf::validate(null),
    'missing token is rejected'
);

$middleware = new CsrfMiddleware();

$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = [];

http_response_code(200);

$result = $middleware->handle(
    new Request(),
    static fn (): string => 'CONTROLLER_REACHED'
);

check(
    http_response_code() === 403,
    'POST without token returns HTTP 403'
);

check(
    $result === 'CSRF validation failed.',
    'POST without token is blocked'
);

$_POST = ['_token' => str_repeat('0', 64)];

http_response_code(200);

$result = $middleware->handle(
    new Request(),
    static fn (): string => 'CONTROLLER_REACHED'
);

check(
    http_response_code() === 403,
    'POST with invalid token returns HTTP 403'
);

check(
    $result === 'CSRF validation failed.',
    'POST with invalid token is blocked'
);

$_POST = ['_token' => $token];

http_response_code(200);

$result = $middleware->handle(
    new Request(),
    static fn (): string => 'CONTROLLER_REACHED'
);

check(
    $result === 'CONTROLLER_REACHED',
    'POST with valid token reaches controller'
);

$_POST = [];
$_SERVER['REQUEST_METHOD'] = 'GET';

http_response_code(200);

$result = $middleware->handle(
    new Request(),
    static fn (): string => 'GET_REACHED'
);

check(
    $result === 'GET_REACHED',
    'GET request bypasses CSRF validation'
);

Session::destroy();

echo "=== CSRF Regression: PASS ===\n";
