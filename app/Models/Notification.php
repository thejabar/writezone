<?php
declare(strict_types=1);
namespace App\Models;
use Core\Database\Model;
use Core\Database\Connection;
use PDO;
class Notification extends Model
{
    protected static string $table = 'notifications';
    public static function unreadCount(
        int $userId
    ): int {
        $db = Connection::getInstance();
        $stmt = $db->prepare(
            'SELECT COUNT(*)
             FROM notifications
             WHERE user_id = ?
             AND is_read = 0'
        );
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }
    public static function forUser(
    int $userId
): array {
    $db = Connection::getInstance();
    $stmt = $db->prepare(
        'SELECT
            notifications.*,
            users.handle,
            users.username
         FROM notifications
         LEFT JOIN users
            ON users.id = notifications.actor_id
         WHERE notifications.user_id = ?
         ORDER BY notifications.created_at DESC'
    );
    $stmt->execute([$userId]);
    return $stmt->fetchAll(
        PDO::FETCH_OBJ
    );
}
    public static function markAllRead(
        int $userId
    ): void {
        $db = Connection::getInstance();
        $stmt = $db->prepare(
            'UPDATE notifications
             SET is_read = 1
             WHERE user_id = ?'
        );
        $stmt->execute([$userId]);
    }
}