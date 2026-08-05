<?php

declare(strict_types=1);

namespace App\Intelligence\Analysis;

final class GrammarReport
{
    public function __construct(

        public readonly float $score,

        public readonly int $capitalizedSentences,

        public readonly int $sentenceEndings,

        public readonly int $doubleSpaces,

        public readonly int $repeatedPunctuation,

        public readonly int $commas,

        public readonly int $semicolons,

        public readonly int $colons,

        public readonly int $quotationMarks,

        public readonly int $parentheses,
    ) {
    }
}
