<?php

declare(strict_types=1);

namespace App\Events\Events;

use App\Events\Contracts\Event;
use DateTimeImmutable;

final class WritPublished implements Event
{
    private readonly DateTimeImmutable $occurredAt;

    public function __construct(
        private readonly int $writId,
        private readonly int $authorId,
        ?DateTimeImmutable $occurredAt = null
    ) {
        $this->occurredAt = $occurredAt ?? new DateTimeImmutable();
    }

    public function name(): string
    {
        return 'writ.published';
    }

    public function payload(): array
    {
        return [
            'writ_id'   => $this->writId,
            'author_id' => $this->authorId,
        ];
    }

    public function occurredAt(): DateTimeImmutable
    {
        return $this->occurredAt;
    }
}