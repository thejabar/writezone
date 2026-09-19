<?php

declare(strict_types=1);

namespace App\Intelligence\Lab;

use App\Intelligence\Language\LanguageRegistry;

final class LabRequest
{
    public function __construct(
        private readonly string $content,
        private readonly string $language = 'en'
    ) {
    }

    public function content(): string
    {
        return $this->content;
    }

    public function language(): string
    {
        return $this->language;
    }

    public function resolvedLanguage(): \App\Intelligence\Language\LanguageDefinition
    {
        return LanguageRegistry::resolve(
            $this->language
        );
    }
}
