<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Intelligence\Lab\IntelligenceLab;
use App\Intelligence\Lab\LabRequest;
use Core\Http\Request;
use Core\Http\Response;

final class LabController
{
    /**
     * Display the Intelligence Studio.
     */
    public function index(
        Request $request
    ): string {

        $result = null;

        $content = '';

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
                'content' => $content,
            ]
        );

    }

    /**
     * Analyze writing and return JSON.
     */
    public function analyze(
        Request $request
    ): never {

        $content = trim(
            $request->input('content')
        );

        if ($content === '') {

            Response::json([
                'success' => false,
                'message' => 'No content provided.',
            ]);

        }

        $lab = new IntelligenceLab();

        $result = $lab->analyze(
            new LabRequest($content)
        );

        $metrics = $result->metrics();

        $quality = $result->quality();
        
        $sentences = $result->sentences();
        
        $vocabulary = $result->vocabulary();

        Response::json([

            'success' => true,

            'score' => $result->score(),

            'metrics' => [

                'characters' => $metrics->characters,
                'words' => $metrics->words,
                'sentences' => $metrics->sentences,
                'paragraphs' => $metrics->paragraphs,
                'mentions' => $metrics->mentions,
                'hashtags' => $metrics->hashtags,
                'links' => $metrics->links,
                'emojis' => $metrics->emojis,

                'readingTime' =>
                    $metrics->readingTime,

                'averageSentenceLength' =>
                    $metrics->averageSentenceLength,

                'averageParagraphLength' =>
                    $metrics->averageParagraphLength,

                'questions' =>
                    $metrics->questions,

                'exclamations' =>
                    $metrics->exclamations,

            ],

            'quality' => [

                'strengths' =>
                    $quality->strengths(),

                'suggestions' =>
                    $quality->suggestions(),

                'breakdown' =>
                    $quality->breakdown(),

            ],
            
            'sentences' => [

    'total' =>
        $sentences->total,

    'shortest' =>
        $sentences->shortest,

    'longest' =>
        $sentences->longest,

    'average' =>
        round(
            $sentences->average,
            1
        ),

    'variety' =>
        $sentences->variety,

],

'vocabulary' => [

    'totalWords' =>
        $vocabulary->totalWords,

    'uniqueWords' =>
        $vocabulary->uniqueWords,

    'repeatedWords' =>
        $vocabulary->repeatedWords,

    'lexicalDiversity' =>
        round(
            $vocabulary->lexicalDiversity,
            1
        ),

    'fillerWords' =>
        $vocabulary->fillerWords,

    'transitionWords' =>
        $vocabulary->transitionWords,

],

        ]);

    }
}