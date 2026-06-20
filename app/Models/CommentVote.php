<?php
declare(strict_types=1);
namespace App\Models;
use Core\Database\Connection;
use PDO;
class CommentVote
{
    public static function toggle(
        int $commentId,
        int $userId,
        int $vote
    ): void {
        $pdo = Connection::getInstance();
        $stmt = $pdo->prepare("
            SELECT id, vote
            FROM comment_votes
            WHERE comment_id = :comment_id
              AND user_id = :user_id
            LIMIT 1
        ");
        $stmt->execute([
            'comment_id' => $commentId,
            'user_id' => $userId,
        ]);
        $existing = $stmt->fetch(PDO::FETCH_OBJ);
        if ($existing) {
            if ((int) $existing->vote === $vote) {
                $delete = $pdo->prepare("
                    DELETE FROM comment_votes
                    WHERE id = :id
                ");
                $delete->execute([
                    'id' => $existing->id,
                ]);
                return;
            }
            $update = $pdo->prepare("
                UPDATE comment_votes
                SET vote = :vote
                WHERE id = :id
            ");
            $update->execute([
                'vote' => $vote,
                'id' => $existing->id,
            ]);
            return;
        }
        $insert = $pdo->prepare("
            INSERT INTO comment_votes (
                comment_id,
                user_id,
                vote
            )
            VALUES (
                :comment_id,
                :user_id,
                :vote
            )
        ");
        $insert->execute([
            'comment_id' => $commentId,
            'user_id' => $userId,
            'vote' => $vote,
        ]);
    }
    public static function score(
        int $commentId
    ): int {
        $pdo = Connection::getInstance();
        $stmt = $pdo->prepare("
            SELECT COALESCE(
                SUM(vote),
                0
            ) AS score
            FROM comment_votes
            WHERE comment_id = :comment_id
        ");
        $stmt->execute([
            'comment_id' => $commentId,
        ]);
        return (int) $stmt->fetchColumn();
    }
    public static function userVote(
        int $commentId,
        int $userId
    ): int {
        $pdo = Connection::getInstance();
        $stmt = $pdo->prepare("
            SELECT vote
            FROM comment_votes
            WHERE comment_id = :comment_id
              AND user_id = :user_id
            LIMIT 1
        ");
        $stmt->execute([
            'comment_id' => $commentId,
            'user_id' => $userId,
        ]);
        return (int) (
            $stmt->fetchColumn() ?: 0
        );
    }
}