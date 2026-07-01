<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use App\Models\Writ;
use App\Models\Follow;
use Core\Authentication\Auth;
use Core\Http\Request;

class ProfileController
{
    
public function show(
    Request $request,
    string $handle
): string {
    $user = User::where(
        'handle',
        $handle
    );
    if (! $user) {
        return 'User not found.';
    }
    return view('profile.show', [
        'user' => $user,
        'writs' => Writ::byUser(
    (int) $user->id
),
        'followersCount' =>
            Follow::followersCount(
                (int) $user->id
            ),
        'followingCount' =>
            Follow::followingCount(
                (int) $user->id
            ),
        'isFollowing' =>
            Auth::check()
            ? Follow::isFollowing(
                (int) Auth::id(),
                (int) $user->id
            )
            : false,
    ]);
}
}