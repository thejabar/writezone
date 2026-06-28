<?php

declare(strict_types=1);

namespace App\Intelligence\Collections;

use App\Intelligence\Contracts\Signal;

final class SignalCollection
{
    /**
     * @var Signal[]
     */
    private array $signals = [];

    public function add(
        Signal $signal
    ): void {

        $this->signals[] = $signal;

    }

    /**
     * @return Signal[]
     */
    public function all(): array
    {
        return $this->signals;
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