<?php

declare(strict_types=1);

require_once __DIR__ . "/../../vendor/autoload.php";

use App\Intelligence\Analysis\ContentAnalyzer;
use App\Intelligence\Analysis\WordAnalyzer;
use App\Intelligence\Language\LanguageRegistry;
use App\Intelligence\Language\Text\LanguageTextProcessor;
use App\Intelligence\Lab\AnalysisContextBuilder;

$passed = 0;
$failed = 0;

function check(string $name, bool $condition): void {
    global $passed, $failed;
    if ($condition) {
        $passed++;
        echo "PASS: {$name}\n";
    } else {
        $failed++;
        echo "FAIL: {$name}\n";
    }
}

$processor = new LanguageTextProcessor();
$en = LanguageRegistry::resolve("en");
$ar = LanguageRegistry::resolve("ar");
$zh = LanguageRegistry::resolve("zh");

$english = "Clear academic writing should communicate ideas in a simple and direct way.";
$arabic = "تؤثر التكنولوجيا الحديثة بشكل كبير على طريقة التواصل وتبادل المعلومات.";
$chinese = "现代科技正在深刻地改变我们的沟通方式和信息交流。";

$enWords = $processor->words($english, $en);
$arWords = $processor->words($arabic, $ar);
$zhWords = $processor->words($chinese, $zh);

check("English baseline token count", count($enWords) === 12);
check("Arabic Unicode token count", count($arWords) === 10);
check("Chinese character baseline count", count($zhWords) === 24);
check("Empty input returns no tokens", $processor->words("", $en) === []);

$content = (new ContentAnalyzer())->analyze($english, $en);
check("ContentAnalyzer uses shared processor", $content->words === 12);

$words = (new WordAnalyzer())->analyze($arabic, $ar);
check("WordAnalyzer uses shared Arabic processor", $words->totalWords === 10);

$context = (new AnalysisContextBuilder())->build($arabic, $ar);
check("Context carries Arabic language", $context->language()->code() === "ar");
check("Context contains Arabic measurements", $context->words()->totalWords === 10);

$zhContext = (new AnalysisContextBuilder())->build($chinese, $zh);
check("Context carries Chinese language", $zhContext->language()->code() === "zh");
check("Context contains Chinese baseline", $zhContext->words()->totalWords === 24);

echo "\nA2.6 Language Text Regression: {$passed} passed, {$failed} failed.\n";
exit($failed === 0 ? 0 : 1);
