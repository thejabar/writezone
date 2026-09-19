<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Intelligence\Lab\IntelligenceLab;
use App\Intelligence\Lab\LabRequest;
use App\Intelligence\Language\LanguageRegistry;
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

        $language = trim(
            $request->input('language', 'en')
        );

        if ($content === '') {

            Response::json([
                'success' => false,
                'message' => 'No content provided.',
            ]);

        }

        $lab = new IntelligenceLab();

        try {
            $languageDefinition = LanguageRegistry::resolve(
                $language === ''
                    ? 'en'
                    : $language
            );
        } catch (\InvalidArgumentException) {
            Response::json([
                'success' => false,
                'message' => 'Unsupported language.',
            ]);
        }

        $result = $lab->analyze(
            new LabRequest(
                content: $content,
                language: $languageDefinition->code()
            )
        );

        $metrics = $result->metrics();

        $quality = $result->quality();
        
        $grammar = $result->grammar();
        
        $grammarFeedback = $result->grammarFeedback();
        
        $grammarDiagnostics = $result->grammarDiagnostics();
        
        $sentences = $result->sentences();
        
        $vocabulary = $result->vocabulary();
        
        $readability = $result->readability();
        
        $executiveSummary = $result->executiveSummary();

        $metadata = $result->metadata();

        Response::json([

            'success' => true,

            'score' => $result->score(),

            'metadata' => [

                'engineVersion' =>
                    $metadata->engineVersion(),

                'evaluatorCount' =>
                    $metadata->evaluatorCount(),

                'metricCount' =>
                    $metadata->metricCount(),

                'executionTime' =>
                    $metadata->executionTime(),

                'generatedAt' =>
                    $metadata->generatedAt()->format(
                        DATE_ATOM
                    ),

            ],

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
            
            'executiveSummary' => [

    'title' =>
        $executiveSummary?->title,

    'summary' =>
        $executiveSummary?->summary,

    'overallAssessment' =>
        $executiveSummary?->overallAssessment,

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

'grammar' => [

    'score' =>
        $grammar->score,

    'capitalizedSentences' =>
        $grammar->capitalizedSentences,

    'sentenceEndings' =>
        $grammar->sentenceEndings,

    'doubleSpaces' =>
        $grammar->doubleSpaces,

    'repeatedPunctuation' =>
        $grammar->repeatedPunctuation,

    'commas' =>
        $grammar->commas,

    'semicolons' =>
        $grammar->semicolons,

    'colons' =>
        $grammar->colons,

    'quotationMarks' =>
        $grammar->quotationMarks,

    'parentheses' =>
        $grammar->parentheses,

],

'grammarFeedback' => [

    'strengths' =>
        $grammarFeedback->strengths,

    'warnings' =>
        $grammarFeedback->warnings,

    'suggestions' =>
        $grammarFeedback->suggestions,

],

'grammarDiagnostics' => [

    'capitalizationConsistent' =>
        $grammarDiagnostics->capitalizationConsistent,

    'sentenceEndingsConsistent' =>
        $grammarDiagnostics->sentenceEndingsConsistent,

    'balancedQuotationMarks' =>
        $grammarDiagnostics->balancedQuotationMarks,

    'balancedParentheses' =>
        $grammarDiagnostics->balancedParentheses,

    'doubleSpacesDetected' =>
        $grammarDiagnostics->doubleSpacesDetected,

    'repeatedPunctuationDetected' =>
        $grammarDiagnostics->repeatedPunctuationDetected,

    'heavyCommaUsage' =>
        $grammarDiagnostics->heavyCommaUsage,

    'longSentencesDetected' =>
        $grammarDiagnostics->longSentencesDetected,

],

'readability' => [

    'score' =>
        $readability->score,

    'shortSentences' =>
        $readability->shortSentences,

    'longSentences' =>
        $readability->longSentences,

    'averageSentenceLength' =>
        $readability->averageSentenceLength,

    'averageParagraphLength' =>
        $readability->averageParagraphLength,

    'readingFlow' =>
        $readability->readingFlow,

    'difficulty' =>
        $readability->difficulty,

    'paragraphBalance' =>
        $readability->paragraphBalance,

],

        ]);

    }
}