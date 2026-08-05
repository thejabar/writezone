<?php

declare(strict_types=1);

namespace App\Intelligence\Analysis;

final class PunctuationReport
{
    public function __construct(

        public readonly int $periods,

        public readonly int $commas,

        public readonly int $questionMarks,

        public readonly int $exclamationMarks,

        public readonly int $semicolons,

        public readonly int $colons,

        public readonly int $quotationMarks,

        public readonly int $parentheses,

        public readonly int $squareBrackets,

        public readonly int $curlyBraces,

        public readonly int $ellipses,

        public readonly int $repeatedPunctuation,

        public readonly bool $balancedQuotes,

        public readonly bool $balancedParentheses,

        public readonly bool $balancedSquareBrackets,

        public readonly bool $balancedCurlyBraces,

        public readonly float $punctuationDensity,

    ) {
    }
}