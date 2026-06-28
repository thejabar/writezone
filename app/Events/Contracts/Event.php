<?php

declare(strict_types=1);

namespace App\Events\Contracts;

interface Event
{
    /**
     * The event name.
     */
    public function name(): string;

    /**
     * Event payload.
     */
    public function payload(): array;

    /**
     * When the event occurred.
     */
    public function occurredAt(): \DateTimeImmutable;
}