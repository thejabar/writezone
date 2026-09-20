<?php

declare(strict_types=1);

require_once __DIR__ . '/../../app/Intelligence/Language/LanguageDefinition.php';
require_once __DIR__ . '/../../app/Intelligence/Language/LanguageRegistry.php';
require_once __DIR__ . '/../../app/Intelligence/Language/Text/LanguageTextProcessor.php';
require_once __DIR__ . '/../../app/Intelligence/Support/SentenceParser.php';
require_once __DIR__ . '/../../app/Intelligence/Analysis/SentenceReport.php';
require_once __DIR__ . '/../../app/Intelligence/Analysis/SentenceAnalyzer.php';
require_once __DIR__ . '/../../app/Intelligence/Analysis/ContentMetrics.php';
require_once __DIR__ . '/../../app/Intelligence/Analysis/ContentAnalyzer.php';
require_once __DIR__ . '/../../app/Intelligence/Analysis/WordReport.php';
require_once __DIR__ . '/../../app/Intelligence/Analysis/WordAnalyzer.php';
require_once __DIR__ . '/../../app/Intelligence/Analysis/PunctuationReport.php';
require_once __DIR__ . '/../../app/Intelligence/Analysis/PunctuationAnalyzer.php';
require_once __DIR__ . '/../../app/Intelligence/Lab/AnalysisContext.php';
require_once __DIR__ . '/../../app/Intelligence/Lab/AnalysisContextBuilder.php';

use App\Intelligence\Analysis\SentenceAnalyzer;
use App\Intelligence\Language\LanguageRegistry;
use App\Intelligence\Lab\AnalysisContextBuilder;
use App\Intelligence\Support\SentenceParser;

$passed = 0;
$failed = 0;

function checkResult(string $name, bool $condition): void
{
    global $passed, $failed;

    if ($condition) {
        $passed++;
        echo "[PASS] {$name}" . PHP_EOL;
        return;
    }

    $failed++;
    echo "[FAIL] {$name}" . PHP_EOL;
}

$english = LanguageRegistry::resolve('en');
$arabic = LanguageRegistry::resolve('ar');
$chinese = LanguageRegistry::resolve('zh');

$englishSentences = SentenceParser::parse(
    'First sentence. Second sentence! Is this clear?',
    $english
);

$arabicSentences = SentenceParser::parse(
    'هذه جملة أولى. هل هذا واضح؟ وهذه جملة أخيرة!',
    $arabic
);

$chineseSentences = SentenceParser::parse(
    '这是第一句。这是第二句！这是第三句？',
    $chinese
);

checkResult(
    'English sentence boundaries',
    count($englishSentences) === 3
);

checkResult(
    'Arabic question mark boundary',
    count($arabicSentences) === 3
);

checkResult(
    'Chinese full-width punctuation boundaries',
    count($chineseSentences) === 3
);

$analyzer = new SentenceAnalyzer();

$chineseReport = $analyzer->analyze(
    '这是第一句。这是第二句！这是第三句？',
    $chinese
);

checkResult(
    'Chinese sentence total',
    $chineseReport->total === 3
);

checkResult(
    'Chinese shortest sentence',
    $chineseReport->shortest === 5
);

checkResult(
    'Chinese longest sentence',
    $chineseReport->longest === 5
);

checkResult(
    'Chinese average sentence length',
    $chineseReport->average === 5.0
);

checkResult(
    'Chinese sentence variety',
    $chineseReport->variety === 0
);

$arabicReport = $analyzer->analyze(
    'هذه جملة أولى. هل هذا واضح؟ وهذه جملة أخيرة!',
    $arabic
);

checkResult(
    'Arabic sentence total',
    $arabicReport->total === 3
);

checkResult(
    'Arabic sentence measurement',
    $arabicReport->shortest === 3
    && $arabicReport->longest === 3
    && $arabicReport->average === 3.0
);

$defaultReport = $analyzer->analyze(
    'First sentence. Second sentence! Is this clear?'
);

checkResult(
    'Default English sentence analysis remains compatible',
    $defaultReport->total === 3
);

/*
|--------------------------------------------------------------------------
| A2.7 integration checks
|--------------------------------------------------------------------------
*/

$contextBuilder = new AnalysisContextBuilder();

$arabicContext = $contextBuilder->build(
    'هذه جملة. هل هذا واضح؟ وهذه جملة أخيرة!',
    $arabic
);

checkResult(
    'Arabic context preserves selected language',
    $arabicContext->language()->code() === 'ar'
);

checkResult(
    'Arabic context detects question punctuation',
    $arabicContext->metrics()->questions === 1
);

checkResult(
    'Arabic context detects exclamation punctuation',
    $arabicContext->metrics()->exclamations === 1
);

checkResult(
    'Arabic context detects three sentences',
    $arabicContext->sentences()->total === 3
);

$chineseContext = $contextBuilder->build(
    '这是第一句。这是第二句！这是第三句？',
    $chinese
);

checkResult(
    'Chinese context preserves selected language',
    $chineseContext->language()->code() === 'zh'
);

checkResult(
    'Chinese context detects full-width question punctuation',
    $chineseContext->metrics()->questions === 1
);

checkResult(
    'Chinese context detects full-width exclamation punctuation',
    $chineseContext->metrics()->exclamations === 1
);

checkResult(
    'Chinese context detects three sentences',
    $chineseContext->sentences()->total === 3
);

echo PHP_EOL;
echo "A2.7 sentence regression: {$passed} passed, {$failed} failed." . PHP_EOL;

exit($failed === 0 ? 0 : 1);
