<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\EventDispatcher;
use App\Events\Events\WritPublished;
use App\Models\Writ;

final class WritPublisher
{
    public function __construct(
        private readonly EventDispatcher $events
    ) {
    }

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

        $this->events->dispatch(
            new WritPublished(
                $writId,
                $authorId
            )
        );

        return [
            'id'        => $writId,
            'public_id' => $publicId,
        ];
    }
}
