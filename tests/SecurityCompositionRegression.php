<?php
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/vendor/autoload.php';

$routesFile = BASE_PATH . '/routes/web.php';
$routes = (string) file_get_contents($routesFile);

$passed = 0;
$failed = 0;

function check_composition(bool $condition, string $message): void
{
    global $passed, $failed;

    if ($condition) {
        $passed++;
        echo "PASS: {$message}\n";
        return;
    }

    $failed++;
    echo "FAIL: {$message}\n";
}

$expectedPostRoutes = [
    '/login',
    '/register',
    '/logout',
    '/writs',
    '/writs/{id}/update',
    '/writs/{id}/delete',
    '/writs/{id}/comments',
    '/comments/{id:[1-9][0-9]*}/reply',
    '/comments/{id:[1-9][0-9]*}/update',
    '/comments/{id:[1-9][0-9]*}/delete',
    '/comments/{id:[1-9][0-9]*}/upvote',
    '/comments/{id:[1-9][0-9]*}/downvote',
    '/follow/{id:[1-9][0-9]*}',
    '/bookmarks/{id:[1-9][0-9]*}',
    '/lab',
    '/lab/analyze',
];

preg_match_all(
    '/(?:(?:->middleware\((.*?)\)\s*)*)->post\(\s*[\'"]([^\'"]+)[\'"]/s',
    $routes,
    $matches,
    PREG_SET_ORDER
);

$actualPostRoutes = [];

foreach ($matches as $match) {
    $middleware = (string) ($match[1] ?? '');
    $route = (string) ($match[2] ?? '');

    $actualPostRoutes[] = [
        'route' => $route,
        'middleware' => $middleware,
    ];
}

check_composition(
    count($actualPostRoutes) === 16,
    'Exactly 16 POST routes are registered'
);

foreach ($expectedPostRoutes as $expectedRoute) {
    $found = null;

    foreach ($actualPostRoutes as $postRoute) {
        if ($postRoute['route'] === $expectedRoute) {
            $found = $postRoute;
            break;
        }
    }

    check_composition(
        $found !== null,
        "POST route exists: {$expectedRoute}"
    );

    if ($found === null) {
        continue;
    }

    $middleware = $found['middleware'];

    check_composition(
        str_contains($middleware, "'csrf'")
            || str_contains($middleware, '"csrf"'),
        "CSRF protects POST route: {$expectedRoute}"
    );

    check_composition(
        str_contains($middleware, "'ratelimit'")
            || str_contains($middleware, '"ratelimit"'),
        "Rate limiting protects POST route: {$expectedRoute}"
    );
}

$authRequired = [
    '/logout',
    '/writs',
    '/writs/{id}/update',
    '/writs/{id}/delete',
    '/writs/{id}/comments',
    '/comments/{id:[1-9][0-9]*}/reply',
    '/comments/{id:[1-9][0-9]*}/update',
    '/comments/{id:[1-9][0-9]*}/delete',
    '/comments/{id:[1-9][0-9]*}/upvote',
    '/comments/{id:[1-9][0-9]*}/downvote',
    '/follow/{id:[1-9][0-9]*}',
    '/bookmarks/{id:[1-9][0-9]*}',
];

foreach ($authRequired as $expectedRoute) {
    $found = null;

    foreach ($actualPostRoutes as $postRoute) {
        if ($postRoute['route'] === $expectedRoute) {
            $found = $postRoute;
            break;
        }
    }

    $middleware = $found['middleware'] ?? '';

    check_composition(
        str_contains($middleware, "'auth'")
            || str_contains($middleware, '"auth"'),
        "Authentication protects state-changing route: {$expectedRoute}"
    );
}

check_composition(
    preg_match(
        '/->middleware\(\[\s*[\'"]guest[\'"]\s*,\s*[\'"]ratelimit[\'"]\s*,\s*[\'"]csrf[\'"]\s*\]\)\s*->post\(\s*[\'"]\/login[\'"]/s',
        $routes
    ) === 1,
    'Login has guest + rate-limit + CSRF boundary'
);

check_composition(
    preg_match(
        '/->middleware\(\[\s*[\'"]guest[\'"]\s*,\s*[\'"]ratelimit[\'"]\s*,\s*[\'"]csrf[\'"]\s*\]\)\s*->post\(\s*[\'"]\/register[\'"]/s',
        $routes
    ) === 1,
    'Registration has guest + rate-limit + CSRF boundary'
);

check_composition(
    preg_match(
        '/->middleware\(\[\s*[\'"]auth[\'"]\s*,\s*[\'"]ratelimit[\'"]\s*,\s*[\'"]csrf[\'"]\s*\]\)\s*->post\(\s*[\'"]\/logout[\'"]/s',
        $routes
    ) === 1,
    'Logout has auth + rate-limit + CSRF boundary'
);

check_composition(
    preg_match(
        '/->get\(\s*[\'"]\/logout[\'"]/',
        $routes
    ) === 0,
    'Logout has no GET route'
);

check_composition(
    preg_match(
        '/\$router->get\(\s*[\'"]\/[\'"]/',
        $routes
    ) === 1,
    'Public home route remains available'
);

check_composition(
    preg_match(
        '/->get\(\s*[\'"]\/search[\'"]/',
        $routes
    ) === 1,
    'Public search remains GET'
);

echo "Security Composition Regression: {$passed}/" . ($passed + $failed) . " passed\n";

exit($failed === 0 ? 0 : 1);
