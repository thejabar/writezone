<?php

declare(strict_types=1);

namespace App\Controllers;

class WritController
{
    public function show(string $id): string
    {
        return "Viewing Writ #{$id}";
    }
public function store(): string
{
    return 'Writ created successfully.';
}
}
