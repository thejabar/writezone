<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\GreetingService;

class HomeController
{
    public function __construct(
        private GreetingService $greeting
    ) {}

    public function index(): string
    {
        return $this->greeting->message();
    }
}