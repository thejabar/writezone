<?php
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/vendor/autoload.php';

use App\Middleware\RateLimitMiddleware;
use Core\Http\Request;

$failures = 0;
$total = 0;

function check_h4(string $name, bool $pass): void
{
    global $failures, $total;

    echo ($pass ? '[PASS] ' : '[FAIL] ') . $name . PHP_EOL;

    if (! $pass) {
        $failures++;
    }

    $total++;
}

function middleware_profile(string $uri): string
{
    $_SERVER['REQUEST_URI'] = $uri;
    $_SERVER['REQUEST_METHOD'] = 'POST';

    $middleware = new RateLimitMiddleware();

    $reflection = new ReflectionClass($middleware);
    $method = $reflection->getMethod('profile');
    $method->setAccessible(true);

    return (string) $method->invoke($middleware, new Request());
}

check_h4(
    'Canonical login path resolves to login profile',
    middleware_profile('/login') === 'login'
);

check_h4(
    'Trailing-slash login path resolves to login profile',
    middleware_profile('/login/') === 'login'
);

check_h4(
    'Canonical register path resolves to register profile',
    middleware_profile('/register') === 'register'
);

check_h4(
    'Trailing-slash register path resolves to register profile',
    middleware_profile('/register/') === 'register'
);

check_h4(
    'Canonical lab path resolves to lab profile',
    middleware_profile('/lab') === 'lab'
);

check_h4(
    'Trailing-slash lab path resolves to lab profile',
    middleware_profile('/lab/') === 'lab'
);

check_h4(
    'Lab analyze resolves to lab profile',
    middleware_profile('/lab/analyze') === 'lab'
);

check_h4(
    'Trailing-slash lab analyze resolves to lab profile',
    middleware_profile('/lab/analyze/') === 'lab'
);

echo PHP_EOL;
echo "RateLimit H4: {$total} assertions, {$failures} failures" . PHP_EOL;

exit($failures === 0 ? 0 : 1);
