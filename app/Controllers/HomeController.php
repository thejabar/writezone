<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\GreetingService;
use Core\Http\Request;

class HomeController
{
    public function __construct(
        private GreetingService $greeting
    ) {}

    public function index(Request $request): string
    {
        return $this->greeting->message();
    }
}