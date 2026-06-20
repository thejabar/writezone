<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Models\Comment;
use App\Models\CommentVote;
use App\Models\Writ;
use Core\Authentication\Auth;
use Core\Http\Request;
use Core\Http\Response;
class CommentVoteController
{
    public function upvote(
        Request $request,
        string $id
    ): void {
        $this->vote(
            (int) $id,
            1
        );
    }
    public function downvote(
        Request $request,
        string $id
    ): void {
        $this->vote(
            (int) $id,
            -1
        );
    }
    private function vote(
        int $commentId,
        int $vote
    ): void {
        $comment = Comment::find($commentId);
        if (! $comment) {
            flash(
                'error',
                'Comment not found.'
            );
            Response::redirect('/writs');
            return;
        }
        CommentVote::toggle(
            (int) $comment->id,
            (int) Auth::id(),
            $vote
        );
        $writ = Writ::find(
            (int) $comment->writ_id
        );
        if (! $writ) {
            flash(
                'error',
                'Writ not found.'
            );
            Response::redirect('/writs');
            return;
        }
        if (
            ! isset($writ->public_id)
            || empty($writ->public_id)
        ) {
            flash(
                'error',
                'Invalid writ reference.'
            );
            Response::redirect('/writs');
            return;
        }
        Response::redirect(
            '/writs/' . $writ->public_id
        );
        return;
    }
}