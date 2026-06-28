<?php

declare(strict_types=1);

namespace App\Feed;

use App\Intelligence\Collections\SignalCollection;
use App\Models\Writ;

final class FeedCandidate
{
    public function __construct(
        public readonly Writ $writ,
        public readonly SignalCollection $signals = new SignalCollection(),
        public readonly array $metadata = []
    ) {
    }
}