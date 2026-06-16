<?php

declare(strict_types=1);

use Core\Http\Request;
use Core\Routing\Router;

return new class {

    public function run(): void
    {
        $router = new Router();

        $routes = require BASE_PATH . '/routes/web.php';

        $routes($router);

        $router->dispatch(new Request());
    }
};
