# WZP-02.1 Test Safety and Isolation Specification

**Status:** Proposed controls; no tests executed
**Policy:** Layered execution with explicit per-layer authorization and isolation

## Current runners

| Runner | Source behavior | Safety assessment |
|---|---|---|
| `scripts/test/run.sh` | Runs 17 standalone PHP scripts, including `WritPublicationEventRegression`, shared-storage/rate-limit tests, and session tests. Each runs in a separate PHP process; there is no test-layer gate or isolated storage root. | **UNSAFE as aggregate. Do not run.** |
| `scripts/release/check.sh` | Requires clean `git status`, runs Composer validation and syntax checks, invokes the aggregate runner, then invokes `php migrate.php status`. | **UNSAFE / unavailable here. Do not run.** On this dirty tree it would stop at the clean-tree guard; if clean, it reaches unsafe tests and a database connection. It is not a safe alternative. |
| `tests/Intelligence/Remote/PythonClassificationClientRegression.php` | Uses temporary fixture/log files, `proc_open`, cURL, and local loopback sockets/HTTP fixture processes. It is omitted from the aggregate runner. | Controlled local-service layer only after explicit isolation/environment review; not run. |
| `tests/RateLimitRouterRegression.php` | Separate script omitted from the aggregate runner; writes and then deletes a deterministic rate-limit file under application storage. | Unsafe until storage is isolated. |

## Regression-script side-effect matrix

There are 19 `*Regression.php` scripts in the local `tests/` inventory, plus `tests/Intelligence/bootstrap.php`. The classification below is based on source inspection, not execution.

| Script | Reads | Writes/deletes or process effects | DB / external / concurrency | Proposed layer and current disposition |
|---|---|---|---|---|
| `AuthorizationRegression.php` | Composer autoloader and policy classes | In-memory model objects and assertions only | No DB or external call found | Layer 1 candidate; safe only after runtime is verified. |
| `DatabaseDisclosureRegression.php` | Application source text | In-memory assertions | No DB connection | Layer 1 candidate. |
| `ErrorHandlerRegression.php` | `core/Error/ErrorHandler.php` and app path | Deletes `storage/logs/app.log` if present, then writes a new log | Shared application log; can destroy pre-existing user evidence | Layer 2 only after log sink is test-owned. |
| `A25LanguageFoundationRegression.php` | Language service source/classes | In-memory inputs/results | No external service found | Layer 1 candidate. |
| `A26LanguageTextRegression.php` | Language service source/classes | In-memory inputs/results | No external service found | Layer 1 candidate. |
| `A27LanguageSentenceRegression.php` | Language service source/classes | In-memory inputs/results | No external service found | Layer 1 candidate. |
| `IntelligenceLabRegression.php` | Local PHP classes/autoloader | In-memory analyses/results | Does not contact Python or an external provider | Layer 1 candidate; does not prove AI/provider integration. |
| `PythonClassificationClientRegression.php` | PHP client classes and local fixture code | `tempnam()` files, fixture process, loopback socket/HTTP, then cleanup | `proc_open`, cURL and local network only; concurrency/port handoff needs verification | Layer 4 controlled local fixture; opt-in, not a default aggregate dependency. |
| `RateLimiterRegression.php` | `RateLimiter` class | Uses fixed temp path `writezone-rate-limit-{PID}`; removes files and directory if already present, then creates and cleans it | Collision/PID reuse can make initial cleanup delete pre-existing temp data; concurrent runs are not safely namespaced | Layer 2 only after atomic unique-root creation and ownership checks. |
| `RateLimitH4Regression.php` | Middleware class via reflection | Changes in-process `$_SERVER`; no filesystem write found | No DB/network found | Layer 1 candidate. |
| `RateLimitMiddlewareRegression.php` | Rate-limit config and middleware | Starts a session; writes rate-limit records under `storage/cache/rate-limit`; deletes fixed-key files before/after | Shared app cache/session state; cleanup could remove a pre-existing record | Layer 2 only after injected test-owned storage and session path. |
| `RateLimitRouterRegression.php` | Router/middleware/config | Writes under `storage/cache/rate-limit` and deletes a deterministic login record | Shared state; omitted from aggregate | Layer 2 only after isolated storage. |
| `RequestBoundaryRegression.php` | Request-boundary source/classes | In-process request inputs | No external write found | Layer 1 candidate. |
| `RequestBoundaryRouterRegression.php` | Router and middleware | In-process `$_SERVER` and response capture | No DB or network found | Layer 1 candidate. |
| `RouteConstraintRegression.php` | Router/route source | In-memory routing assertions | No external write found | Layer 1 candidate. |
| `SecurityCompositionRegression.php` | Security source and route composition | In-memory/source assertions | No database use found | Layer 1 candidate. |
| `SecurityCsrfRegression.php` | CSRF and session classes | Starts and mutates PHP session; calls `session_destroy()` if a session is already active | Uses default PHP session configuration; no test-specific save path | Layer 2 only after a unique session directory/name is set before session start. |
| `SessionSecurityRegression.php` | Session/auth classes | Starts, regenerates, and destroys PHP session state | Uses default PHP session storage; no test-specific save path | Layer 2 only after isolated session configuration. |
| `WritPublicationEventRegression.php` | `.env` via Dotenv and application code | Opens PDO using environment-provided database/account, inserts a temporary user/Writ, and deletes the user for cascade cleanup | Database target is not independently authorized; cleanup safety depends on selected DB and FK behavior | Layer 3 only after fail-closed target policy and independent target verification. Do not run now. |

`tests/Intelligence/bootstrap.php` is a shared autoload/bootstrap helper; inspect any future changes for side effects before admitting a test to Layer 1. No test was executed in this package.

## Runner inventory and concurrency / cleanup review

`scripts/test/run.sh` invokes these 17 scripts sequentially in one shell process, launching a new PHP process for each: `Intelligence/IntelligenceLabRegression.php`, `WritPublicationEventRegression.php`, `SecurityCsrfRegression.php`, `RequestBoundaryRegression.php`, `RouteConstraintRegression.php`, `RateLimiterRegression.php`, `RateLimitMiddlewareRegression.php`, `RateLimitH4Regression.php`, `RequestBoundaryRouterRegression.php`, `AuthorizationRegression.php`, `ErrorHandlerRegression.php`, `DatabaseDisclosureRegression.php`, `SessionSecurityRegression.php`, `SecurityCompositionRegression.php`, and `Intelligence/A25LanguageFoundationRegression.php`, `Intelligence/A26LanguageTextRegression.php`, `Intelligence/A27LanguageSentenceRegression.php`. Sequential execution inside one run does not isolate it from another simultaneous run or from existing application state. The release runner first checks for a clean Git tree, but that check is not a data-safety control; if the tree were clean, the runner would invoke the same aggregate and then `migrate.php status`, which bootstraps a database connection.

| Script or group | Concurrent invocation and pre-existing-data risk | Required disposition |
|---|---|---|
| `AuthorizationRegression.php`, `DatabaseDisclosureRegression.php`, `A25LanguageFoundationRegression.php`, `A26LanguageTextRegression.php`, `A27LanguageSentenceRegression.php`, `IntelligenceLabRegression.php`, `RateLimitH4Regression.php`, `RequestBoundaryRegression.php`, `RequestBoundaryRouterRegression.php`, `RouteConstraintRegression.php`, `SecurityCompositionRegression.php` | Source inspection found only in-process objects, assertions, source reads, or superglobal changes. Each PHP process has its own memory; no pre-existing files are deleted by these scripts. This is not runtime verification, and shared bootstrap changes would need re-review. | Layer 1 candidates. Keep individually invocable; verify PHP runtime first. |
| `ErrorHandlerRegression.php` | Removes the shared `storage/logs/app.log` if present before writing. It can erase pre-existing logs, and simultaneous execution can race on the same path. | Do not run until the log destination is test-owned and isolated. |
| `RateLimiterRegression.php` | Uses `sys_get_temp_dir()/writezone-rate-limit-{PID}` and removes that path before use. PID reuse or collision can delete pre-existing data; concurrent callers sharing a PID namespace/path are not protected by an atomic unique root. | Do not run until changed to an atomically created per-run root with ownership-checked cleanup. |
| `RateLimitMiddlewareRegression.php`, `RateLimitRouterRegression.php` | Use fixed records under `storage/cache/rate-limit`; setup/cleanup unlinks matching records. They can delete pre-existing application rate-limit state and race with each other or live application requests. | Do not run until rate-limit storage is injected from an isolated per-run root. |
| `SecurityCsrfRegression.php`, `SessionSecurityRegression.php` | Start, mutate, regenerate, or destroy PHP session state with default session storage/configuration; existing session state may be affected and parallel runs can collide if they share identifiers/save paths. | Do not run until unique session name and test-owned session path are set before session start. |
| `WritPublicationEventRegression.php` | Loads `.env`, connects using its selected DB identity, writes a temporary user and Writ, and deletes the user for cascade cleanup. A random marker reduces record-name collision but does not authorize the target or make cleanup safe against an incorrectly selected/shared database; concurrent DB runs also depend on DB isolation and FK behavior. | Do not run until independent code-controlled target approval, pre-connection rejection tests, post-connection identity checks, least privilege, and an approved disposable DB are established. |
| `PythonClassificationClientRegression.php` | Uses uniquely allocated temporary files and local fixture processes/sockets, then removes the created files. It is omitted from the aggregate. Concurrent port/process startup and teardown still require bounded, per-run ownership checks. | Layer 4 only after local fixture and cleanup behavior is explicitly reviewed; it was not run. |

## Required layered test architecture

| Layer | Allowed behavior | Isolation and admission criteria |
|---|---|---|
| **1 — Pure** | Deterministic transformations, policy decisions, route parsing, source assertions | No database, network, shared filesystem writes, sessions, or non-unique cleanup. Run as an explicitly listed target after a compatible PHP runtime is verified. |
| **2 — Isolated filesystem** | File-backed rate limiting, error logging, PHP sessions | Create a unique per-run root atomically under a temporary parent; use child directories for logs, cache/rate-limit records, and sessions. Set session path/name before session start. Pass storage location through test-only injection. Cleanup must resolve and prove containment within the exact run root and delete only paths created by that run. Never delete a shared app path. |
| **3 — Disposable database** | PDO/migration integration against a dedicated disposable test target | A code-controlled allowlist approves the target tuple independently of `.env`; credentials may supply authentication only, never target authorization. Validate requested host, port, database, and account before PDO construction. After connecting and before writes, confirm `DATABASE()`, effective `CURRENT_USER()`, server identity/version, and expected charset/collation against approved values. Abort on missing, ambiguous, alternate, or mismatched identity. Use least privilege and run-owned record markers. No production/staging/shared database. |
| **4 — Controlled local service** | Client tests against a fixture bound only to loopback | Explicit local fixture path/port, bounded timeouts, process tracking, and test-owned temporary files. Refuse remote destinations. Clean up only created processes/files and verify shutdown. No provider, staging, production, or public listener. |

The previously discussed local target values (`writezone_test`, `127.0.0.1:3306`, application account `writezone_app`, migration account `writezone_migrator`, MariaDB `10.11.14`) are approval/planning values from earlier user decisions, not independently verified state in this inspection. This package did not connect to test or production databases. Do not let `.env` choose or authorize the target.

## Execution policy and runner design

1. Keep pure tests separately invocable from filesystem, database, and local-service tests.
2. Require an explicit layer selection; default aggregate should run only Layer 1 and must not infer authorization for Layers 2–4.
3. Make test bootstrap fail closed if a layer's required run ID/root/target policy is absent or malformed.
4. Use a test-run ID generated by the harness and a code-controlled temp parent; verify canonical paths before cleanup. Refuse symlink/path escapes and pre-existing unowned roots.
5. Do not load project `.env` for pure or filesystem tests. For Layer 3, keep approved identity constants/configuration separate from secret material; secret source cannot change the approved target tuple.
6. Route the Layer 3 application test through the validated target factory instead of its current direct PDO DSN. Migration credentials and operations remain a distinct opt-in unit.
7. Remove shared storage cleanup from tests; no test should unlink `storage/logs/app.log`, use `storage/cache/rate-limit`, or destroy a session from the default save path.
8. Review test cleanup failure paths, concurrent execution, process termination, and failure reporting before independent approval.
9. Do not run the current release gate until it no longer invokes unsafe tests or an unapproved DB connection. The dirty-tree guard is not a safety boundary for test data.

## Minimum acceptance evidence for a future isolation implementation

- Unit tests prove absent/invalid layer selection denies execution.
- Layer 2 tests prove unique roots, concurrent run separation, session/log/rate-limit isolation, containment-checked cleanup, and preservation of pre-existing files.
- Layer 3 policy tests prove missing, unapproved, ambiguous, and mismatched identities are rejected before any connection attempt; connection-spy evidence proves call order. Actual database integration requires a separately approved disposable environment and independently observed effective identity.
- Layer 4 tests prove the destination is loopback-only, fixture process is bounded, and failure cleanup leaves no process or file outside its run root.
- An independent reviewer reviews the exact diff and repeats only approved, isolated checks.

None of these conditions was tested or implemented during WZP-02.1.
