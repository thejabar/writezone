<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Models\Comment;
use App\Models\Writ;
use Core\Authentication\Auth;
use Core\Http\Request;
use Core\Http\Response;
class CommentController
{
    public function store(
        Request $request,
        string $id
    ): void {
        $writ = Writ::findByPublicId($id);
        if (! $writ) {
            flash(
                'error',
                'Writ not found.'
            );
            Response::redirect('/writs');
            return;
        }
        $content = trim(
            $request->input('content')
        );
        if ($content === '') {
            flash(
                'error',
                'Comment cannot be empty.'
            );
            Response::redirect(
                "/writs/{$writ->public_id}"
            );
            return;
        }
        Comment::create([
            'writ_id'  => (int) $writ->id,
            'user_id'  => Auth::id(),
            'parent_id'=> null,
            'content'  => $content,
        ]);
        flash(
            'success',
            'Comment added successfully.'
        );
        Response::redirect(
            "/writs/{$writ->public_id}"
        );
    }
}