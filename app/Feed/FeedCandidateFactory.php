<?php

declare(strict_types=1);

namespace App\Feed;

use App\Intelligence\Collections\SignalCollection;

final class FeedCandidateFactory
{
    /**
     * @param object[] $rows
     * @return FeedCandidate[]
     */
    public static function fromFeed(
        array $rows,
        array $metadata = []
    ): array {

        return array_map(

            static fn (object $row): FeedCandidate =>

                new FeedCandidate(
                    item: FeedItem::fromRow($row),
                    signals: new SignalCollection(),
                    metadata: $metadata
                ),

            $rows

        );

    }
}