<?php
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/vendor/autoload.php';

use App\Middleware\RateLimitMiddleware;
use Core\Http\Request;
use Core\Session\Session;

function assert_result(string $name, bool $pass): void
{
    echo ($pass ? '[PASS] ' : '[FAIL] ') . $name . PHP_EOL;

    if (! $pass) {
        $GLOBALS['failures']++;
    }

    $GLOBALS['total']++;
}

function run_request(
    string $uri,
    string $ip,
    callable $next
): array {
    $_SERVER['REQUEST_URI'] = $uri;
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_SERVER['REMOTE_ADDR'] = $ip;

    http_response_code(200);

    ob_start();

    $middleware = new RateLimitMiddleware();
    $result = $middleware->handle(new Request(), $next);

    $body = (string) ob_get_clean();

    return [
        'result' => $result,
        'body' => $body,
        'status' => http_response_code(),
    ];
}

function cleanup_key(string $profile, string $identity): void
{
    $file = BASE_PATH
        . '/storage/cache/rate-limit/'
        . hash('sha256', $profile . ':' . $identity)
        . '.json';

    if (is_file($file)) {
        unlink($file);
    }
}

$GLOBALS['failures'] = 0;
$GLOBALS['total'] = 0;

Session::start();

$loginIp = '198.51.100.11';

$first = run_request('/login', $loginIp, fn () => 'LOGIN_OK');

assert_result(
    'Login profile allows first request',
    $first['result'] === 'LOGIN_OK'
        && $first['status'] === 200
);

$blocked = null;

for ($i = 0; $i < 5; $i++) {
    $blocked = run_request('/login', $loginIp, fn () => 'LOGIN_OK');
}

assert_result(
    'Login profile blocks after five allowed attempts',
    $blocked['result'] === null
        && $blocked['status'] === 429
        && $blocked['body'] === 'Too many requests.'
);

$registerIp = '198.51.100.12';

$register = run_request('/register', $registerIp, fn () => 'REGISTER_OK');

assert_result(
    'Register uses independent profile',
    $register['result'] === 'REGISTER_OK'
        && $register['status'] === 200
);

$labIp = '198.51.100.13';

$lab = run_request('/lab', $labIp, fn () => 'LAB_OK');

assert_result(
    'Lab profile allows request',
    $lab['result'] === 'LAB_OK'
        && $lab['status'] === 200
);

$labAnalyze = run_request('/lab/analyze', $labIp, fn () => 'LAB_ANALYZE_OK');

assert_result(
    'Lab analyze shares lab profile',
    $labAnalyze['result'] === 'LAB_ANALYZE_OK'
        && $labAnalyze['status'] === 200
);

$actionIp = '198.51.100.14';

$action = run_request('/writs/123/update', $actionIp, fn () => 'ACTION_OK');

assert_result(
    'Authenticated action uses auth_action profile',
    $action['result'] === 'ACTION_OK'
        && $action['status'] === 200
);


cleanup_key('login', 'ip:' . $loginIp);
cleanup_key('register', 'ip:' . $registerIp);
cleanup_key('lab', 'user:0:ip:' . $labIp);
cleanup_key('auth_action', 'user:0:ip:' . $actionIp);

echo PHP_EOL;
echo "RateLimitMiddleware: {$GLOBALS['total']} assertions, "
    . "{$GLOBALS['failures']} failures"
    . PHP_EOL;

exit($GLOBALS['failures'] === 0 ? 0 : 1);
