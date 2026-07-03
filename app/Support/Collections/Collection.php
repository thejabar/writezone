<?php

declare(strict_types=1);

namespace App\Support\Collections;

interface Collection
{
    /**
     * Return all items.
     */
    public function all(): array;

    /**
     * Number of items.
     */
    public function count(): int;

    /**
     * Is the collection empty?
     */
    public function isEmpty(): bool;

    /**
     * Return the first item.
     */
    public function first(): mixed;

    /**
     * Return the last item.
     */
    public function last(): mixed;
}
