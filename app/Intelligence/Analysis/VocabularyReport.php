<?php

declare(strict_types=1);

namespace App\Intelligence\Analysis;

final class VocabularyReport
{
    public function __construct(

        public readonly int $totalWords,

        public readonly int $uniqueWords,

        public readonly float $lexicalDiversity,

        public readonly int $repeatedWords,

        public readonly int $fillerWords,

        public readonly int $transitionWords,

    ) {
    }
}
