<?php

declare(strict_types=1);

namespace App\Intelligence\Lab;

final class LabRequest
{
    public function __construct(
        private readonly string $content
    ) {
    }

    public function content(): string
    {
        return $this->content;
    }
}
