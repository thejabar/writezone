<?php

declare(strict_types=1);

namespace App\Intelligence\Grammar\Diagnostics;

final class GrammarDiagnostics
{
    public function __construct(

        public readonly bool $capitalizationConsistent,

        public readonly bool $sentenceEndingsConsistent,

        public readonly bool $balancedQuotationMarks,

        public readonly bool $balancedParentheses,

        public readonly bool $doubleSpacesDetected,

        public readonly bool $repeatedPunctuationDetected,

        public readonly bool $heavyCommaUsage,

        public readonly bool $longSentencesDetected,
    ) {
    }
}
