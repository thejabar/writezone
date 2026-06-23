<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Models\Writ;
use Core\Http\Request;
class HomeController
{
    public function index(
        Request $request
    ): string {
        return view(
            'home.index',
            [
                'writs' => Writ::feed(),
            ]
        );
    }
}