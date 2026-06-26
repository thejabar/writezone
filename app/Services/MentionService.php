<?php
declare(strict_types=1);
namespace App\Services;
use App\Models\Notification;
use App\Models\User;
class MentionService
{
    public static function render(
        string $content
    ): string {
        $content = htmlspecialchars(
            $content,
            ENT_QUOTES,
            'UTF-8'
        );
        $content = preg_replace(
            '/@([A-Za-z0-9_]+)/',
            '<a href="/@$1">@$1</a>',
            $content
        );
        return nl2br($content);
    }
    public static function extractHandles(
        string $content
    ): array {
        preg_match_all(
            '/@([A-Za-z0-9_]+)/',
            $content,
            $matches
        );
        return array_unique(
            $matches[1] ?? []
        );
    }
    public static function notifyMentions(
        string $content,
        int $actorId,
        string $type,
        int $referenceId
    ): void {
        $handles = static::extractHandles(
            $content
        );
        foreach ($handles as $handle) {
            $user = User::where(
                'handle',
                $handle
            );
            if (! $user) {
                continue;
            }
            if ((int) $user->id === $actorId) {
                continue;
            }
            $commentId = Comment::create([
    'writ_id'   => (int) $writ->id,
    'user_id'   => Auth::id(),
    'parent_id' => null,
    'content'   => $content,
]);

MentionService::notifyMentions(
    $content,
    (int) Auth::id(),
    'mention_comment',
    $commentId
);
        }
    }
}