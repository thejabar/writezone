#!/bin/sh
set -eu

ROOT="$(CDPATH= cd -- "$(dirname "$0")/../.." && pwd)"
cd "$ROOT"

echo "=== WriteZone Regression Test Suite ==="

run_test() {
    name="$1"
    file="$2"
    echo "--- $name ---"
    php "$file"
    echo "[$name] PASS"
}

run_test "Intelligence Lab" tests/Intelligence/IntelligenceLabRegression.php
run_test "A2.5 Language Foundation" tests/Intelligence/A25LanguageFoundationRegression.php
run_test "A2.6 Language Text" tests/Intelligence/A26LanguageTextRegression.php
run_test "A2.7 Language Sentence" tests/Intelligence/A27LanguageSentenceRegression.php

echo "=== Regression Test Suite: PASS ==="
