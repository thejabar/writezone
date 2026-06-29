<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\FeedService;
use Core\Authentication\Auth;
use Core\Http\Request;

class HomeController
{
    public function index(
        Request $request
    ): string {

        return view(
            'home.index',
            [
                'writs' => FeedService::get([
                    'viewer_id' => Auth::id(),
                ]),
            ]
        );

    }
}