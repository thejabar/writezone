<?php

declare(strict_types=1);

namespace App\Intelligence\Support;

final class SentenceParser
{
    /**
     * Parse writing into sentences.
     *
     * @return string[]
     */
    public static function parse(
        string $content
    ): array {

        $content = trim($content);

        if ($content === '') {

            return [];

        }

        /*
        |--------------------------------------------------------------------------
        | Normalize whitespace
        |--------------------------------------------------------------------------
        */

        $content = preg_replace(
            '/\s+/u',
            ' ',
            $content
        );

        /*
        |--------------------------------------------------------------------------
        | Extract sentences
        |--------------------------------------------------------------------------
        */

        preg_match_all(
            '/[^.!?]+(?:[.!?]+|$)/u',
            $content,
            $matches
        );

        return array_values(
            array_filter(
                array_map(
                    'trim',
                    $matches[0]
                )
            )
        );

    }
}