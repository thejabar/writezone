<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Http\Request;

class WritController
{
    public function show(Request $request, string $id): string
    {
        return "Viewing Writ #{$id}";
    }

    public function store(Request $request): string
    {
        return 'Writ created successfully.';
    }
}