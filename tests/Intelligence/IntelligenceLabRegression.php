<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

use App\Intelligence\Lab\IntelligenceLab;
use App\Intelligence\Lab\LabRequest;

final class IntelligenceLabRegression
{
    private int $passed = 0;
    private int $failed = 0;

    public function run(): int
    {
        $this->testShortContent();
        $this->testDevelopedContent();
        $this->testQualityAverage();
        $this->testReportCompleteness();
        $this->testExecutiveSummary();
        $this->testGrammarAnalysis();
        $this->testVocabularyAnalysis();
        $this->testReadabilityAnalysis();

        echo PHP_EOL;
        echo "======================================================" . PHP_EOL;
        echo "RESULT: {$this->passed} passed, {$this->failed} failed" . PHP_EOL;
        echo "======================================================" . PHP_EOL;

        return $this->failed === 0 ? 0 : 1;
    }

    private function testShortContent(): void
    {
        $result = $this->analyze(
            'WriteZone Intelligence Engine audit test.'
        );

        $this->assert(
            $result->score() >= 0 &&
            $result->score() <= 100,
            'Short content produces a bounded quality score.'
        );

        $this->assert(
            $result->metrics()->words > 0,
            'Short content produces word metrics.'
        );

        $this->assert(
            $result->quality()->breakdown() !== [],
            'Short content produces a quality breakdown.'
        );
    }

    private function testDevelopedContent(): void
    {
        $content = <<<TEXT
WriteZone provides a structured writing environment for people who want
clearer and more useful content.

The Intelligence Engine analyses several measurable properties of the
writing. These include structure, readability, vocabulary, grammar, and
sentence characteristics.

The purpose of this analysis is to provide transparent feedback that can
help writers understand their draft and identify areas for improvement.
TEXT;

        $result = $this->analyze($content);

        $this->assert(
            $result->metrics()->words >= 50,
            'Developed content is measured as substantial content.'
        );

        $this->assert(
            $result->metrics()->paragraphs >= 3,
            'Paragraph structure is detected.'
        );

        $this->assert(
            $result->score() > 0,
            'Developed content receives a non-zero quality score.'
        );
    }

    private function testQualityAverage(): void
    {
        $content = <<<TEXT
WriteZone is designed to help writers improve their content.

Clear structure and readable sentences make information easier to understand.
A varied vocabulary can also make writing more precise and engaging.

The Intelligence Engine evaluates these dimensions separately and combines
their results into an overall quality score.
TEXT;

        $result = $this->analyze($content);

        $breakdown = $result->quality()->breakdown();

        $this->assert(
            count($breakdown) === 3,
            'Quality evaluation currently contains three dimensions.'
        );

        $expected = round(
            array_sum($breakdown) / count($breakdown),
            2
        );

        $this->assert(
            abs($result->score() - $expected) < 0.01,
            'Overall quality score equals the average of evaluator scores.'
        );
    }

    private function testReportCompleteness(): void
    {
        $result = $this->analyze(
            'This is a complete WriteZone intelligence report test.'
        );

        $this->assert(
            $result->report() !== null,
            'LabResult exposes the AnalysisReport.'
        );

        $this->assert(
            $result->metrics() !== null,
            'Report exposes content metrics.'
        );

        $this->assert(
            $result->sentences() !== null,
            'Report exposes sentence analysis.'
        );

        $this->assert(
            $result->vocabulary() !== null,
            'Report exposes vocabulary analysis.'
        );

        $this->assert(
            $result->readability() !== null,
            'Report exposes readability analysis.'
        );

        $this->assert(
            $result->grammar() !== null,
            'Report exposes grammar analysis.'
        );

        $this->assert(
            $result->grammarFeedback() !== null,
            'Report exposes grammar feedback.'
        );

        $this->assert(
            $result->grammarDiagnostics() !== null,
            'Report exposes grammar diagnostics.'
        );

        $this->assert(
            $result->metadata() !== null,
            'Report exposes analysis metadata.'
        );
    }

    private function testExecutiveSummary(): void
    {
        $result = $this->analyze(
            'WriteZone analyses writing and provides structured feedback.'
        );

        $summary = $result->executiveSummary();

        $this->assert(
            $summary !== null,
            'Executive summary is generated.'
        );

        $this->assert(
            $summary->title !== '',
            'Executive summary has a title.'
        );

        $this->assert(
            $summary->summary !== '',
            'Executive summary has a summary.'
        );

        $this->assert(
            $summary->overallAssessment !== '',
            'Executive summary has an assessment.'
        );
    }

    private function testGrammarAnalysis(): void
    {
        $result = $this->analyze(
            'This sentence is correctly formed. Another sentence follows.'
        );

        $this->assert(
            $result->grammar()->score >= 0 &&
            $result->grammar()->score <= 100,
            'Grammar score is bounded between 0 and 100.'
        );
    }

    private function testVocabularyAnalysis(): void
    {
        $result = $this->analyze(
            'Clear writing uses precise words. Precise words help readers.'
        );

        $vocabulary = $result->vocabulary();

        $this->assert(
            $vocabulary->totalWords > 0,
            'Vocabulary analysis counts words.'
        );

        $this->assert(
            $vocabulary->lexicalDiversity >= 0 &&
            $vocabulary->lexicalDiversity <= 100,
            'Lexical diversity is bounded between 0 and 100.'
        );
    }

    private function testReadabilityAnalysis(): void
    {
        $result = $this->analyze(
            'Clear writing should be easy to understand. Short sentences can
            help readers process information. Good paragraph structure can
            also improve the reading experience.'
        );

        $readability = $result->readability();

        $this->assert(
            $readability->score >= 0 &&
            $readability->score <= 100,
            'Readability score is bounded between 0 and 100.'
        );
    }

    private function analyze(string $content)
    {
        $lab = new IntelligenceLab();

        return $lab->analyze(
            new LabRequest($content)
        );
    }

    private function assert(
        bool $condition,
        string $message
    ): void {
        if ($condition) {
            $this->passed++;

            echo "[PASS] {$message}" . PHP_EOL;

            return;
        }

        $this->failed++;

        echo "[FAIL] {$message}" . PHP_EOL;
    }
}

$test = new IntelligenceLabRegression();

exit(
    $test->run()
);
