<?php

declare(strict_types=1);

namespace App\Intelligence\Grammar;

use App\Intelligence\Analysis\GrammarReport;

final class GrammarFeedbackGenerator
{
    public function generate(
        GrammarReport $grammar
    ): GrammarFeedback {

        $strengths = [];

        $warnings = [];

        $suggestions = [];

        /*
        |--------------------------------------------------------------------------
        | Grammar Coaching
        |--------------------------------------------------------------------------
        */

        if (
            $grammar->doubleSpaces > 0
        ) {

            $suggestions[] =
                'Remove unnecessary spaces between words for a cleaner reading experience.';
        }

        if (
            $grammar->repeatedPunctuation > 0
        ) {

            $suggestions[] =
                'Avoid repeated punctuation such as "!!" or "??" to maintain a professional writing style.';
        }

        if (
            $grammar->capitalizedSentences
            !==
            $grammar->sentenceEndings
        ) {

            $suggestions[] =
                'Ensure every sentence begins with a capital letter.';
        }

        if (
            $grammar->commas > 20
        ) {

            $suggestions[] =
                'Break long sentences into shorter ones to improve readability and reduce comma usage.';
        }

        /*
        |--------------------------------------------------------------------------
        | Positive Reinforcement
        |--------------------------------------------------------------------------
        */

        if (
            empty($suggestions)
        ) {

            $strengths[] =
                'Your grammar demonstrates strong consistency with no significant issues requiring attention.';
        }

        /*
        |--------------------------------------------------------------------------
        | Remove Duplicate Messages
        |--------------------------------------------------------------------------
        */

        $strengths = array_values(
            array_unique($strengths)
        );

        $warnings = array_values(
            array_unique($warnings)
        );

        $suggestions = array_values(
            array_unique($suggestions)
        );

        return new GrammarFeedback(

            strengths: $strengths,

            warnings: $warnings,

            suggestions: $suggestions,

        );
    }
}