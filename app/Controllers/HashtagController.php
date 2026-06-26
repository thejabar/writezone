<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Models\Hashtag;
use Core\Http\Request;
class HashtagController
{
    public function show(
        Request $request,
        string $tag
    ): string {
        return view(
            'hashtags.show',
            [
                'tag' => $tag,
                'writs' => Hashtag::findWrits(
                    $tag
                ),
            ]
        );
    }
}