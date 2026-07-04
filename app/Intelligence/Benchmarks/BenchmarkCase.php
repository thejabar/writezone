<?php

declare(strict_types=1);

namespace App\Intelligence\Benchmarks;

final class BenchmarkCase
{
    public function __construct(
        private readonly string $name,
        private readonly string $content,
        private readonly array $expectations = []
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function expectations(): array
    {
        return $this->expectations;
    }
}
