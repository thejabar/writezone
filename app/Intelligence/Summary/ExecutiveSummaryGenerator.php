<?php

declare(strict_types=1);

namespace App\Intelligence\Summary;

use App\Intelligence\Lab\AnalysisReport;

final class ExecutiveSummaryGenerator
{
    public function generate(
        AnalysisReport $report
    ): ExecutiveSummary {

        $quality = $report->quality();

        $metrics = $report->metrics();

        $grammar = $report->grammar();

        $sentences = $report->sentences();

        $score = $quality->score();

        /*
        |--------------------------------------------------------------------------
        | Title
        |--------------------------------------------------------------------------
        */

        if ($score >= 90) {

            $title = 'Excellent Writing';

        } elseif ($score >= 80) {

            $title = 'Strong Draft';

        } elseif ($score >= 65) {

            $title = 'Good Foundation';

        } elseif ($score >= 45) {

            $title = 'Developing Draft';

        } else {

            $title = 'Needs Improvement';

        }

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $summary = [];

        if ($grammar->score >= 90) {

            $summary[] =
                'Grammar is consistently strong';

        } elseif ($grammar->score >= 70) {

            $summary[] =
                'Grammar is generally accurate';

        } else {

            $summary[] =
                'Grammar requires further attention';

        }

        if ($sentences->variety >= 80) {

            $summary[] =
                'sentence variety is excellent';

        } elseif ($sentences->variety >= 50) {

            $summary[] =
                'sentence variety is acceptable';

        } else {

            $summary[] =
                'sentence variety could be improved';

        }

        if ($metrics->paragraphs > 1) {

            $summary[] =
                'paragraph structure supports readability';

        } else {

            $summary[] =
                'paragraph organisation should be improved';

        }

        $summaryText =
            ucfirst(
                implode(', ', $summary)
            ) . '.';

        /*
        |--------------------------------------------------------------------------
        | Overall Assessment
        |--------------------------------------------------------------------------
        */

        $assessment = [];

        if ($metrics->paragraphs <= 1) {

            $assessment[] =
                'Separate ideas into multiple paragraphs for better readability.';

        }

        if ($grammar->commas > 20) {

            $assessment[] =
                'Reduce long comma-heavy sentences to improve flow.';

        }

        if ($grammar->capitalizedSentences !== $grammar->sentenceEndings) {

            $assessment[] =
                'Review sentence capitalization for consistency.';

        }

        if ($assessment === []) {

            $assessment[] =
                'Your writing demonstrates a strong overall balance of grammar, structure, and readability.';

        }

        return new ExecutiveSummary(

            title: $title,

            summary: $summaryText,

            overallAssessment:
                implode(
                    ' ',
                    $assessment
                ),

        );

    }
}