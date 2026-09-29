<?php
declare(strict_types=1);

namespace App\Policies;

use App\Models\Writ;

final class WritPolicy
{
    public function update(int|string $userId, Writ $writ): bool
    {
        return (int) $writ->user_id === (int) $userId;
    }

    public function delete(int|string $userId, Writ $writ): bool
    {
        return (int) $writ->user_id === (int) $userId;
    }
}
