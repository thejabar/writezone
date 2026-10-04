<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/vendor/autoload.php';

use App\Events\EventDispatcher;
use App\Events\Events\WritPublished;
use App\Models\User;
use App\Models\Writ;
use App\Services\WritPublisher;

function assert_result(string $name, bool $pass): void
{
    echo ($pass ? '[PASS] ' : '[FAIL] ') . $name . PHP_EOL;

    if (! $pass) {
        $GLOBALS['failures']++;
    }

    $GLOBALS['total']++;
}

$GLOBALS['failures'] = 0;
$GLOBALS['total'] = 0;

/*
 * Event primitive coverage.
 */
$dispatcher = new EventDispatcher();
$received = [];

$dispatcher->listen(
    function ($event) use (&$received): void {
        $received[] = $event;
    }
);

$dispatcher->dispatch(
    new WritPublished(123, 42)
);

assert_result(
    'WritPublished event is dispatched to listeners',
    count($received) === 1
);

assert_result(
    'WritPublished event name is correct',
    count($received) === 1
        && $received[0]->name() === 'writ.published'
);

assert_result(
    'WritPublished event payload is correct',
    count($received) === 1
        && $received[0]->payload() === [
            'writ_id'   => 123,
            'author_id' => 42,
        ]
);

assert_result(
    'WritPublished event timestamp is immutable',
    count($received) === 1
        && $received[0]->occurredAt() instanceof DateTimeImmutable
);

/*
 * Real WritPublisher integration coverage.
 *
 * This test creates one uniquely identified temporary user and Writ.
 * Cleanup deletes only that temporary user. The FK cascade removes the
 * temporary Writ. No existing application records are modified.
 */
$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

$pdo = new PDO(
    'mysql:host=localhost;port=3306;dbname=' . $_ENV['DB_DATABASE'] . ';charset=utf8mb4',
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD'],
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
);

$marker = 'wz_it_' . bin2hex(random_bytes(6));
$username = $marker;
$email = $marker . '@example.invalid';

$userId = null;
$writId = null;

try {
    $userId = User::create([
        'username'     => $username,
        'handle'       => $marker,
        'email'        => $email,
        'password'     => password_hash(
            'integration-test-only',
            PASSWORD_DEFAULT
        ),
        'display_name' => 'Integration Test User',
    ]);

    assert_result(
        'WritPublisher integration user created',
        $userId > 0
    );

    $events = new EventDispatcher();
    $captured = null;

    $events->listen(
        function (WritPublished $event) use (&$captured): void {
            $captured = $event;
        }
    );

    $publisher = new WritPublisher($events);

    $result = $publisher->publish(
        $userId,
        'WriteZone WritPublisher integration test ' . $marker
    );

    $writId = (int) ($result['id'] ?? 0);

    assert_result(
        'WritPublisher returns a valid Writ ID',
        $writId > 0
    );

    assert_result(
        'WritPublisher returns a public ID',
        isset($result['public_id'])
            && is_string($result['public_id'])
            && $result['public_id'] !== ''
    );

    $writ = $writId > 0 ? Writ::find($writId) : null;

    assert_result(
        'Published Writ persists',
        $writ !== null
    );

    assert_result(
        'Published Writ has correct author',
        $writ !== null
            && (int) $writ->user_id === $userId
    );

    assert_result(
        'WritPublished event is emitted by WritPublisher',
        $captured instanceof WritPublished
    );

    $payload = $captured instanceof WritPublished
        ? $captured->payload()
        : [];

    assert_result(
        'WritPublisher event payload matches persisted Writ',
        $captured instanceof WritPublished
            && (int) ($payload['writ_id'] ?? 0) === $writId
            && (int) ($payload['author_id'] ?? 0) === $userId
    );

    assert_result(
        'WritPublisher event name is correct',
        $captured instanceof WritPublished
            && $captured->name() === 'writ.published'
    );

} catch (Throwable $e) {
    echo '[FAIL] WritPublisher integration exception: '
        . $e->getMessage()
        . PHP_EOL;

    $GLOBALS['failures']++;
    $GLOBALS['total']++;
} finally {
    if ($userId !== null) {
        try {
            $stmt = $pdo->prepare(
                'DELETE FROM users WHERE id = :id'
            );

            $stmt->execute([
                'id' => $userId,
            ]);

            $remainingWrit = $writId !== null
                ? $pdo->prepare(
                    'SELECT COUNT(*) FROM writs WHERE id = :id'
                )
                : null;

            if ($remainingWrit !== null) {
                $remainingWrit->execute([
                    'id' => $writId,
                ]);

                assert_result(
                    'Temporary Writ is removed by FK cascade',
                    (int) $remainingWrit->fetchColumn() === 0
                );
            }

        } catch (Throwable $cleanupError) {
            echo '[FAIL] Test cleanup failed: '
                . $cleanupError->getMessage()
                . PHP_EOL;

            $GLOBALS['failures']++;
            $GLOBALS['total']++;
        }
    }
}

echo PHP_EOL;
echo 'Writ Publication Event Regression: '
    . $GLOBALS['total']
    . ' assertions, '
    . $GLOBALS['failures']
    . ' failures'
    . PHP_EOL;

exit($GLOBALS['failures'] === 0 ? 0 : 1);
