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
    public function all(): array
{
    $stmt = $this->pdo->query(
        "SELECT * FROM {$this->table} ORDER BY id DESC"
    );

    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

public function update(int $id, array $data): bool
{
    $fields = implode(
        ', ',
        array_map(
            fn ($key) => "{$key} = :{$key}",
            array_keys($data)
        )
    );

    $sql = sprintf(
        'UPDATE %s SET %s WHERE id = :id',
        $this->table,
        $fields
    );

    $data['id'] = $id;

    $stmt = $this->pdo->prepare($sql);

    return $stmt->execute($data);
}

public function delete(int $id): bool
{
    $stmt = $this->pdo->prepare(
        "DELETE FROM {$this->table} WHERE id = :id"
    );

    return $stmt->execute([
        'id' => $id,
    ]);
}

public function whereAll(string $column, mixed $value): array
{
    $stmt = $this->pdo->prepare(
        "SELECT * FROM {$this->table}
         WHERE {$column} = :value
         ORDER BY id DESC"
    );

    $stmt->execute([
        'value' => $value,
    ]);

    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

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
