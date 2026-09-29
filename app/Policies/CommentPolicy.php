<?php
declare(strict_types=1);

namespace App\Policies;

use App\Models\Comment;

final class CommentPolicy
{
    public function update(int|string $userId, Comment $comment): bool
    {
        return (int) $comment->user_id === (int) $userId;
    }

    public function delete(int|string $userId, Comment $comment): bool
    {
        return (int) $comment->user_id === (int) $userId;
    }
}
