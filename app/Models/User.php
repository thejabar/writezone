<?php
declare(strict_types=1);
namespace App\Models;
use Core\Database\Connection;
use Core\Database\Model;
use PDO;
class User extends Model
{
    protected static string $table = 'users';
    public static function search(
        string $query
    ): array {
        $pdo = Connection::getInstance();
        $stmt = $pdo->prepare("
            SELECT *
            FROM users
            WHERE
                username LIKE :query
                OR handle LIKE :query
                OR display_name LIKE :query
            ORDER BY username ASC
            LIMIT 20
        ");
        $stmt->execute([
            'query' => '%' . $query . '%',
        ]);
        return $stmt->fetchAll(
            PDO::FETCH_OBJ
        );
    }
}