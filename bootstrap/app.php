<?php
declare(strict_types=1);
use Dotenv\Dotenv;
use Core\Container\Container;
use Core\Http\Request;
use Core\Routing\Router;
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
return new class {
    public function run(): void
    {
        $dotenv = Dotenv::createImmutable(BASE_PATH);
        $dotenv->load();
        $container = new Container();
        $router = new Router($container);
        $router->alias(
            'auth',
            AuthMiddleware::class
        );
        $router->alias(
            'guest',
            GuestMiddleware::class
        );
        $routes = require BASE_PATH . '/routes/web.php';
        $routes($router);
        $router->dispatch(new Request());
    }
};