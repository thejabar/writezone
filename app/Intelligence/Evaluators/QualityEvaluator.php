<?php

declare(strict_types=1);

namespace App\Intelligence\Evaluators;

use App\Intelligence\Analysis\ContentMetrics;

final class QualityEvaluator
{
    public function evaluate(
        ContentMetrics $metrics
    ): float {

        $score = 0.0;

        /*
        |--------------------------------------------------------------------------
        | Length
        |--------------------------------------------------------------------------
        */

        if ($metrics->words >= 20) {
            $score += 10;
        }

        if ($metrics->words >= 80) {
            $score += 10;
        }

        /*
        |--------------------------------------------------------------------------
        | Structure
        |--------------------------------------------------------------------------
        */

        if ($metrics->paragraphs >= 2) {
            $score += 5;
        }

        if ($metrics->sentences >= 3) {
            $score += 5;
        }

        /*
        |--------------------------------------------------------------------------
        | Spam Penalties
        |--------------------------------------------------------------------------
        */

        if ($metrics->hashtags > 5) {
            $score -= 5;
        }

        if ($metrics->mentions > 5) {
            $score -= 5;
        }

        if ($metrics->links > 2) {
            $score -= 10;
        }

        return max(
            0.0,
            $score
        );
    }
}
