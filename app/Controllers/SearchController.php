<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Services\SearchService;
use Core\Http\Request;
class SearchController
{
    public function index(
        Request $request
    ): string {
        $query = trim(
            (string) $request->input('q')
        );
        if ($query === '') {
            return view(
                'search.index',
                [
                    'query'   => '',
                    'results' => [
                        'users'    => [],
                        'writs'    => [],
                        'hashtags' => [],
                    ],
                ]
            );
        }
        return view(
            'search.index',
            [
                'query'   => $query,
                'results' => SearchService::searchEverything(
                    $query
                ),
            ]
        );
    }
}