<?php

declare(strict_types=1);

namespace App\Intelligence\Analysis;

final class ReadabilityReport
{
    public function __construct(

        public readonly float $score,

        public readonly int $shortSentences,

        public readonly int $longSentences,

        public readonly float $averageSentenceLength,

        public readonly float $averageParagraphLength,

        public readonly string $readingFlow,

        public readonly string $difficulty,

        public readonly string $paragraphBalance,

    ) {
    }
}