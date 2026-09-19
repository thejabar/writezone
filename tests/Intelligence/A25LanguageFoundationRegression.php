<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

use App\Intelligence\Lab\AnalysisContextBuilder;
use App\Intelligence\Lab\LabRequest;
use App\Intelligence\Language\LanguageRegistry;

$passed = 0;
$failed = 0;

$assert = static function (
    bool $condition,
    string $message
) use (&$passed, &$failed): void {

    if ($condition) {

        $passed++;

        echo "[PASS] {$message}" . PHP_EOL;

        return;
    }

    $failed++;

    echo "[FAIL] {$message}" . PHP_EOL;
};

echo "======================================================" . PHP_EOL;
echo " WRITEZONE — A2.5 LANGUAGE FOUNDATION REGRESSION" . PHP_EOL;
echo "======================================================" . PHP_EOL;

$languages = LanguageRegistry::all();

$assert(
    count($languages) === 20,
    'Language registry contains 20 reserved language slots.'
);

$codes = array_map(
    static fn ($language): string => $language->code(),
    $languages
);

$assert(
    count($codes) === count(array_unique($codes)),
    'All language codes are unique.'
);

$assert(
    LanguageRegistry::default()->code() === 'en',
    'English is the default language.'
);

$english = LanguageRegistry::resolve('en');

$assert(
    $english->isActive(),
    'English is marked active.'
);

$urdu = LanguageRegistry::resolve('ur');

$assert(
    $urdu->isPlaceholder(),
    'Urdu is reserved as a placeholder.'
);

$englishResources = LanguageRegistry::resources(
    $english
);

$assert(
    in_array(
        'actually',
        $englishResources->fillerWords(),
        true
    ),
    'English filler resources contain the existing "actually" rule.'
);

$assert(
    in_array(
        'however',
        $englishResources->transitionWords(),
        true
    ),
    'English transition resources contain the existing "however" rule.'
);

$placeholderResources = LanguageRegistry::resources(
    $urdu
);

$assert(
    $placeholderResources->fillerWords() === [],
    'Placeholder languages have no English filler resources.'
);

$request = new LabRequest(
    content: 'WriteZone supports multilingual intelligence.',
    language: 'en'
);

$assert(
    $request->resolvedLanguage()->code() === 'en',
    'LabRequest resolves English correctly.'
);

$context = (new AnalysisContextBuilder())->build(
    content: $request->content(),
    language: $request->resolvedLanguage()
);

$assert(
    $context->language()->code() === 'en',
    'AnalysisContext carries the resolved language.'
);

echo PHP_EOL;
echo "======================================================" . PHP_EOL;
echo " A2.5 RESULT" . PHP_EOL;
echo "======================================================" . PHP_EOL;
echo "Passed: {$passed}" . PHP_EOL;
echo "Failed: {$failed}" . PHP_EOL;

exit(
    $failed === 0 ? 0 : 1
);
