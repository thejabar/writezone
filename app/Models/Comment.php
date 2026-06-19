<?php
declare(strict_types=1);
namespace App\Models;
use Core\Database\Connection;
use Core\Database\Model;
use PDO;
class Comment extends Model
{
    protected static string $table = 'comments';
    public static function forWrit(
        int $writId,
        ?int $parentId = null
    ): array {
        $pdo = Connection::getInstance();
        $sql = '
            SELECT
                c.*,
                u.handle,
                u.display_name,
                u.username
            FROM comments c
            INNER JOIN users u
                ON u.id = c.user_id
            WHERE c.writ_id = :writ_id
              AND c.parent_id '
              . ($parentId === null
                    ? 'IS NULL'
                    : '= :parent_id')
              . '
            ORDER BY c.created_at ASC
        ';
        $stmt = $pdo->prepare($sql);
        $params = [
            'writ_id' => $writId,
        ];
        if ($parentId !== null) {
            $params['parent_id'] = $parentId;
        }
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public static function replies(
        int $commentId
    ): array {
        $pdo = Connection::getInstance();
        $stmt = $pdo->prepare('
            SELECT
                c.*,
                u.handle,
                u.display_name,
                u.username
            FROM comments c
            INNER JOIN users u
                ON u.id = c.user_id
            WHERE c.parent_id = :parent_id
            ORDER BY c.created_at ASC
        ');
        $stmt->execute([
            'parent_id' => $commentId,
        ]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public static function belongsToUser(
        int $commentId,
        int $userId
    ): bool {
        $comment = static::find($commentId);
        return $comment !== null
            && (int) $comment->user_id === $userId;
    }
}