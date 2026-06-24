<?php

declare(strict_types=1);

namespace App\Services;

class MentionService
{
    public static function render(
        string $content
    ): string {

        $content = htmlspecialchars(
            $content,
            ENT_QUOTES,
            'UTF-8'
        );

        $content = preg_replace(
            '/@([A-Za-z0-9_]+)/',
            '<a href="/@$1">@$1</a>',
            $content
        );

        return nl2br($content);
    }
}
