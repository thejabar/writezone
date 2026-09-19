<?php

declare(strict_types=1);

namespace App\Intelligence\Analysis;

use App\Intelligence\Lab\AnalysisContext;
use App\Intelligence\Language\LanguageRegistry;

final class VocabularyAnalyzer
{
    public function analyze(
        AnalysisContext $context
    ): VocabularyReport {

        /*
        |--------------------------------------------------------------------------
        | Shared Word Intelligence
        |--------------------------------------------------------------------------
        */

        $words = $context->words();

        /*
        |--------------------------------------------------------------------------
        | Lexical Diversity
        |--------------------------------------------------------------------------
        */

        $lexicalDiversity =
            $words->totalWords > 0
                ? round(
                    (
                        $words->uniqueWords /
                        $words->totalWords
                    ) * 100,
                    1
                )
                : 0.0;

        /*
        |--------------------------------------------------------------------------
        | Filler Words
        |--------------------------------------------------------------------------
        */

        $fillerWords = 0;

        $resources = LanguageRegistry::resources(
            $context->language()
        );

        $fillerList = $resources->fillerWords();

        foreach (
            $words->wordFrequency as $word => $count
        ) {

            if (
                in_array(
                    $word,
                    $fillerList,
                    true
                )
            ) {

                $fillerWords += $count;

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Transition Words
        |--------------------------------------------------------------------------
        */

        $transitionWords = 0;

        $transitionList = $resources->transitionWords();

        foreach (
            $words->wordFrequency as $word => $count
        ) {

            if (
                in_array(
                    $word,
                    $transitionList,
                    true
                )
            ) {

                $transitionWords += $count;

            }

        }

        return new VocabularyReport(

            totalWords:
                $words->totalWords,

            uniqueWords:
                $words->uniqueWords,

            lexicalDiversity:
                $lexicalDiversity,

            repeatedWords:
                $words->repeatedWords,

            fillerWords:
                $fillerWords,

            transitionWords:
                $transitionWords,

        );

    }
}