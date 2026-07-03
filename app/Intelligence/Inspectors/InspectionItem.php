<?php

declare(strict_types=1);

namespace App\Intelligence\Inspectors;

final class InspectionItem
{
    public function __construct(
        private readonly string $label,
        private readonly mixed $value
    ) {
    }

    public function label(): string
    {
        return $this->label;
    }

    public function value(): mixed
    {
        return $this->value;
    }
}
