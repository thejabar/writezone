<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Models\Comment;
use App\Models\Writ;
use Core\Authentication\Auth;
use Core\Http\Request;
use Core\Http\Response;
class WritController
{
    public function index(
        Request $request
    ): string {
        return view('writs.index', [
            'writs' => Writ::feed(),
        ]);
    }
    public function create(
        Request $request
    ): string {
        return view('writs.create');
    }
    public function store(
        Request $request
    ): void {
        $content = trim(
            $request->input('content')
        );
        $publicId = Writ::generatePublicId();
        Writ::create([
            'public_id' => $publicId,
            'user_id'   => Auth::id(),
            'content'   => $content,
        ]);
        flash(
            'success',
            'Writ published successfully.'
        );
        Response::redirect(
            "/writs/{$publicId}"
        );
        return;
    }
    public function show(
        Request $request,
        string $id
    ): string {
        $writ = Writ::findByPublicId($id);
        if (! $writ) {
            return 'Writ not found.';
        }
        return view('writs.show', [
            'writ' => $writ,
            'comments' => Comment::forWrit(
                (int) $writ->id
            ),
        ]);
    }
    public function edit(
        Request $request,
        string $id
    ): string {
        $writ = Writ::findByPublicId($id);
        if (! $writ) {
            return 'Writ not found.';
        }
        if (! Writ::belongsToUserByPublicId(
            $id,
            (int) Auth::id()
        )) {
            return 'Unauthorized';
        }
        return view('writs.edit', [
            'writ' => $writ,
        ]);
    }
    public function update(
        Request $request,
        string $id
    ): void {
        $writ = Writ::findByPublicId($id);
        if (! $writ) {
            flash(
                'error',
                'Writ not found.'
            );
            Response::redirect('/writs');
            return;
        }
        if (! Writ::belongsToUserByPublicId(
            $id,
            (int) Auth::id()
        )) {
            flash(
                'error',
                'Unauthorized.'
            );
            Response::redirect('/writs');
            return;
        }
        Writ::updateById(
            (int) $writ->id,
            [
                'content' => trim(
                    $request->input('content')
                ),
            ]
        );
        flash(
            'success',
            'Writ updated successfully.'
        );
        Response::redirect(
            "/writs/{$writ->public_id}"
        );
        return;
    }
    public function delete(
        Request $request,
        string $id
    ): void {
        $writ = Writ::findByPublicId($id);
        if (! $writ) {
            flash(
                'error',
                'Writ not found.'
            );
            Response::redirect('/writs');
            return;
        }
        if (! Writ::belongsToUserByPublicId(
            $id,
            (int) Auth::id()
        )) {
            flash(
                'error',
                'Unauthorized.'
            );
            Response::redirect('/writs');
            return;
        }
        Writ::deleteById(
            (int) $writ->id
        );
        flash(
            'success',
            'Writ deleted successfully.'
        );
        Response::redirect('/writs');
        return;
    }
}