# WriteZone Engineering Readiness Assessmen

**Assessment date:** 2026-11-08
**Repository:** local checkout, branch feature/mentor-engine
**Authority:** [Architecture authority](../ARCHITECTURE_AUTHORITY.md), [canonical roadmap](../Roadmap/README.md), and repository guidance
**Scope:** static repository inspection, safe local checks, and documentation. No database or remote environment was accessed.

## Executive assessmen

**Overall: NOT READY for broad feature implementation or release.** The repository contains a working custom PHP MVC application with a number of public writing and participation flows, a new opt-in PHP-to-Python transport foundation, and a separately documented corporate architecture. The Project Owner has approved the long-term Knowledge Network / Knowledge Hub direction and the website-first, content-first baseline B candidate with conditions. B is not yet frozen as a final implementation inventory. The public product is not yet a complete Knowledge Network, the public API and native mobile foundations are absent but are not automatically required for B, safety and moderation controls are incomplete, the schema/migration authority is unresolved, and the available aggregate test command is not safe for an uncontrolled working tree or database.

The recommended next gate is closure of the remaining WZP-01 conditions and decisions, followed by safe test and source reconciliation under WZP-02. The notification GET mutation and bookmark identifier mismatch remain technical findings, but notifications and bookmarks are excluded from baseline B; any correction is possible future work outside B, not a current beta prerequisite. No implementation package is started or authorized by this assessment.

### Readiness snapsho

| Area | Assessment | Evidence boundary |
|---|---|---|
| PHP MVC application | Implemented; principal public flows exist | Local source inspection; not deployed-runtime verification |
| Core public journeys | Partially implemented | Publishing, comments, follows, bookmarks, notifications and keyword search exist; gaps are catalogued in the module inventory |
| Security baseline | Partially implemented, with bounded defects | Sessions, CSRF, request middleware, policies and rate limiting exist; see security assessment |
| Database/migrations | Blocked for safe change planning | Snapshot and baseline differ; no database was accessed |
| Test safety | Blocked for aggregate execution | Runner includes database-writing and shared-storage tests |
| Public API/mobile | Not started | API route files are empty; no native mobile client is present. Neither is automatically required for baseline B under D01 |
| Intelligence | Scaffold/client foundation reported complete, classification unavailable | Local code exists; independent WP6B closure and production properties are not established here |
| Corporate architecture | Documented proposal only | No corporate runtime or schema implementation established |
| Release/operations | Not production-verified | Local documentation only; no CI or deployment environment was inspected |

## Repository and Git baseline

**Verified locally:** branch feature/mentor-engine; upstream is origin/feature/mentor-engine; HEAD was 95527c3 (feat(events): integrate writ publication events); upstream divergence was 0 commits ahead and 0 behind at inspection. The worktree was already modified before this package.

Pre-existing tracked modifications: board/CTO-roadmap.md, docs/Book-VI-Product/Product-Roadmap.md, and docs/README.md. Pre-existing untracked work included AGENTS.md, app/Intelligence/Remote/, config/intelligence.php, docs/Corporate/, docs/Roadmap/ (including WriteZone Roadmap.zip), docs/Engineering/PYTHON_INTELLIGENCE_CLIENT.md, services/, and tests/Intelligence/Remote/. These were preserved.

This package adds nine engineering reports and updates the Engineering and top-level documentation indexes. No runtime files, existing roadmap/corporate/intelligence documents, or pre-existing changes are in scope.

## Principal findings

1. **Test safety is the first engineering blocker.** scripts/test/run.sh invokes WritPublicationEventRegression.php, which loads local environment configuration and performs MySQL writes; ErrorHandlerRegression.php deletes a shared log; rate-limit tests write/delete shared storage. The aggregate suite and release gate were not run.
2. **Schema authority needs reconciliation.** The schema README calls live-schema.sql a historical live reference snapshot, not an executable baseline. The root migration differs on users.handle nullability and notifications.actor_id nullability/index naming. No actual database state was checked.
3. **Safe HTTP semantics need correction if notifications are separately approved for a later scope.** NotificationController::index calls Notification::markAllRead for a GET route. Notifications are excluded from baseline B, so this finding is not a current beta prerequisite.
4. **Input and identity journeys need focused validation.** Registration strips characters from username rather than rejecting invalid input and does not implement complete server-side format/password policy checks, no email verification; login responses distinguish a missing user from an invalid password.
5. **Route behavior is inconsistent if bookmarks are separately approved for a later scope.** The bookmark detail route constrains a public Writ identifier as a numeric value even though bookmark lookup uses public_id. Bookmarks are excluded from baseline B, so this finding is not a current beta prerequisite.
6. **Public API and native mobile foundations are absent, but are not automatic baseline B prerequisites under D01.** If a later approved inventory includes API consumers or native apps, routes/api.php is empty and existing page routes do not provide a versioned JSON contract, mobile auth/session policy, standardized pagination, or stable error envelope.
7. **Knowledge discovery is basic.** Search uses keyword matching; feed retrieval lacks pagination and ranking scores are computed without a final sort in RankingEngine::rankAll.
8. **Optional intelligence remains unavailable.** The PHP client is disabled by default and is not routed into ordinary workflows; Python’s classification endpoint returns a generic unavailable result by design. WP6B is marked awaiting technical validation in the roadmap.
9. **Corporate work is design-only.** The WZ-CORP documents explicitly do not establish an IdP, HRMS, ERP, legal entities, workforce, or corporate access runtime.

### Source evidence anchors

| Finding | Local source evidence |
|---|---|
| Bookmark URL mismatch | routes/web.php:252-257 constrains id to numeric text; app/Controllers/BookmarkController.php:11-16 and app/Models/Writ.php:111-125 look up the public identifier |
| Notification GET mutation | routes/web.php:265-270 registers the GET route; app/Controllers/NotificationController.php:9-21 calls Notification::markAllRead before rendering |
| Registration and login validation | app/Controllers/AuthController.php:15-29 differentiates unknown user/password failures; lines 50-73 normalize a username and insert without complete validation |
| Parsed request boundary | app/Middleware/RequestBoundaryMiddleware.php:11-35 checks CONTENT_LENGTH and parsed GET/POST values |
| Migration/snapshot differences | database/schema/live-schema.sql:93-101, 111-122; database/migrations/2026_09_22_000000_writezone_baseline.php:23-26, 150-164 |
| Unsafe test effects | scripts/test/run.sh:17-33; tests/WritPublicationEventRegression.php:78-89; tests/ErrorHandlerRegression.php:10-14; tests/RateLimitMiddlewareRegression.php:48-57 |
| Synchronous publication event | app/Services/WritPublisher.php:38-47 and app/Events/EventDispatcher.php |

## Verification actually performed

- Read-only Git status, branch, upstream and commit inspection.
- Static source, documentation, migration, route, test-script, and configuration inspection.
- Local Python AST parsing using Python 3.14.3: 19 Python files parsed successfully. This verifies syntax only, not dependencies or tests.
- PHP and Composer availability checks: neither executable is available in Windows PowerShell. WSL access returned E_ACCESSDENIED. FastAPI import under Windows Python failed because the package is not installed there.
- The complete Python test suite, PHP tests, PHP syntax checks, Composer validation, database checks and release gate were not run.
- Documentation validation and git diff --check results are recorded in the completion report for this package.

## Decision and approval blockers

- Close WZP-01: complete D01's outstanding conditions and the remaining product, safety/permission, acceptance, and owner/appointment decisions. D01's long-term direction and website-first, content-first B candidate are approved with conditions; B is not frozen as a final implementation inventory.
- Clear WZP-02 only when its source/test reconciliation is complete and any database-dependent work has a separately verified disposable target with fail-closed identity controls.
- Finalize the versioned B inventory and its journeys. Public API implementation, native apps, AI, and corporate administration are not automatic B requirements; preserve D01 exclusions unless a separate approved scope decision changes them.
- Assign product, engineering, security/privacy, Trust/Safety, QA, operations and independent review authorities.
- Approve data/media, content, abuse/reporting, privacy, retention, localization, accessibility and support decisions relevant to the selected release.

## Recommended order

1. Close the remaining WZP-01 conditions, product/safety decisions and appointments against the conditionally approved D01 direction; do not treat B as frozen.
2. WZP-02 safe test-target policy and source/schema reconciliation; avoid aggregate tests until isolated.
3. WZP-03 continuous assurance procedure and named independent reviewers.
4. Stabilize only the approved B journeys after separate package approval. Notification GET and bookmark route corrections remain possible future work outside baseline B; D11-D13 are pending and apply if those capabilities are separately approved or included in a later inventory. Registration/login validation remains subject to its own approved scope and policy.
5. Approve stable public API contracts before API implementation only if an approved capability requires an API consumer. Neither public API implementation nor native apps are automatic baseline B prerequisites; defer mobile framework selection unless separately authorized.
6. Build moderation/reporting authority before expanding community participation.
7. Keep intelligence classification and corporate runtime behind their separate approvals and readiness gates.
8. Run Gate 2 only against a frozen beta inventory and Gate 3 only after beta evidence; neither is authorized now.

See [implementation sequencing and roadmap traceability](IMPLEMENTATION-SEQUENCING-RECOMMENDATION.md), [risk register](TECHNICAL-DEBT-AND-RISK-REGISTER.md), and the detailed domain assessments linked from the index.

## Safety declaration

This post-D01 documentation reconciliation did not access a database, credentials, staging, production, Hostinger, GitHub remote, or external service. It changed only the Markdown governance/readiness documents listed in the completion report; no application code, schema, migration, configuration, or tests were changed. Pre-existing tracked and untracked work was preserved. This is a local engineering assessment, not a deployment or production-readiness certification.
