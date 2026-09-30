<?php
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/vendor/autoload.php';

use Core\Authentication\Auth;
use Core\Session\Session;

$passed = 0;
$failed = 0;

function check_session(bool $condition, string $message): void
{
    global $passed, $failed;

    if ($condition) {
        $passed++;
        echo "PASS: {$message}\n";
        return;
    }

    $failed++;
    echo "FAIL: {$message}\n";
}

if (session_status() !== PHP_SESSION_NONE) {
    session_destroy();
}

Session::start();

$params = session_get_cookie_params();

check_session(
    ini_get('session.use_only_cookies') === '1',
    'Session accepts cookies only'
);

check_session(
    ini_get('session.use_strict_mode') === '1',
    'Session strict mode is enabled'
);

check_session(
    ini_get('session.use_trans_sid') === '0',
    'URL session identifiers are disabled'
);

check_session(
    (bool) ($params['secure'] ?? false),
    'Session cookie is Secure'
);

check_session(
    (bool) ($params['httponly'] ?? false),
    'Session cookie is HttpOnly'
);

check_session(
    ($params['samesite'] ?? '') === 'Lax',
    'Session cookie uses SameSite=Lax'
);

Auth::login(123);

check_session(
    Auth::check(),
    'Authenticated session is recognised'
);

check_session(
    (int) Auth::id() === 123,
    'Authenticated user ID is stored'
);

Auth::login(456);

check_session(
    Auth::check(),
    'Re-authenticated session remains authenticated'
);

check_session(
    (int) Auth::id() === 456,
    'Re-authentication replaces previous user ID'
);

check_session(
    session_status() === PHP_SESSION_ACTIVE,
    'Session remains active after authentication regeneration'
);

Auth::logout();

check_session(
    ! Auth::check(),
    'Logout destroys authentication state'
);

check_session(
    Auth::id() === null,
    'Logout removes stored user ID'
);

check_session(
    session_status() === PHP_SESSION_NONE,
    'Logout destroys the PHP session'
);

echo "Session Security Regression: {$passed}/" . ($passed + $failed) . " passed\n";

exit($failed === 0 ? 0 : 1);
