# Testing and Quality Baseline

**Assessment date:** 2026-11-08
**Execution policy:** Run only checks that are local, isolated and do not touch databases, shared application storage or external services.

## Existing test conventions

The PHP suite uses standalone *Regression.php scripts with simple assertion helpers. composer.json declares no PHPUnit, static analyzer, formatter or Composer test scripts. scripts/test/run.sh invokes 17 scripts. scripts/release/check.sh requires a clean tree and invokes Composer validation, syntax, the aggregate suite and migrate.php status.

The Python service documents unittest discovery under services/intelligence/tests. Tests use in-process application harnesses and synthetic inputs; they require FastAPI/Pydantic/Uvicorn and the approved virtual environment for execution.

## Safety review

The aggregate PHP runner is **not safe to run in this checkout without a separately approved disposable test identity and storage isolation**:

| Test | Side effect identified |
|---|---|
| tests/WritPublicationEventRegression.php | Loads local dotenv and opens PDO; creates test user/Writ and deletes a record |
| tests/ErrorHandlerRegression.php | Deletes storage/logs/app.log before assertions |
| tests/RateLimitMiddlewareRegression.php | Writes and removes files in storage/cache/rate-limit |
| tests/RateLimitRouterRegression.php | Deletes rate-limit files under shared storage; this script is outside scripts/test/run.sh |

Additional PHP tests start/destroy sessions or use local storage. Inspect setup and cleanup before executing each test. Tests must use a separate test DB identity allowlist and unique test-owned paths. Cleanup must never remove pre-existing data or ambiguous targets.

## Checks executed

- Python AST parse only: **python -B -c "import ast,pathlib; files=list(pathlib.Path('services/intelligence').rglob('*.py')); [ast.parse(p.read_text(encoding='utf-8'), filename=str(p)) for p in files]; print(f'Parsed {len(files)} Python files')"** — passed for 19 files using Windows Python 3.14.3. No bytecode writes due to -B.
- PHP and Composer availability: not available in Windows PowerShell.
- WSL Python environment: access attempt returned Wsl/Service/E_ACCESSDENIED; no further attempt was made.
- Windows Python FastAPI import: unavailable (ModuleNotFoundError); no package was installed.
- No PHP tests, Python unit tests, syntax checks, Composer validation, database test or release gate was run.

The AST check is not a Python type/dependency/runtime test. No test pass count is claimed.

## Required quality controls

1. Split safe pure regressions from DB-backed and shared-storage tests.
2. Require an independent code-controlled DB target allowlist and validation before connecting.
3. Give each test process unique log/session/cache/rate-limit paths; cleanup only artifacts created by that process.
4. Add test manifests stating required services, writes, environment and cleanup.
5. For PHP changes, run relevant syntax checks, isolated regression, security tests and affected user journey; report checks not run.
6. For Python changes, use the approved virtual environment and run only services/intelligence/tests where isolated.
7. For schema work, run version-specific migration tests on a disposable DB; never substitute source snapshots for runtime evidence.
8. Keep release gates separate from package-level tests; do not run the current release script with a dirty tree or unapproved DB target.
9. Record exact commit, environment identity (without secret), command, output, result and reviewer.
10. Add CI only after the project owner approves workflow, secrets, isolated database service and release authority.

## Acceptance rule

A test result applies only to the code, environment and scope it actually exercised. Static inspection, syntax parsing, isolated unit tests, database integration, staging verification and production verification are separate evidence classes.
