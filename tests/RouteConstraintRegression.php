<?php
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
require BASE_PATH . '/vendor/autoload.php';

use Core\Container\Container;
use Core\Http\Request;
use Core\Routing\Router;

$pass = 0;
$fail = 0;

$check = static function (bool $ok, string $name) use (&$pass, &$fail): void {
    if ($ok) {
        echo "PASS: {$name}\n";
        $pass++;
    } else {
        echo "FAIL: {$name}\n";
        $fail++;
    }
};

$router = new Router(new Container());

$matched = [];

$router->get('/comments/{id:[1-9][0-9]*}/edit', static function (Request $request, string $id) use (&$matched): void {
    $matched[] = $id;
});

$router->get('/follow/{id:[1-9][0-9]*}', static function (Request $request, string $id) use (&$matched): void {
    $matched[] = $id;
});

$router->get('/bookmarks/{id:[1-9][0-9]*}', static function (Request $request, string $id) use (&$matched): void {
    $matched[] = $id;
});

$router->get('/writs/{id}', static function (Request $request, string $id) use (&$matched): void {
    $matched[] = 'writ:' . $id;
});

$setRequest = static function (string $uri): void {
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = $uri;
};

$setRequest('/comments/42/edit');
$router->dispatch(new Request());
$check($matched === ['42'], 'positive comment ID matches');

$matched = [];
$setRequest('/comments/0/edit');
$router->dispatch(new Request());
$check($matched === [], 'comment ID zero does not match');

$matched = [];
$setRequest('/comments/abc/edit');
$router->dispatch(new Request());
$check($matched === [], 'non-numeric comment ID does not match');

$matched = [];
$setRequest('/follow/42');
$router->dispatch(new Request());
$check($matched === ['42'], 'positive follow ID matches');

$matched = [];
$setRequest('/follow/abc');
$router->dispatch(new Request());
$check($matched === [], 'non-numeric follow ID does not match');

$matched = [];
$setRequest('/bookmarks/42');
$router->dispatch(new Request());
$check($matched === ['42'], 'positive bookmark ID matches');

$matched = [];
$setRequest('/bookmarks/abc');
$router->dispatch(new Request());
$check($matched === [], 'non-numeric bookmark ID does not match');

$matched = [];
$setRequest('/writs/abc-public-id');
$router->dispatch(new Request());
$check($matched === ['writ:abc-public-id'], 'Writ public ID remains unrestricted');

echo "Route Constraints: {$pass} passed, {$fail} failed\n";
exit($fail > 0 ? 1 : 0);
