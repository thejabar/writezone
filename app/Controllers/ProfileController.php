<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use App\Models\Writ;
use Core\Http\Request;

class ProfileController
{
    public function show(
        Request $request,
        string $handle
    ): string {

        $user = User::where('handle', $handle);

        if (! $user) {
            return 'User not found.';
        }

        return view('profile.show', [
            'user' => $user,
            'writs' => Writ::whereAll(
                'user_id',
                $user->id
            ),
        ]);
    }
}
