<?php

declare(strict_types=1);

namespace App\Intelligence\Analysis;

final class WordReport
{
    public function __construct(

        public readonly int $totalWords,

        public readonly int $uniqueWords,

        public readonly int $repeatedWords,

        public readonly int $longWords,

        public readonly int $shortWords,

        public readonly float $averageWordLength,

        public readonly int $numbers,

        public readonly int $mentions,

        public readonly int $hashtags,

        public readonly int $urls,

        public readonly int $emails,

        public readonly int $acronyms,

        public readonly array $wordFrequency,

    ) {
    }
}