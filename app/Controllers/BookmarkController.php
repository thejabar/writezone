<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Models\Bookmark;
use App\Models\Writ;
use Core\Authentication\Auth;
use Core\Http\Request;
use Core\Http\Response;
class BookmarkController
{
    public function store(
        Request $request,
        string $id
    ): void {
        $writ = Writ::findByPublicId($id);
        if (! $writ) {
            Response::redirect('/writs');
            return;
        }
        if (! Bookmark::isBookmarked(
            (int) Auth::id(),
            (int) $writ->id
        )) {
            Bookmark::create([
                'user_id' => (int) Auth::id(),
                'writ_id' => (int) $writ->id,
            ]);
        }
        Response::redirect(
            "/writs/{$writ->public_id}"
        );
    }
    public function index(
        Request $request
    ): string {
        return view(
            'bookmarks.index',
            [
                'writs' => Bookmark::forUser(
                    (int) Auth::id()
                ),
            ]
        );
    }
}