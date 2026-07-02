<?php

declare(strict_types=1);

namespace App\Feed\Results;

use App\Ranking\Results\RankedCandidate;

final class FeedResult
{
    /**
     * @param RankedCandidate[] $candidates
     */
    public function __construct(
        private readonly array $candidates,
        private readonly array $metadata = []
    ) {
    }

    /**
     * Ranked feed candidates.
     *
     * @return RankedCandidate[]
     */
    public function candidates(): array
    {
        return $this->candidates;
    }

    /**
     * Number of ranked candidates.
     */
    public function count(): int
    {
        return count($this->candidates);
    }

    /**
     * Is the feed empty?
     */
    public function isEmpty(): bool
    {
        return empty($this->candidates);
    }

    /**
     * Feed metadata.
     */
    public function metadata(): array
    {
        return $this->metadata;
    }
}