<?php
declare(strict_types=1);
function flash(
    string $key,
    ?string $message = null
): ?string {
    if ($message !== null) {
        $_SESSION['_flash'][$key] = $message;
        return null;
    }
    $value = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);
    return $value;
}