<?php
declare(strict_types=1);

define("BASE_PATH", dirname(__DIR__));
require BASE_PATH . "/vendor/autoload.php";

use App\Middleware\RequestBoundaryMiddleware;
use Core\Container\Container;
use Core\Http\Request;
use Core\Routing\Router;

$pass = 0;
$fail = 0;
$check = static function (bool $ok, string $name) use (&$pass, &$fail): void {
    echo ($ok ? "[PASS] " : "[FAIL] ") . $name . PHP_EOL;
    $ok ? $pass++ : $fail++;
};

$router = new Router(new Container());
$router->alias("request_boundary", RequestBoundaryMiddleware::class);
$router->globalMiddleware("request_boundary");
$router->post("/boundary-test", static function (): string {
    return "HANDLER_REACHED";
});

$run = static function (array $get, array $post, string $length = "0") use ($router): array {
    $_GET = $get;
    $_POST = $post;
    $_SERVER["REQUEST_METHOD"] = "POST";
    $_SERVER["REQUEST_URI"] = "/boundary-test";
    $_SERVER["CONTENT_LENGTH"] = $length;
    http_response_code(200);
    ob_start();
    $router->dispatch(new Request());
    $body = ob_get_clean();
    return [http_response_code(), $body];
};

[$status, $body] = $run([], []);
$check($status === 200 && $body === "HANDLER_REACHED", "global boundary permits normal request");

[$status, $body] = $run([], [], "1048577");
$check($status === 413 && $body === "Request entity too large.", "global boundary blocks oversized request");

$many = [];
for ($i = 0; $i < 101; $i++) { $many["f{$i}"] = "x"; }
[$status, $body] = $run([], $many);
$check($status === 400 && $body === "Too many input variables.", "global boundary blocks excessive variables");

[$status, $body] = $run(["q" => ["bad"]], []);
$check($status === 400 && $body === "Array input is not allowed.", "global boundary blocks array input");

$_GET = [];
$_POST = [];
$_SERVER["CONTENT_LENGTH"] = "0";

echo "Request Boundary Router: {$pass} passed, {$fail} failed" . PHP_EOL;
exit($fail > 0 ? 1 : 0);
