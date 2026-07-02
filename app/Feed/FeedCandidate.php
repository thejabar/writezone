<?php

declare(strict_types=1);

namespace App\Feed;

use App\Intelligence\Collections\SignalCollection;

final class FeedCandidate
{
    public function __construct(
        public readonly FeedItem $item,
        public readonly SignalCollection $signals = new SignalCollection(),
        public readonly array $metadata = []
    ) {
    }

    /**
     * Retrieve attached signals.
     */
    public function signals(): SignalCollection
    {
        return $this->signals;
    }

    /**
     * Retrieve the wrapped FeedItem.
     */
    public function item(): FeedItem
    {
        return $this->item;
    }

    /**
     * Retrieve workflow metadata.
     */
    public function metadata(): array
    {
        return $this->metadata;
    }
}