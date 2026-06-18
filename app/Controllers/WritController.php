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
            'title' => $request->input('title'),
            'content' => $request->input('content'),
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
}