<?php
declare(strict_types=1);
namespace App\Models;
use Core\Database\Model;
use Core\Database\Connection;
use PDO;
class Bookmark extends Model
{
    protected static string $table = 'bookmarks';
    public static function isBookmarked(
        int $userId,
        int $writId
    ): bool {
        $db = Connection::getInstance();
        $stmt = $db->prepare(
            'SELECT id
             FROM bookmarks
             WHERE user_id = ?
             AND writ_id = ?
             LIMIT 1'
        );
        $stmt->execute([
            $userId,
            $writId,
        ]);
        return (bool) $stmt->fetch();
    }
    public static function forUser(
        int $userId
    ): array {
        $db = Connection::getInstance();
        $stmt = $db->prepare(
            'SELECT
                w.*,
                u.handle,
                u.username,
                u.display_name
             FROM bookmarks b
             INNER JOIN writs w
                ON w.id = b.writ_id
             INNER JOIN users u
                ON u.id = w.user_id
             WHERE b.user_id = ?
             ORDER BY b.created_at DESC'
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll(
            PDO::FETCH_OBJ
        );
    }
}