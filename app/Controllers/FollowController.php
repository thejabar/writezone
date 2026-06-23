<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Follow;
use App\Models\Notification;
use App\Models\User;
use Core\Authentication\Auth;
use Core\Http\Request;
use Core\Http\Response;

class FollowController
{
    public function follow(
        Request $request,
        string $id
    ): void {

        $targetUser = User::find((int) $id);

        if (! $targetUser) {
            Response::redirect('/');
            return;
        }

        if ((int) $targetUser->id === (int) Auth::id()) {
            Response::redirect(
                '/@' . $targetUser->handle
            );
            return;
        }

        if (! Follow::isFollowing(
            (int) Auth::id(),
            (int) $targetUser->id
        )) {

            Follow::create([
                'follower_id'  => (int) Auth::id(),
                'following_id' => (int) $targetUser->id,
            ]);

            Notification::create([
                'user_id'      => (int) $targetUser->id,
                'actor_id'     => (int) Auth::id(),
                'type'         => 'user_followed',
                'reference_id' => (int) Auth::id(),
            ]);
        }

        Response::redirect(
            '/@' . $targetUser->handle
        );
    }
}