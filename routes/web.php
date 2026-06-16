<?php

declare(strict_types=1);

use App\Controllers\HomeController;
use App\Controllers\WritController;
use Core\Routing\Router;

return function (Router $router): void {

    $router->get('/', [HomeController::class, 'index']);
    $router->get('/writs/{id}', [WritController::class, 'show']);
    $router->post('/writs', [WritController::class, 'store']);
    $router
    ->middleware('auth')
    ->get('/dashboard', function () {
        return 'Dashboard';
    });

};