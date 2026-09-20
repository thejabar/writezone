<?php

declare(strict_types=1);

namespace App\Intelligence\Support;

use App\Intelligence\Language\LanguageDefinition;
use App\Intelligence\Language\LanguageRegistry;

final class SentenceParser
{
    /**
     * Parse writing into sentences.
     *
     * @return string[]
     */
    public static function parse(
        string $content,
        ?LanguageDefinition $language = null
    ): array {

        $content = trim($content);

        if ($content === '') {
            return [];
        }

        $language = $language ?? LanguageRegistry::default();

        $content = preg_replace(
            '/\s+/u',
            ' ',
            $content
        );

        $terminators = '.!?';

        if ($language->script() === 'Arabic') {
            $terminators .= '؟';
        }

        if (in_array(
            $language->script(),
            ['Han', 'Japanese', 'Hangul'],
            true
        )) {
            $terminators .= '。！？';
        }

        $escaped = preg_quote($terminators, '/');

        preg_match_all(
            '/[^' . $escaped . ']+(?:[' . $escaped . ']+|$)/u',
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
