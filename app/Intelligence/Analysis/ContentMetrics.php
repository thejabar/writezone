<?php

declare(strict_types=1);

namespace App\Intelligence\Analysis;

final class ContentMetrics
{
    public function __construct(
        public readonly int $characters,
        public readonly int $words,
        public readonly int $sentences,
        public readonly int $paragraphs,
        public readonly int $mentions,
        public readonly int $hashtags,
        public readonly int $links,
        public readonly int $emojis,

        /*
        |--------------------------------------------------------------------------
        | Intelligence Metrics
        |--------------------------------------------------------------------------
        */

        public readonly int $readingTime,
        public readonly float $averageSentenceLength,
        public readonly float $averageParagraphLength,
        public readonly int $questions,
        public readonly int $exclamations,
    ) {
    }
}