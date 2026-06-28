<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Writ;

final class WritPublisher
{
    public function publish(
        int $authorId,
        string $content
    ): array {

        $publicId = Writ::generatePublicId();

        $writId = Writ::create([
            'public_id' => $publicId,
            'user_id'   => $authorId,
            'content'   => $content,
        ]);

        MentionService::notifyMentions(
            $content,
            $authorId,
            'mention_writ',
            $writId
        );

        return [
            'id'        => $writId,
            'public_id' => $publicId,
        ];
    }
}