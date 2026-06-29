<?php

declare(strict_types=1);

namespace App\Intelligence\Processors;

use App\Engines\Contracts\Processor;
use App\Feed\FeedCandidate;
use App\Intelligence\Signals\RelationshipSignal;
use App\Models\Follow;

final class RelationshipProcessor implements Processor
{
    /**
     * @param FeedCandidate[] $data
     * @return FeedCandidate[]
     */
    public function process(
        array $data
    ): array {

        foreach ($data as $candidate) {

            if (! $candidate instanceof FeedCandidate) {
                continue;
            }

            $viewerId = $candidate->metadata['viewer_id'] ?? null;

            if ($viewerId === null) {
                continue;
            }

            $following = Follow::isFollowing(
                (int) $viewerId,
                (int) $candidate->writ->user_id
            );

            $candidate->signals->add(
                new RelationshipSignal(
                    $following
                )
            );
        }

        return $data;
    }
}