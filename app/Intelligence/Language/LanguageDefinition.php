<?php

declare(strict_types=1);

namespace App\Intelligence\Language;

final class LanguageDefinition
{
    public function __construct(
        private readonly string $code,
        private readonly string $name,
        private readonly string $nativeName,
        private readonly string $script,
        private readonly string $status
    ) {
    }

    public function code(): string
    {
        return $this->code;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function nativeName(): string
    {
        return $this->nativeName;
    }

    public function script(): string
    {
        return $this->script;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isPlaceholder(): bool
    {
        return $this->status === 'placeholder';
    }
}
