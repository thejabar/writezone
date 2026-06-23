<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Models\Notification;
use Core\Authentication\Auth;
use Core\Http\Request;
class NotificationController
{
    public function index(
        Request $request
    ): string {
        Notification::markAllRead(
            (int) Auth::id()
        );
        return view(
            'notifications.index',
            [
                'notifications' => Notification::forUser(
                    (int) Auth::id()
                ),
            ]
        );
    }
}