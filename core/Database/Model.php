<?php

declare(strict_types=1);

namespace Core\Database;

abstract class Model
{
    protected static string $table;

    protected static function query(): QueryBuilder
    {
        return new QueryBuilder(
            Connection::getInstance(),
            static::$table
        );
    }

    public static function find(int|string $id): ?object
    {
        return static::query()->find($id);
    }

    public static function where(
        string $column,
        mixed $value
    ): ?object {
        return static::query()->where($column, $value);
    }

    public static function create(array $data): int
    {
        return static::query()->create($data);
    }
    public static function all(): array
{
    return static::query()->all();
}

public static function whereAll(
    string $column,
    mixed $value
): array {
    return static::query()->whereAll(
        $column,
        $value
    );
}
}
