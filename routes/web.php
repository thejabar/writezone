<?php

declare(strict_types=1);

use App\Controllers\HomeController;
use App\Controllers\WritController;
use Core\Routing\Router;
use App\Controllers\AuthController;

return function (Router $router): void {

    $router->get('/', [HomeController::class, 'index']);

    $router->get('/writs/{id}', [WritController::class, 'show']);

    $router->get('/login', [AuthController::class, 'login']);
    
    $router->get('/login', [AuthController::class, 'showLogin']);
    
    $router->post('/login', [AuthController::class, 'login']);
    
    $router->get('/register', [AuthController::class, 'showRegister']);
    
    $router->post('/register', [AuthController::class, 'register']);

    $router->get('/logout', [AuthController::class, 'logout']);

    $router
        ->middleware('auth')
        ->get('/dashboard', function () {
            return 'Dashboard';
        });
};