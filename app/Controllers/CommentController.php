<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Services\MentionService;
use App\Models\Comment;
use App\Models\Notification;
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
            flash('error', 'Writ not found.');
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
            'writ_id'   => (int) $writ->id,
            'user_id'   => Auth::id(),
            'parent_id' => null,
            'content'   => $content,
        ]);
        flash(
            'success',
            'Comment added successfully.'
        );
        Response::redirect(
            "/writs/{$writ->public_id}"
        );
    }
    public function reply(
        Request $request,
        string $id
    ): void {
        $parent = Comment::find((int) $id);
        if (! $parent) {
            flash(
                'error',
                'Comment not found.'
            );
            Response::redirect('/writs');
            return;
        }
        $writ = Writ::findWithAuthor(
            (int) $parent->writ_id
        );
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
                'Reply cannot be empty.'
            );
            Response::redirect(
                "/writs/{$writ->public_id}"
            );
            return;
        }
        $replyId = Comment::create([
    'writ_id'   => (int) $parent->writ_id,
    'user_id'   => Auth::id(),
    'parent_id' => (int) $parent->id,
    'content'   => $content,
]);

MentionService::notifyMentions(
    $content,
    (int) Auth::id(),
    'mention_reply',
    $replyId
);
        if (
            (int) $parent->user_id !== (int) Auth::id()
        ) {
            Notification::create([
                'user_id'      => (int) $parent->user_id,
                'actor_id'     => (int) Auth::id(),
                'type'         => 'reply_created',
                'reference_id' => (int) $parent->id,
            ]);
        }
        flash(
            'success',
            'Reply added successfully.'
        );
        Response::redirect(
            "/writs/{$writ->public_id}"
        );
    }
    public function edit(
        Request $request,
        string $id
    ): string {
        $comment = Comment::findWithAuthor(
            (int) $id
        );
        if (! $comment) {
            return 'Comment not found.';
        }
        if (! Comment::belongsToUser(
            (int) $id,
            (int) Auth::id()
        )) {
            return 'Unauthorized';
        }
        return view(
            'comments.edit',
            [
                'comment' => $comment,
            ]
        );
    }
    public function update(
        Request $request,
        string $id
    ): void {
        $comment = Comment::find(
            (int) $id
        );
        if (! $comment) {
            flash(
                'error',
                'Comment not found.'
            );
            Response::redirect('/writs');
            return;
        }
        if (! Comment::belongsToUser(
            (int) $id,
            (int) Auth::id()
        )) {
            flash(
                'error',
                'Unauthorized.'
            );
            Response::redirect('/writs');
            return;
        }
        Comment::updateById(
            (int) $id,
            [
                'content' => trim(
                    $request->input('content')
                ),
            ]
        );
        $writ = Writ::findWithAuthor(
            (int) $comment->writ_id
        );
        flash(
            'success',
            'Comment updated successfully.'
        );
        Response::redirect(
            "/writs/{$writ->public_id}"
        );
    }
    public function delete(
        Request $request,
        string $id
    ): void {
        $comment = Comment::find(
            (int) $id
        );
        if (! $comment) {
            flash(
                'error',
                'Comment not found.'
            );
            Response::redirect('/writs');
            return;
        }
        if (! Comment::belongsToUser(
            (int) $id,
            (int) Auth::id()
        )) {
            flash(
                'error',
                'Unauthorized.'
            );
            Response::redirect('/writs');
            return;
        }
        $writ = Writ::findWithAuthor(
            (int) $comment->writ_id
        );
        Comment::deleteById(
            (int) $id
        );
        flash(
            'success',
            'Comment deleted successfully.'
        );
        Response::redirect(
            "/writs/{$writ->public_id}"
        );
    }
}