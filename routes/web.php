<?php

declare(strict_types=1);

use App\Controllers\HomeController;
use Core\Routing\Router;

return function (Router $router): void {

    $router->get('/', [HomeController::class, 'index']);

};