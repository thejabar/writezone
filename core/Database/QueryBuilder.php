<?php

declare(strict_types=1);

namespace Core\Database;

use PDO;

class QueryBuilder
{
    public function __construct(
        private PDO $pdo,
        private string $table
    ) {}

    public function find(int|string $id): ?object
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1"
        );

        $stmt->execute(['id' => $id]);

        return $stmt->fetchObject() ?: null;
    }

    public function where(string $column, mixed $value): ?object
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM {$this->table} WHERE {$column} = :value LIMIT 1"
        );

        $stmt->execute(['value' => $value]);

        return $stmt->fetchObject() ?: null;
    }

    public function create(array $data): int
    {
        $columns = implode(', ', array_keys($data));

        $placeholders = implode(
            ', ',
            array_map(
                fn ($key) => ':' . $key,
                array_keys($data)
            )
        );

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->table,
            $columns,
            $placeholders
        );

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($data);

        return (int) $this->pdo->lastInsertId();
    }
}
