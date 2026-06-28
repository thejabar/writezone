<?php

declare(strict_types=1);

namespace App\Engines\Feed\Processors;

use App\Engines\Contracts\Processor;
use App\Models\Follow;

final class RelationshipProcessor implements Processor
{
    public function process(
        array $payload
    ): array {

        if (
            ! isset(
                $payload['viewer'],
                $payload['writ']
            )
        ) {
            return $payload;
        }

        $viewer = $payload['viewer'];
        $writ   = $payload['writ'];

        $payload['relationship'] = Follow::isFollowing(
            (int) $viewer->id,
            (int) $writ->user_id
        );

        return $payload;
    }
}