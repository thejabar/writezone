<?php

declare(strict_types=1);

namespace App\Models;

use Core\Database\Model;
use Core\Database\Connection;
use PDO;

class Follow extends Model
{
    protected static string $table = 'follows';

    public static function isFollowing(
        int $followerId,
        int $followingId
    ): bool {

        $db = Connection::getInstance();

        $stmt = $db->prepare(
            'SELECT id
             FROM follows
             WHERE follower_id = ?
             AND following_id = ?
             LIMIT 1'
        );

        $stmt->execute([
            $followerId,
            $followingId,
        ]);

        return (bool) $stmt->fetch();
    }

    public static function followersCount(
        int $userId
    ): int {

        $db = Connection::getInstance();

        $stmt = $db->prepare(
            'SELECT COUNT(*)
             FROM follows
             WHERE following_id = ?'
        );

        $stmt->execute([$userId]);

        return (int) $stmt->fetchColumn();
    }

    public static function followingCount(
        int $userId
    ): int {

        $db = Connection::getInstance();

        $stmt = $db->prepare(
            'SELECT COUNT(*)
             FROM follows
             WHERE follower_id = ?'
        );

        $stmt->execute([$userId]);

        return (int) $stmt->fetchColumn();
    }
}