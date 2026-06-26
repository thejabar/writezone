<?php
declare(strict_types=1);
namespace App\Models;
use Core\Database\Connection;
use PDO;
class Hashtag
{
    public static function findWrits(
        string $tag
    ): array {
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
            WHERE
                w.content LIKE :tag
            ORDER BY
                w.created_at DESC
        ");
        $stmt->execute([
            'tag' => '%#' . $tag . '%',
        ]);
        return $stmt->fetchAll(
            PDO::FETCH_OBJ
        );
    }
    public static function search(
        string $query
    ): array {
        $pdo = Connection::getInstance();
        $stmt = $pdo->prepare("
            SELECT content
            FROM writs
            WHERE content LIKE :query
        ");
        $stmt->execute([
            'query' => '%' . $query . '%',
        ]);
        $hashtags = [];
        foreach (
            $stmt->fetchAll(PDO::FETCH_OBJ)
            as $row
        ) {
            preg_match_all(
                '/#([A-Za-z0-9_]+)/',
                $row->content,
                $matches
            );
            foreach (
                $matches[1] ?? []
                as $tag
            ) {
                if (
                    stripos(
                        $tag,
                        $query
                    ) !== false
                ) {
                    $hashtags[] = $tag;
                }
            }
        }
        return array_values(
            array_unique(
                $hashtags
            )
        );
    }
}