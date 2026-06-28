<?php

declare(strict_types=1);

namespace App\Events;

use App\Events\Contracts\Event;

final class EventDispatcher
{
    /**
     * @var callable[]
     */
    private array $listeners = [];

    public function listen(
        callable $listener
    ): self {

        $this->listeners[] = $listener;

        return $this;
    }

    public function dispatch(
        Event $event
    ): void {

        foreach ($this->listeners as $listener) {
            $listener($event);
        }

    }
}