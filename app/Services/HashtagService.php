<?php

declare(strict_types=1);

namespace App\Services;

class HashtagService
{
    public static function render(
    string $content
): string {

    $content = preg_replace(
        '/#([A-Za-z0-9_]+)/',
        '<a href="/hashtags/$1">#$1</a>',
        $content
    );

    return $content;
}

    public static function extractTags(
        string $content
    ): array {

        preg_match_all(
            '/#([A-Za-z0-9_]+)/',
            $content,
            $matches
        );

        return array_unique(
            $matches[1] ?? []
        );
    }
}
