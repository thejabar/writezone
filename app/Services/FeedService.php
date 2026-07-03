<?php

declare(strict_types=1);

namespace App\Services;

use App\Engines\Feed\FeedEngine;
use App\Feed\Results\FeedResult;

final class FeedService
{
    public static function get(
        array $payload = []
    ): FeedResult {

        return (new FeedEngine())
            ->execute($payload);

    }
}