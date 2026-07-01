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
}