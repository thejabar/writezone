<?php

declare(strict_types=1);

namespace App\Services;

use App\Engines\Feed\FeedEngine;

final class FeedService
{
    public static function get(
        array $payload = []
    ): array {

        return (new FeedEngine())
            ->execute($payload);

    }
}