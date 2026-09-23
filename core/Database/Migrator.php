<?php

declare(strict_types=1);

namespace Core\Database;

use Database\Migrations\MigrationInterface;
use PDO;
use RuntimeException;

final class Migrator
{
    private const MIGRATIONS_PATH = '/database/migrations';
    private const BASELINE = '2026_09_22_000000_writezone_baseline.php';
    private const BASELINE_NAME = '2026_09_22_000000_writezone_baseline';

    public function __construct(private PDO $pdo)
    {
    }

    public function status(): void
    {
        $tables = $this->tables();
        $hasMigrations = in_array('migrations', $tables, true);

        echo "WriteZone Migration Status\n";
        echo "==========================\n";
        echo "Tables detected: " . count($tables) . "\n";
        echo "Migration table: " . ($hasMigrations ? 'YES' : 'NO') . "\n";

        if (!$hasMigrations) {
            echo "State: FRESH DATABASE\n";
            echo "Pending: " . self::BASELINE_NAME . "\n";
            return;
        }

        $applied = $this->applied();
        $this->validateAppliedMigrations($applied);

        echo "Applied migrations: " . count($applied) . "\n";

        if ($applied !== []) {
            foreach ($applied as $migration) {
                echo "  [APPLIED] $migration\n";
            }
        }

        $files = $this->migrationFiles();

        $pending = [];

        foreach ($files as $file) {
            $name = pathinfo($file, PATHINFO_FILENAME);

            if (!in_array($name, $applied, true)) {
                $pending[] = $name;
            }
        }

        if ($pending !== []) {
            foreach ($pending as $migration) {
                echo "  [PENDING] $migration\n";
            }
        }

        if (count($tables) > 1 && $applied === []) {
            echo "State: EXISTING UNBASELINED DATABASE\n";
            $this->verifyLiveSchema();
            echo "Action: migrate is BLOCKED; baseline adoption is available.\n";
            return;
        }

        if ($pending === []) {
            echo "State: UP TO DATE\n";
        }
    }

    public function migrate(): void
    {
        $tables = $this->tables();
        $hasMigrations = in_array('migrations', $tables, true);

        if (!$hasMigrations) {
            if ($tables !== []) {
                throw new RuntimeException(
                    'Refusing migration: database contains tables but has no migration history. Run "php migrate.php baseline" after schema verification.'
                );
            }

            $this->runFreshBaseline();
            return;
        }

        $applied = $this->applied();
        $this->validateAppliedMigrations($applied);

        if ($applied === [] && count($tables) > 1) {
            throw new RuntimeException(
                'Refusing migration: existing database is not baselined. Run "php migrate.php baseline" after schema verification.'
            );
        }

        $this->ensureMigrationTable();

        foreach ($this->migrationFiles() as $file) {
            $name = pathinfo($file, PATHINFO_FILENAME);

            if (in_array($name, $applied, true)) {
                continue;
            }

            $migration = $this->loadMigration($file);

            $migration->up($this->pdo);

            $stmt = $this->pdo->prepare(
                'INSERT INTO migrations (migration) VALUES (:migration)'
            );

            $stmt->execute(['migration' => $name]);

            echo "Applied: $name\n";
        }

        echo "Migration run completed.\n";
    }

    public function baseline(): void
    {
        $tables = $this->tables();

        if (!in_array('migrations', $tables, true)) {
            throw new RuntimeException(
                'Baseline adoption requires the existing migrations table.'
            );
        }

        $applied = $this->applied();

        if ($applied !== []) {
            throw new RuntimeException(
                'Baseline adoption is blocked because migration history already contains records.'
            );
        }

        $this->verifyLiveSchema();

        $stmt = $this->pdo->prepare(
            'INSERT INTO migrations (migration) VALUES (:migration)'
        );

        $stmt->execute(['migration' => self::BASELINE_NAME]);

        echo "Baseline adoption recorded: " . self::BASELINE_NAME . "\n";
        echo "No application tables or application data were modified.\n";
    }

    private function runFreshBaseline(): void
    {
        $file = BASE_PATH . self::MIGRATIONS_PATH . '/' . self::BASELINE;

        if (!is_file($file)) {
            throw new RuntimeException('Canonical baseline migration not found.');
        }

        $migration = $this->loadMigration($file);

        $migration->up($this->pdo);

        $stmt = $this->pdo->prepare(
            'INSERT INTO migrations (migration) VALUES (:migration)'
        );

        $stmt->execute(['migration' => self::BASELINE_NAME]);

        echo "Fresh database baseline applied.\n";
    }

    private function validateAppliedMigrations(array $applied): void
    {
        $known = array_map(
            static fn(string $file): string => pathinfo($file, PATHINFO_FILENAME),
            $this->migrationFiles()
        );

        foreach ($applied as $migration) {
            if (!in_array($migration, $known, true)) {
                throw new RuntimeException(
                    "Migration history contains an unknown migration: $migration"
                );
            }
        }
    }

    private function ensureMigrationTable(): void
    {
        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL UNIQUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB
              DEFAULT CHARACTER SET utf8mb4
              COLLATE utf8mb4_unicode_ci'
        );
    }

    private function applied(): array
    {
        if (!in_array('migrations', $this->tables(), true)) {
            return [];
        }

        $rows = $this->pdo
            ->query('SELECT migration FROM migrations ORDER BY id')
            ->fetchAll(PDO::FETCH_COLUMN);

        return array_map('strval', $rows);
    }

    private function tables(): array
    {
        $rows = $this->pdo
            ->query('SHOW TABLES')
            ->fetchAll(PDO::FETCH_COLUMN);

        return array_map('strval', $rows);
    }

    private function migrationFiles(): array
    {
        $files = glob(
            BASE_PATH . self::MIGRATIONS_PATH . '/*.php'
        ) ?: [];

        sort($files, SORT_STRING);

        return array_values(
            array_filter(
                $files,
                static fn(string $file): bool =>
                    basename($file) !== 'MigrationInterface.php'
            )
        );
    }

    private function loadMigration(string $file): MigrationInterface
    {
        require_once BASE_PATH . self::MIGRATIONS_PATH . '/MigrationInterface.php';

        $classesBefore = get_declared_classes();

        require_once $file;

        $candidate = null;

        foreach (get_declared_classes() as $declared) {
            if (
                !in_array($declared, $classesBefore, true) &&
                is_subclass_of($declared, MigrationInterface::class)
            ) {
                $candidate = $declared;
                break;
            }
        }

        if ($candidate === null) {
            throw new RuntimeException(
                'Migration class could not be resolved: ' . basename($file)
            );
        }

        return new $candidate();
    }

    private function verifyLiveSchema(): void
    {
        $reference = BASE_PATH . "/database/schema/live-schema.sql";
        if (!is_file($reference)) { throw new RuntimeException("Verified live schema reference is missing."); }
        $referenceSql = file_get_contents($reference);
        if ($referenceSql === false) { throw new RuntimeException("Unable to read live schema reference."); }
        $referenceTables = [];
        foreach (explode("\n", $referenceSql) as $line) {
            $line = trim($line);
            if (str_starts_with($line, "CREATE TABLE `")) {
                $name = substr($line, 14);
                $end = strpos($name, "`");
                if ($end !== false) { $referenceTables[] = substr($name, 0, $end); }
            }
        }
        $actualTables = $this->tables();
        sort($referenceTables, SORT_STRING);
        sort($actualTables, SORT_STRING);
        if ($referenceTables !== $actualTables) { throw new RuntimeException("Schema verification failed: table sets differ."); }
        foreach ($actualTables as $table) {
            $marker = "CREATE TABLE `" . $table . "`";
            $start = strpos($referenceSql, $marker);
            if ($start === false) { throw new RuntimeException("Schema verification failed: missing reference for table: $table"); }
            $end = strpos($referenceSql, ";", $start);
            if ($end === false) { throw new RuntimeException("Schema verification failed: incomplete reference for table: $table"); }
            $expectedSql = substr($referenceSql, $start, $end - $start);
            $stmt = $this->pdo->prepare("SHOW CREATE TABLE `$table`");
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) { throw new RuntimeException("Unable to inspect table: $table"); }
            $actualSql = (string) ($row["Create Table"] ?? "");
            if ($this->normaliseSchema($expectedSql) !== $this->normaliseSchema($actualSql)) { throw new RuntimeException("Schema verification failed for table: $table"); }
        }
        echo "Live schema verification: PASS\n";
    }

    private function normaliseSchema(string $sql): string
    {
        $sql = preg_replace(
            '/AUTO_INCREMENT=\d+/i',
            '',
            $sql
        ) ?? $sql;

        $sql = preg_replace(
            '/\s+/',
            ' ',
            trim($sql)
        ) ?? $sql;

        return strtolower($sql);
    }
}
