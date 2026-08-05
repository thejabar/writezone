<?php

declare(strict_types=1);

namespace App\Intelligence\Analysis;

use App\Intelligence\Lab\AnalysisContext;

final class ReadabilityAnalyzer
{
    public function analyze(
        AnalysisContext $context
    ): ReadabilityReport {

        /*
        |--------------------------------------------------------------------------
        | Shared Analysis Context
        |--------------------------------------------------------------------------
        */

        $metrics = $context->metrics();

        $sentences = $context->sentences();

        /*
        |--------------------------------------------------------------------------
        | Sentence Distribution
        |--------------------------------------------------------------------------
        */

        $shortSentences = 0;

        $longSentences = 0;

        if ($sentences->average <= 15) {

            $shortSentences = $sentences->total;

        } elseif ($sentences->average >= 25) {

            $longSentences = $sentences->total;

        }

        /*
        |--------------------------------------------------------------------------
        | Reading Flow
        |--------------------------------------------------------------------------
        */

        $readingFlow = 'Moderate';

        if (
            $metrics->averageSentenceLength <= 18
            &&
            $metrics->averageParagraphLength <= 80
        ) {

            $readingFlow = 'Smooth';

        } elseif (
            $metrics->averageSentenceLength >= 28
            ||
            $metrics->averageParagraphLength >= 140
        ) {

            $readingFlow = 'Dense';

        }

        /*
        |--------------------------------------------------------------------------
        | Reading Difficulty
        |--------------------------------------------------------------------------
        */

        $difficulty = 'Standard';

        if (
            $metrics->averageSentenceLength <= 15
        ) {

            $difficulty = 'Easy';

        } elseif (
            $metrics->averageSentenceLength >= 25
        ) {

            $difficulty = 'Advanced';

        }

        /*
        |--------------------------------------------------------------------------
        | Paragraph Balance
        |--------------------------------------------------------------------------
        */

        $paragraphBalance = 'Fair';

        if (
            $metrics->averageParagraphLength <= 80
        ) {

            $paragraphBalance = 'Excellent';

        } elseif (
            $metrics->averageParagraphLength <= 120
        ) {

            $paragraphBalance = 'Good';

        }

        /*
        |--------------------------------------------------------------------------
        | Readability Score
        |--------------------------------------------------------------------------
        */

        $score = 100.0;

        if ($longSentences > 0) {

            $score -= 10;

        }

        if (
            $metrics->averageParagraphLength > 120
        ) {

            $score -= 10;

        }

        if (
            $metrics->averageSentenceLength > 25
        ) {

            $score -= 10;

        }

        if (
            $readingFlow === 'Dense'
        ) {

            $score -= 10;

        }

        $score = max(
            0.0,
            $score
        );

        return new ReadabilityReport(

            score: $score,

            shortSentences: $shortSentences,

            longSentences: $longSentences,

            averageSentenceLength:
                $metrics->averageSentenceLength,

            averageParagraphLength:
                $metrics->averageParagraphLength,

            readingFlow: $readingFlow,

            difficulty: $difficulty,

            paragraphBalance:
                $paragraphBalance,

        );

    }
}