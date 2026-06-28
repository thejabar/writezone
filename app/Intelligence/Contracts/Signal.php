<?php

declare(strict_types=1);

namespace App\Intelligence\Contracts;

interface Signal
{
    public function name(): string;

    public function value(): float;

    public function reason(): string;

    public function source(): string;

    public function metadata(): array;
}