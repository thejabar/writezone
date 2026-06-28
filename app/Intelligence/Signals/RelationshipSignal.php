<?php

declare(strict_types=1);

namespace App\Intelligence\Signals;

use App\Intelligence\Contracts\Signal;

final class RelationshipSignal implements Signal
{
    public function __construct(
        private readonly float $value,
        private readonly string $reason
    ) {
    }

    public function name(): string
    {
        return 'relationship';
    }

    public function value(): float
    {
        return $this->value;
    }

    public function reason(): string
    {
        return $this->reason;
    }

    public function source(): string
    {
        return self::class;
    }

    public function metadata(): array
    {
        return [];
    }
}