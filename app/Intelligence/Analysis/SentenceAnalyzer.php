<?php

declare(strict_types=1);

namespace App\Intelligence\Analysis;

final class SentenceAnalyzer
{
    public function analyze(
        string $content
    ): SentenceReport {

        $content = trim($content);

        if ($content === '') {

            return new SentenceReport(
                total: 0,
                shortest: 0,
                longest: 0,
                average: 0,
                variety: 0,
            );

        }

       $sentences = preg_split(

    '/(?<=[.!?])\s+/u',

    $content,

    -1,

    PREG_SPLIT_NO_EMPTY

);

$total = count(
    $sentences
);

$lengths = [];

foreach ($sentences as $sentence) {

    $words = preg_split(

        '/\s+/u',

        trim($sentence),

        -1,

        PREG_SPLIT_NO_EMPTY

    );

    $lengths[] = count($words);

}
$shortest = min($lengths);

$longest = max($lengths);

$average = array_sum($lengths) / $total;
$spread = $longest - $shortest;

$variety = min(
    100,
    (int) round(
        $spread * 5
    )
);
        return new SentenceReport(

    total: $total,

    shortest: $shortest,

longest: $longest,

average: $average,
    variety: $variety,
);

    }
}