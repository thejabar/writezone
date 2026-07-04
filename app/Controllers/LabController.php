<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Intelligence\Lab\IntelligenceLab;
use App\Intelligence\Lab\LabRequest;
use Core\Http\Request;

final class LabController
{
    public function index(
        Request $request
    ): string {

        $result = null;

        if (
            $request->method() === 'POST'
        ) {

            $content = trim(
                $request->input('content')
            );

            if ($content !== '') {

                $lab = new IntelligenceLab();

                $result = $lab->analyze(
                    new LabRequest($content)
                );

            }

        }

        return view(
    'lab.index',
    [
        'result'  => $result,
        'content' => $content ?? '',
    ]
);

    }
}