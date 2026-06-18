<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\WritController;
use Core\Http\Request;
use Core\Routing\Router;
use App\Controllers\ProfileController;

return function (Router $router): void {

    $router->get('/', [HomeController::class, 'index']);

    $router->get('/login', [AuthController::class, 'showLogin']);
    $router->post('/login', [AuthController::class, 'login']);

    $router->get('/register', [AuthController::class, 'showRegister']);
    $router->post('/register', [AuthController::class, 'register']);

    $router->get('/logout', [AuthController::class, 'logout']);

    $router
        ->middleware('auth')
        ->get('/dashboard', function (Request $request) {
            return 'Dashboard';
        });

    $router->get('/writs', [
        WritController::class,
        'index',
    ]);

    $router
        ->middleware('auth')
        ->get('/writs/create', [
            WritController::class,
            'create',
        ]);

    $router
        ->middleware('auth')
        ->post('/writs', [
            WritController::class,
            'store',
        ]);

    $router->get('/writs/{id}', [
        WritController::class,
        'show',
    ]);
    
    $router->get('/@{handle}', [
    ProfileController::class,
    'show'
]);
};