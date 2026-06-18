<?php
declare(strict_types=1);
namespace App\Models;
use Core\Database\Connection;
use Core\Database\Model;
use PDO;
class Writ extends Model
{
    protected static string $table = 'writs';
    public static function feed(): array
    {
        $pdo = Connection::getInstance();
        $stmt = $pdo->query("
            SELECT
                w.*,
                u.handle,
                u.username,
                u.display_name
            FROM writs w
            INNER JOIN users u
                ON u.id = w.user_id
            ORDER BY w.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public static function findWithAuthor(int $id): ?object
    {
        $pdo = Connection::getInstance();
        $stmt = $pdo->prepare("
            SELECT
                w.*,
                u.handle,
                u.username,
                u.display_name
            FROM writs w
            INNER JOIN users u
                ON u.id = w.user_id
            WHERE w.id = :id
            LIMIT 1
        ");
        $stmt->execute([
            'id' => $id,
        ]);
        return $stmt->fetchObject() ?: null;
    }
    public static function belongsToUser(
    int $writId,
    int $userId
): bool {
    $writ = static::find($writId);
    return $writ !== null
        && (int) $writ->user_id === (int) $userId;
}
}