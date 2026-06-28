<?php

declare(strict_types=1);

namespace App\Engines\Feed;

use App\Engines\Engine;
use App\Models\Writ;

class FeedEngine extends Engine
{
    public function execute(
        array $payload = []
    ): array {

        return Writ::feed();

    }
}