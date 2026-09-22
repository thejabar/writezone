<?php
declare(strict_types=1);

namespace Database\Migrations;

use PDO;

interface MigrationInterface
{
    public function up(PDO $pdo): void;
}
