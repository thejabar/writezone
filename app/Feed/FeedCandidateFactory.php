<?php

declare(strict_types=1);

namespace App\Feed;

use App\Intelligence\Collections\SignalCollection;
use App\Models\Writ;

final class FeedCandidateFactory
{
    /**
     * @param Writ[] $writs
     * @return FeedCandidate[]
     */
    public static function fromWrits(
        array $writs
    ): array {

        return array_map(

            fn (Writ $writ) =>

                new FeedCandidate(
                    writ: $writ,
                    signals: new SignalCollection()
                ),

            $writs

        );

    }
}