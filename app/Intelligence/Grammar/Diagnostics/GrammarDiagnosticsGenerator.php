<?php

declare(strict_types=1);

namespace App\Intelligence\Grammar\Diagnostics;

use App\Intelligence\Analysis\GrammarReport;

final class GrammarDiagnosticsGenerator
{
    public function generate(
        GrammarReport $grammar
    ): GrammarDiagnostics {

        return new GrammarDiagnostics(

            capitalizationConsistent:
                $grammar->capitalizedSentences
                ===
                $grammar->sentenceEndings,

            sentenceEndingsConsistent:
                $grammar->sentenceEndings > 0,

            balancedQuotationMarks:
                $grammar->quotationMarks % 2 === 0,

            balancedParentheses:
                $grammar->parentheses % 2 === 0,

            doubleSpacesDetected:
                $grammar->doubleSpaces > 0,

            repeatedPunctuationDetected:
                $grammar->repeatedPunctuation > 0,

            heavyCommaUsage:
                $grammar->commas > 20,

            longSentencesDetected:
                false,
        );
    }
}
