<?php

declare(strict_types=1);

namespace App\Intelligence\Analysis;

final class VocabularyAnalyzer
{
    public function analyze(
        string $content
    ): VocabularyReport {

        $content = trim($content);
        $words = preg_split(
    '/\PL+/u',
    mb_strtolower($content),
    -1,
    PREG_SPLIT_NO_EMPTY
);

$totalWords = count($words);

$uniqueWords = count(
    array_unique($words)
);

$repeatedWords =
    $totalWords - $uniqueWords;

$lexicalDiversity =
    $totalWords > 0
        ? ($uniqueWords / $totalWords) * 100
        : 0;

return new VocabularyReport(

    totalWords: $totalWords,

    uniqueWords: $uniqueWords,

    lexicalDiversity: $lexicalDiversity,

    repeatedWords: $repeatedWords,

    fillerWords: 0,

    transitionWords: 0,

);

        if ($content === '') {

            return new VocabularyReport(

                totalWords: 0,

                uniqueWords: 0,

                lexicalDiversity: 0,

                repeatedWords: 0,

                fillerWords: 0,

                transitionWords: 0,

            );

        }

        return new VocabularyReport(

            totalWords: 0,

            uniqueWords: 0,

            lexicalDiversity: 0,

            repeatedWords: 0,

            fillerWords: 0,

            transitionWords: 0,

        );

    }
}