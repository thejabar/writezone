<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Writ;
use Core\Authentication\Auth;
use Core\Http\Request;

class WritController
{
    public function index(Request $request): string
    {
        return view('writs.index', [
            'writs' => Writ::feed(),
        ]);
    }

    public function create(Request $request): string
    {
        return view('writs.create');
    }

    public function store(Request $request): string
    {
        $id = Writ::create([
    'user_id' => Auth::id(),
    'content' => trim(
        $request->input('content')
    ),
]);

        return "Writ created: /writs/{$id}";
    }

    public function show(
        Request $request,
        string $id
    ): string {

        $writ = Writ::findWithAuthor((int) $id);

        if (! $writ) {
            return 'Writ not found.';
        }

        return view('writs.show', [
            'writ' => $writ,
        ]);
    }
    public function edit(
    Request $request,
    string $id
): string {

    if (! Writ::belongsToUser(
        (int) $id,
        Auth::id()
    )) {
        return 'Unauthorized';
    }

    $writ = Writ::find((int) $id);

    return view('writs.edit', [
        'writ' => $writ,
    ]);
}

public function update(
    Request $request,
    string $id
): string {

    if (! Writ::belongsToUser(
        (int) $id,
        Auth::id()
    )) {
        return 'Unauthorized';
    }

    Writ::updateById(
        (int) $id,
        [
            'content' => trim(
                $request->input('content')
            ),
        ]
    );

    return "Updated: /writs/{$id}";
}

public function delete(
    Request $request,
    string $id
): string {

    if (! Writ::belongsToUser(
        (int) $id,
        Auth::id()
    )) {
        return 'Unauthorized';
    }

    Writ::deleteById((int) $id);

    return 'Writ deleted.';
}
}