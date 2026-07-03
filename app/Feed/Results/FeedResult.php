<?php

declare(strict_types=1);

namespace App\Feed\Results;

use App\Support\Collections\Collection;

final class FeedResult
{
    public function __construct(
        private readonly Collection $candidates,
        private readonly array $metadata = []
    ) {
    }

    /**
     * Ranked feed candidates.
     */
    public function candidates(): Collection
    {
        return $this->candidates;
    }

    /**
     * Number of ranked candidates.
     */
    public function count(): int
    {
        return $this->candidates->count();
    }

    /**
     * Is the feed empty?
     */
    public function isEmpty(): bool
    {
        return $this->candidates->isEmpty();
    }

    /**
     * Feed metadata.
     */
    public function metadata(): array
    {
        return $this->metadata;
    }
}