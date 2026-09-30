<?php
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/vendor/autoload.php';

use App\Middleware\RateLimitMiddleware;
use Core\Container\Container;
use Core\Http\Request;
use Core\Http\Response;
use Core\Routing\Router;

function check_router(bool $condition, string $message): void
{
    if (! $condition) {
        fwrite(STDERR, "[FAIL] {$message}" . PHP_EOL);
        exit(1);
    }

    echo "[PASS] {$message}" . PHP_EOL;
}

$ip = '198.51.100.21';

$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['REQUEST_URI'] = '/login';
$_SERVER['REMOTE_ADDR'] = $ip;

$container = new Container();
$router = new Router($container);

$router->alias('ratelimit', RateLimitMiddleware::class);

$router
    ->middleware('ratelimit')
    ->post('/login', static function (): string {
        return 'ROUTER_HANDLER_REACHED';
    });

$results = [];

for ($i = 1; $i <= 6; $i++) {
    http_response_code(200);

    ob_start();

    $router->dispatch(new Request());

    $body = (string) ob_get_clean();

    $results[$i] = [
        'status' => http_response_code(),
        'body' => $body,
    ];
}

check_router(
    $results[1]['status'] === 200
        && $results[1]['body'] === 'ROUTER_HANDLER_REACHED',
    'Router resolves ratelimit middleware and reaches handler'
);

check_router(
    $results[5]['status'] === 200
        && $results[5]['body'] === 'ROUTER_HANDLER_REACHED',
    'Router permits the fifth login request'
);

check_router(
    $results[6]['status'] === 429
        && $results[6]['body'] === 'Too many requests.',
    'Router blocks the sixth login request with HTTP 429'
);

$file = BASE_PATH
    . '/storage/cache/rate-limit/'
    . hash('sha256', 'login:ip:' . $ip)
    . '.json';

if (is_file($file)) {
    unlink($file);
}

check_router(
    ! is_file($file),
    'Router integration test leaves no rate-limit residue'
);

echo "RateLimitRouter: PASS (4/4)" . PHP_EOL;
