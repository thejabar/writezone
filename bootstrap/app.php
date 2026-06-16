<?php

declare(strict_types=1);

use Core\Container\Container;
use Core\Http\Request;
use Core\Routing\Router;

return new class {

    public function run(): void
    {
        $container = new Container();

        $router = new Router($container);

        $routes = require BASE_PATH . '/routes/web.php';

        $routes($router);

        $router->dispatch(new Request());
    }
};