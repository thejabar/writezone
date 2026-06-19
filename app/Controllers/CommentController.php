<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Models\Comment;
use Core\Authentication\Auth;
use Core\Http\Request;
use Core\Http\Response;
class CommentController
{
    public function store(
        Request $request,
        string $writId
    ): string {
        $content = trim(
            $request->input('content')
        );
        if ($content === '') {
            flash(
                'error',
                'Comment cannot be empty.'
            );
            Response::redirect("/writs/{$writId}");
        }
        Comment::create([
            'writ_id' => (int) $writId,
            'user_id' => Auth::id(),
            'parent_id' => null,
            'content' => $content,
        ]);
        flash(
            'success',
            'Comment added successfully.'
        );
        Response::redirect("/writs/{$writId}");
    }
}