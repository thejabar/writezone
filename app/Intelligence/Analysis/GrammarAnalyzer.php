<?php

declare(strict_types=1);

namespace App\Intelligence\Analysis;

use App\Intelligence\Lab\AnalysisContext;
use App\Intelligence\Support\SentenceParser;

final class GrammarAnalyzer
{
    public function analyze(
        AnalysisContext $context
    ): GrammarReport {

        /*
        |--------------------------------------------------------------------------
        | Shared Analysis Context
        |--------------------------------------------------------------------------
        */

        $content = $context->content();

        $punctuation = $context->punctuation();

        /*
        |--------------------------------------------------------------------------
        | Sentence Parsing
        |--------------------------------------------------------------------------
        */

        $sentences = SentenceParser::parse(
            $content
        );

        $sentenceEndings = count(
            $sentences
        );

        $capitalizedSentences = 0;

        foreach ($sentences as $sentence) {

            $sentence = ltrim(
                $sentence
            );

            if ($sentence === '') {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Ignore opening punctuation
            |--------------------------------------------------------------------------
            */

            $sentence = ltrim(
                $sentence,
                "\"'“”‘’([{"
            );

            if ($sentence === '') {
                continue;
            }

            $firstCharacter = mb_substr(
                $sentence,
                0,
                1
            );

            if (
                preg_match(
                    '/^\p{Lu}$/u',
                    $firstCharacter
                )
            ) {

                $capitalizedSentences++;

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Grammar Metrics
        |--------------------------------------------------------------------------
        */

        $doubleSpaces = preg_match_all(
            '/ {2,}/u',
            $content
        );

        /*
        |--------------------------------------------------------------------------
        | Grammar Score
        |--------------------------------------------------------------------------
        */

        $score = 100.0;

        if ($doubleSpaces > 0) {

            $score -= 5;

        }

        if (
            $punctuation->repeatedPunctuation > 0
        ) {

            $score -= 10;

        }

        if (
            $capitalizedSentences < $sentenceEndings
        ) {

            $score -= min(
                10,
                $sentenceEndings - $capitalizedSentences
            );

        }

        if (
            !$punctuation->balancedQuotes
        ) {

            $score -= 5;

        }

        if (
            !$punctuation->balancedParentheses
        ) {

            $score -= 5;

        }

        $score = max(
            0.0,
            $score
        );

        /*
        |--------------------------------------------------------------------------
        | Grammar Report
        |--------------------------------------------------------------------------
        */

        return new GrammarReport(

            score: $score,

            capitalizedSentences:
                $capitalizedSentences,

            sentenceEndings:
                $sentenceEndings,

            doubleSpaces:
                $doubleSpaces,

            repeatedPunctuation:
                $punctuation->repeatedPunctuation,

            commas:
                $punctuation->commas,

            semicolons:
                $punctuation->semicolons,

            colons:
                $punctuation->colons,

            quotationMarks:
                $punctuation->quotationMarks,

            parentheses:
                $punctuation->parentheses,

        );

    }
}