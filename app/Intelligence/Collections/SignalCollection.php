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

    /**
     * Store a signal.
     */
    public function add(
        Signal $signal
    ): void {

        $this->signals[
            $signal->name()
        ] = $signal;

    }

    /**
     * Retrieve one signal.
     */
    public function get(
        string $name
    ): ?Signal {

        return $this->signals[$name]
            ?? null;

    }

    /**
     * Determine whether a signal exists.
     */
    public function has(
        string $name
    ): bool {

        return isset(
            $this->signals[$name]
        );

    }

    /**
     * Number of signals.
     */
    public function count(): int
    {

        return count(
            $this->signals
        );

    }

    /**
     * Collection empty?
     */
    public function isEmpty(): bool
    {

        return empty(
            $this->signals
        );

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

    /**
     * Sum all signal values.
     */
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