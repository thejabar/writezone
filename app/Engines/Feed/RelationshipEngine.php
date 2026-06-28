<?php

declare(strict_types=1);

namespace App\Engines\Feed;

use App\Engines\Contracts\Processor;
use App\Intelligence\Collections\SignalCollection;
use App\Intelligence\Signals\RelationshipSignal;
use App\Models\Follow;

final class RelationshipEngine implements Processor
{
    public function process(
        mixed $payload
    ): mixed {

        $viewer = $payload['viewer'] ?? null;
        $writ = $payload['writ'] ?? null;

        if (! $viewer || ! $writ) {
            return $payload;
        }

        $signals = $payload['signals'] ?? new SignalCollection();

        if (
            Follow::isFollowing(
                (int) $viewer->id,
                (int) $writ->user_id
            )
        ) {
            $signals->add(
                new RelationshipSignal(
                    40,
                    'Viewer follows author'
                )
            );
        }

        $payload['signals'] = $signals;

        return $payload;
    }
}