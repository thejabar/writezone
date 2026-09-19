<?php

declare(strict_types=1);

namespace App\Intelligence\Language\Resources;

final class EnglishLanguageResources implements LanguageResources
{
    public function fillerWords(): array
    {
        return [
            'actually',
            'basically',
            'literally',
            'really',
            'very',
            'just',
            'quite',
            'simply',
        ];
    }

    public function transitionWords(): array
    {
        return [
            'however',
            'therefore',
            'moreover',
            'furthermore',
            'consequently',
            'meanwhile',
            'finally',
            'additionally',
            'instead',
            'otherwise',
        ];
    }
}
