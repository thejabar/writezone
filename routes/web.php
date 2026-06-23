<?php
declare(strict_types=1);
use App\Controllers\BookmarkController;
use App\Controllers\AuthController;
use App\Controllers\CommentController;
use App\Controllers\CommentVoteController;
use App\Controllers\HomeController;
use App\Controllers\FollowController;
use App\Controllers\NotificationController;
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
            return view('dashboard.index');
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
    $router
        ->middleware('auth')
        ->post('/writs/{id}/comments', [
            CommentController::class,
            'store',
        ]);
    $router
        ->middleware('auth')
        ->post('/comments/{id}/reply', [
            CommentController::class,
            'reply',
        ]);
    $router
        ->middleware('auth')
        ->get('/comments/{id}/edit', [
            CommentController::class,
            'edit',
        ]);
    $router
        ->middleware('auth')
        ->post('/comments/{id}/update', [
            CommentController::class,
            'update',
        ]);
    $router
        ->middleware('auth')
        ->post('/comments/{id}/delete', [
            CommentController::class,
            'delete',
        ]);
    $router
        ->middleware('auth')
        ->post('/comments/{id}/upvote', [
            CommentVoteController::class,
            'upvote',
        ]);
    $router
        ->middleware('auth')
        ->post('/comments/{id}/downvote', [
            CommentVoteController::class,
            'downvote',
        ]);
    $router
    ->middleware('auth')
    ->post('/follow/{id}', [
        FollowController::class,
        'follow',
    ]);
    $router
        ->middleware('auth')
        ->get('/notifications', [
            NotificationController::class,
            'index',
        ]);
    $router
    ->middleware('auth')
    ->post('/bookmarks/{id}', [
        BookmarkController::class,
        'store',
    ]);

$router
    ->middleware('auth')
    ->get('/bookmarks', [
        BookmarkController::class,
        'index',
    ]);

};