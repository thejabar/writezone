<?php

declare(strict_types=1);

namespace App\Models;

use Core\Database\Connection;
use Core\Database\Model;
use PDO;

class Writ extends Model
{
    protected static string $table = 'writs';

    private static function authorSelect(): string
    {
        return "
            SELECT
                w.*,
                u.handle,
                u.username,
                u.display_name
            FROM writs w
            INNER JOIN users u
                ON u.id = w.user_id
        ";
    }

    public static function feed(): array
    {
        $pdo = Connection::getInstance();

        $stmt = $pdo->query(
            self::authorSelect() . "
            ORDER BY w.created_at DESC
        "
        );

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function byUser(
        int $userId
    ): array {

        $pdo = Connection::getInstance();

        $stmt = $pdo->prepare(
            self::authorSelect() . "
            WHERE w.user_id = :user_id
            ORDER BY w.created_at DESC
        "
        );

        $stmt->execute([
            'user_id' => $userId,
        ]);

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function generatePublicId(): string
    {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        do {

            $code = '';

            for ($i = 0; $i < 7; $i++) {

                $code .= $characters[
                    random_int(
                        0,
                        strlen($characters) - 1
                    )
                ];

            }

        } while (
            static::where(
                'public_id',
                $code
            )
        );

        return $code;
    }

    public static function findWithAuthor(
        int $id
    ): ?object {

        $pdo = Connection::getInstance();

        $stmt = $pdo->prepare(
            self::authorSelect() . "
            WHERE w.id = :id
            LIMIT 1
        "
        );

        $stmt->execute([
            'id' => $id,
        ]);

        return $stmt->fetchObject() ?: null;
    }

    public static function findByPublicId(
        string $publicId
    ): ?object {

        $pdo = Connection::getInstance();

        $stmt = $pdo->prepare(
            self::authorSelect() . "
            WHERE w.public_id = :public_id
            LIMIT 1
        "
        );

        $stmt->execute([
            'public_id' => $publicId,
        ]);

        return $stmt->fetchObject() ?: null;
    }

    public static function belongsToUser(
        int $writId,
        int $userId
    ): bool {

        $writ = static::find($writId);

        return $writ !== null
            && (int) $writ->user_id === $userId;
    }

    public static function belongsToUserByPublicId(
        string $publicId,
        int $userId
    ): bool {

        $writ = static::findByPublicId($publicId);

        return $writ !== null
            && (int) $writ->user_id === $userId;
    }

    public static function search(
        string $query
    ): array {

        $pdo = Connection::getInstance();

        $stmt = $pdo->prepare(
            self::authorSelect() . "
            WHERE w.content LIKE :query
            ORDER BY w.created_at DESC
            LIMIT 20
        "
        );

        $stmt->execute([
            'query' => '%' . $query . '%',
        ]);

        return $stmt->fetchAll(
            PDO::FETCH_OBJ
        );
    }
}