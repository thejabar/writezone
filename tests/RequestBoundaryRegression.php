<?php
declare(strict_types=1);

define("BASE_PATH", dirname(__DIR__));
require BASE_PATH . "/vendor/autoload.php";

use App\Middleware\RequestBoundaryMiddleware;
use Core\Http\Request;

$m = new RequestBoundaryMiddleware();
$pass = 0;
$fail = 0;

$check = static function (bool $ok, string $name) use (&$pass, &$fail): void {
    if ($ok) { echo "PASS: {$name}\n"; $pass++; }
    else { echo "FAIL: {$name}\n"; $fail++; }
};

$run = static function (array $get, array $post, string $length = "0") use ($m): array {
    $_GET = $get;
    $_POST = $post;
    $_SERVER["CONTENT_LENGTH"] = $length;
    $called = false;
    $result = $m->handle(new Request(), static function () use (&$called): bool { $called = true; return true; });
    return [$result, $called];
};

[$result, $called] = $run([], []);
$check($result === true && $called, "normal request passes");

[$result, $called] = $run(["q" => ["bad"]], []);
$check(!$called, "array input rejected");

[$result, $called] = $run([], [], "1048577");
$check(!$called, "oversized request rejected");

$many = []; for ($i = 0; $i < 101; $i++) { $many["f{$i}"] = "x"; }
[$result, $called] = $run($many, []);
$check(!$called, "too many variables rejected");

$long = str_repeat("x", 10001);
[$result, $called] = $run(["q" => $long], []);
$check(!$called, "oversized scalar rejected");

$_GET = [];
$_POST = [];
$_SERVER["CONTENT_LENGTH"] = "0";

echo "Request Boundary: {$pass} passed, {$fail} failed\n";
exit($fail > 0 ? 1 : 0);
