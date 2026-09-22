<?php
declare(strict_types=1);

namespace Database\Migrations;

use PDO;

final class WriteZoneBaseline implements MigrationInterface
{
    public function up(PDO $pdo): void
    {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL UNIQUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET utf8mb4
              COLLATE utf8mb4_unicode_ci
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                handle VARCHAR(50) NOT NULL UNIQUE,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                display_name VARCHAR(100) NULL,
                bio TEXT NULL,
                avatar VARCHAR(255) NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                    ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET utf8mb4
              COLLATE utf8mb4_unicode_ci
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS writs (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                public_id CHAR(7) NOT NULL,
                UNIQUE KEY idx_writs_public_id (public_id),
                user_id BIGINT UNSIGNED NOT NULL,
                content TEXT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                    ON UPDATE CURRENT_TIMESTAMP,
                INDEX fk_writs_user (user_id),
                CONSTRAINT fk_writs_user
                    FOREIGN KEY (user_id) REFERENCES users(id)
                    ON DELETE CASCADE
            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET utf8mb4
              COLLATE utf8mb4_unicode_ci
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS comments (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                writ_id BIGINT UNSIGNED NOT NULL,
                user_id BIGINT UNSIGNED NOT NULL,
                parent_id BIGINT UNSIGNED NULL,
                content TEXT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                    ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_comments_writ (writ_id),
                INDEX idx_comments_parent (parent_id),
                INDEX idx_comments_user (user_id),
                CONSTRAINT fk_comments_writ
                    FOREIGN KEY (writ_id) REFERENCES writs(id)
                    ON DELETE CASCADE,
                CONSTRAINT fk_comments_user
                    FOREIGN KEY (user_id) REFERENCES users(id)
                    ON DELETE CASCADE,
                CONSTRAINT fk_comments_parent
                    FOREIGN KEY (parent_id) REFERENCES comments(id)
                    ON DELETE CASCADE
            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET utf8mb4
              COLLATE utf8mb4_unicode_ci
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS comment_votes (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                comment_id BIGINT UNSIGNED NOT NULL,
                user_id BIGINT UNSIGNED NOT NULL,
                vote TINYINT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY uniq_comment_user (comment_id, user_id),
                INDEX idx_comment_votes_comment (comment_id),
                INDEX fk_comment_votes_user (user_id),
                CONSTRAINT fk_comment_votes_comment
                    FOREIGN KEY (comment_id) REFERENCES comments(id)
                    ON DELETE CASCADE,
                CONSTRAINT fk_comment_votes_user
                    FOREIGN KEY (user_id) REFERENCES users(id)
                    ON DELETE CASCADE,
                CONSTRAINT chk_comment_vote
                    CHECK (vote IN (-1, 1))
            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET utf8mb4
              COLLATE utf8mb4_unicode_ci
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS bookmarks (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id BIGINT UNSIGNED NOT NULL,
                writ_id BIGINT UNSIGNED NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY uniq_bookmark (user_id, writ_id),
                INDEX idx_bookmarks_user (user_id),
                INDEX idx_bookmarks_writ (writ_id),
                CONSTRAINT fk_bookmarks_user
                    FOREIGN KEY (user_id) REFERENCES users(id)
                    ON DELETE CASCADE,
                CONSTRAINT fk_bookmarks_writ
                    FOREIGN KEY (writ_id) REFERENCES writs(id)
                    ON DELETE CASCADE
            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET utf8mb4
              COLLATE utf8mb4_unicode_ci
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS follows (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                follower_id BIGINT UNSIGNED NOT NULL,
                following_id BIGINT UNSIGNED NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY uniq_follow (follower_id, following_id),
                INDEX idx_follower (follower_id),
                INDEX idx_following (following_id),
                CONSTRAINT fk_follows_follower
                    FOREIGN KEY (follower_id) REFERENCES users(id)
                    ON DELETE CASCADE,
                CONSTRAINT fk_follows_following
                    FOREIGN KEY (following_id) REFERENCES users(id)
                    ON DELETE CASCADE
            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET utf8mb4
              COLLATE utf8mb4_unicode_ci
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS notifications (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id BIGINT UNSIGNED NOT NULL,
                actor_id BIGINT UNSIGNED NULL,
                type VARCHAR(50) NOT NULL,
                reference_id BIGINT UNSIGNED NULL,
                is_read TINYINT(1) NOT NULL DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_notifications_user (user_id, is_read, created_at),
                INDEX idx_notifications_actor (actor_id),
                CONSTRAINT fk_notifications_user
                    FOREIGN KEY (user_id) REFERENCES users(id)
                    ON DELETE CASCADE,
                CONSTRAINT fk_notifications_actor
                    FOREIGN KEY (actor_id) REFERENCES users(id)
                    ON DELETE CASCADE
            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET utf8mb4
              COLLATE utf8mb4_unicode_ci
        ");
    }
}
