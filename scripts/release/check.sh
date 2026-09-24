#!/bin/sh
set -eu

ROOT="$(CDPATH= cd -- "$(dirname "$0")/../.." && pwd)"
cd "$ROOT"

echo "=== WriteZone Release Gate ==="

BRANCH="$(git branch --show-current)"
HEAD="$(git rev-parse HEAD)"

echo "Branch: $BRANCH"
echo "Commit: $HEAD"

if [ -n "$(git status --short)" ]; then
    echo "ERROR: working tree is not clean."
    git status --short
    exit 1
fi

echo "Git working tree: CLEAN"

echo "--- Composer validation ---"
composer validate --no-check-publish

echo "--- PHP syntax validation ---"
find app core routes resources database -type f -name '*.php' -print0 |
while IFS= read -r -d '' file
do
    php -l "$file" >/dev/null
done

echo "PHP syntax: PASS"

echo "--- Migration state ---"
php migrate.php status

echo "--- Release gate result ---"
echo "PASS"
echo "Release candidate: $HEAD"
