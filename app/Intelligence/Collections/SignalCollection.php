<?php

declare(strict_types=1);

namespace App\Intelligence\Collections;

use App\Intelligence\Contracts\Signal;

final class SignalCollection
{
    /**
     * @var array<string, Signal>
     */
    private array $signals = [];

    public function add(
        Signal $signal
    ): void {

        $this->signals[
            $signal->name()
        ] = $signal;

    }

    /**
     * @return Signal[]
     */
    public function all(): array
    {
        return array_values(
            $this->signals
        );
    }

    public function get(
        string $name
    ): ?Signal {

        return $this->signals[
            $name
        ] ?? null;

    }

    public function has(
        string $name
    ): bool {

        return isset(
            $this->signals[$name]
        );

    }

    public function total(): float
    {
        return array_reduce(
            $this->signals,
            fn (
                float $total,
                Signal $signal
            ) => $total + $signal->value(),
            0.0
        );
    }
}