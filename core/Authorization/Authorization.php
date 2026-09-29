<?php
declare(strict_types=1);

namespace Core\Authorization;

final class Authorization
{
    /**
     * Determine whether a policy permits an action.
     *
     * @param object $subject
     */
    public static function allows(
        string $policyClass,
        string $ability,
        int|string $userId,
        object $subject
    ): bool {
        $policy = new $policyClass();

        if (! method_exists($policy, $ability)) {
            return false;
        }

        return (bool) $policy->{$ability}($userId, $subject);
    }
}
