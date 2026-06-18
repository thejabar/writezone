<?php
declare(strict_types=1);
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\ProfileController;
use App\Controllers\WritController;
use Core\Http\Request;
use Core\Routing\Router;
return function (Router $router): void {
    $router->get('/', [
        HomeController::class,
        'index',
    ]);
    $router
        ->middleware('guest')
        ->get('/login', [
            AuthController::class,
            'showLogin',
        ]);
    $router
        ->middleware('guest')
        ->post('/login', [
            AuthController::class,
            'login',
        ]);
    $router
        ->middleware('guest')
        ->get('/register', [
            AuthController::class,
            'showRegister',
        ]);
    $router
        ->middleware('guest')
        ->post('/register', [
            AuthController::class,
            'register',
        ]);
    $router->get('/logout', [
        AuthController::class,
        'logout',
    ]);
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
    $router
        ->middleware('auth')
        ->get('/writs/{id}/edit', [
            WritController::class,
            'edit',
        ]);
    $router
        ->middleware('auth')
        ->post('/writs/{id}/update', [
            WritController::class,
            'update',
        ]);
    $router
        ->middleware('auth')
        ->post('/writs/{id}/delete', [
            WritController::class,
            'delete',
        ]);
    $router->get('/writs/{id}', [
        WritController::class,
        'show',
    ]);
    $router->get('/@{handle}', [
        ProfileController::class,
        'show',
    ]);
};