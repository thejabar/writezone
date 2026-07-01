<?php

declare(strict_types=1);

namespace App\Feed;

final class FeedItem
{
    public function __construct(
        public readonly int $id,
        public readonly string $public_id,
        public readonly int $user_id,
        public readonly string $content,
        public readonly string $created_at,
        public readonly ?string $updated_at,
        public readonly string $handle,
        public readonly string $username,
        public readonly ?string $display_name
    ) {
    }

    public static function fromRow(
        object $row
    ): self {

        return new self(
            id: (int) $row->id,
            public_id: (string) $row->public_id,
            user_id: (int) $row->user_id,
            content: (string) $row->content,
            created_at: (string) $row->created_at,
            updated_at: $row->updated_at ?? null,
            handle: (string) $row->handle,
            username: (string) $row->username,
            display_name: $row->display_name ?? null
        );

    }
}