# WZP-02 Initial Engineering Baseline Verification Report

**Programme:** WZ-MASTER-ROADMAP-2027.2
**Report date:** 2026-11-09
**Execution:** Local repository, read-only source/document/environment inspection
**Review independence:** NOT CLAIMED — independent reviewer is UNASSIGNED

## 1. Executive status

- **WZP-01:** CLOSED WITH CONDITIONS under WZP-01.8. E-08 is adopted at documentary closure level only; no specialist is appointed.
- **Baseline B:** APPROVED CANDIDATE, NOT FROZEN.
- **WZP-02:** READY FOR CONTROLLED ACTIVATION only for the initial local, read-only verification authorized by WZP-01.8. This report is not WZP-02 completion evidence.
- **Beta / production / deployment:** NOT AUTHORIZED.

The repository contains a custom PHP MVC modular monolith, a PHP-to-Python intelligence client foundation, a separate Python service, migrations, and standalone PHP regression scripts. Source review found material test-safety, source/schema, environment, and Baseline B gaps. No tests, PHP syntax checks, database operations, or runtime requests were performed. No production, staging, Hostinger, GitHub, or external service was accessed.

## 2. Repository baseline

At inspection start the branch was feature/mentor-engine at HEAD 95527c38c8bd4b554489dcad0bf5dc700a69d4ad. Normal git status output contained four pre-existing tracked modifications and 49 untracked file/directory entries (53 status entries; untracked directories are collapsed by default). The four tracked modifications were board/CTO-roadmap.md, docs/Book-VI-Product/Product-Roadmap.md, docs/README.md, and docs/engineering/README.md. Existing untracked work included AGENTS.md, WZP-01.6/WZP-01.7 evidence, roadmap and corporate documentation, services/, intelligence client files, and tests. All were preserved.

The WZP-01.7 audit reports 89 pre-existing entries before its two audit deliverables; both WZP-01.7 files were present at this inspection start, yielding 91 expanded-path entries for the later state. The captured default status display contains 53 entries because nested untracked directories are collapsed. These are different points/display modes, not a substantive evidence contradiction. The branch, HEAD, WZP-01.7 files, and WZP-01.6 fingerprints agree; both accepted-artifact fingerprints were recalculated locally and matched.

### Source-of-truth map

| Area | Local evidence | Finding and limit |
|---|---|---|
| Repository rules | Root AGENTS.md | Local contributor and governance requirements are present; it is untracked and preserved. |
| Architecture authority | docs/ARCHITECTURE_AUTHORITY.md, especially sections 1–8 | Status claims require source/runtime/test/environment evidence; the local handbook is docs/ARCHITECTURE.md. Documentation is not implementation or live-state evidence. |
| Current PHP structure | public_html/index.php, bootstrap/app.php, routes/web.php, app/, core/, resources/views/ | Verified source shows a custom MVC request pipeline with controllers, services, models, policies, middleware, PHP views, and PDO persistence. |
| PHP dependencies | composer.json and composer.lock | composer.json requires PHP ^8.2 and phpdotenv ^5.6; PSR-4 maps App to app/ and Core to core/. No PHPUnit, formatter, or linter configuration was found in the inspected root inventory. |
| Database references | database/schema/README.md, database/schema/live-schema.sql, database/migrations/, core/Database/Migrator.php | The SQL snapshot is a documented historical reference; executable migration code is separate. No live database state was checked. |
| Tests and release checks | scripts/test/run.sh, scripts/release/check.sh, tests/ | Tests are standalone PHP scripts. The release script requires a clean tree, invokes the aggregate runner, then calls migrate.php status. Neither script was run. |
| CI | Local root and known GitHub/GitLab/Azure/Bitbucket/Jenkins workflow locations | No CI workflow definition was found in the inspected local repository inventory. This is not a claim about GitHub settings or remote workflows. |

The PHP PythonClassificationClient classes are present but no call site was found in app routes, controllers, bootstrap, or views. config/intelligence.php defaults the remote feature to disabled. The Python health route reports process availability; the protected classification route deliberately returns HTTP 503 classification_unavailable until WP7 (services/intelligence/app/api/v1/health.py:12–20; services/intelligence/app/api/v1/classifications.py:12–21). The Writ feed's visible Intelligence panel uses the local FeedInspector and is not evidence of Python integration (resources/views/writs/index.php:50; resources/views/partials/intelligence-panel.php:5–10). No service was started or contacted.

## 3. Database and migration reconciliation

**VERIFIED IN DOCUMENTATION:** AGENTS.md names database/schema/live-schema.sql as the documented canonical schema authority. database/schema/README.md describes it as a historical, read-only forensic snapshot and says it is not the canonical executable migration baseline. These statements establish distinct documentary-reference and executable-migration roles, not live parity.

**VERIFIED IN SOURCE:** database/migrations/2026_09_22_000000_writezone_baseline.php declares the executable baseline and creates eight tables. core/Database/Migrator.php discovers files with a non-recursive glob of database/migrations/*.php; database/migrations/legacy/ is therefore not loaded by that discovery path. The legacy scripts contain top-level database connection/DDL statements, so accidental recursive inclusion would be unsafe. No discovery change is proposed.

Static comparison confirmed these recorded differences; none was selected as correct:

| Object | live-schema.sql | Baseline migration | Status |
|---|---|---|---|
| users.handle | Nullable | NOT NULL | Unresolved semantic difference; retain both until an authorized data/database owner decides. |
| notifications.actor_id | NOT NULL | Nullable | Unresolved semantic difference; retain both. |
| notifications actor index | Named fk_notifications_actor | Named idx_notifications_actor | Naming difference; determine whether only a name or an operational dependency matters. |

The two artifacts broadly describe the same eight-table baseline; this static comparison is not proof of complete semantic equivalence or MariaDB behavior. migrate.php loads dotenv and creates the database connection before dispatching status, baseline, or migrate (lines 9–30). The status subcommand therefore is not a no-connection check; baseline/migrate can write. Neither it nor any SQL was run. No database contents, credentials, or .env values were accessed. Live state remains NOT VERIFIED.

## 4. Test safety classification

The repository contains 19 PHP regression scripts plus the Intelligence test bootstrap. scripts/test/run.sh lists 17 tests. It omits RateLimitRouterRegression.php and the opt-in PythonClassificationClientRegression.php. No test was run.

| Classification | Tests and observed behavior | Execution decision |
|---|---|---|
| In-memory/source-only candidates | AuthorizationRegression, DatabaseDisclosureRegression, RateLimitH4Regression, RequestBoundaryRegression, RequestBoundaryRouterRegression, RouteConstraintRegression, SecurityCompositionRegression, A25LanguageFoundationRegression, A26LanguageTextRegression, A27LanguageSentenceRegression, IntelligenceLabRegression | Static inspection indicates no database or shared application-storage writes. Not run because PHP is unavailable. These still need a safe harness and reviewer before an approved test task. |
| Temporary filesystem with collision risk | RateLimiterRegression uses a PID-derived fixed directory in the system temp folder and deletes an existing directory/files there before use (tests/RateLimiterRegression.php:8–17,58–69). | Do not run until cleanup is fenced to a uniquely created test-owned path. |
| Shared application log | ErrorHandlerRegression deletes storage/logs/app.log if present, then exercises error logging (tests/ErrorHandlerRegression.php:10–14). | Unsafe; do not run. |
| Shared rate-limit storage and session | RateLimitMiddlewareRegression starts a session and cleans fixed identities under storage/cache/rate-limit (tests/RateLimitMiddlewareRegression.php:41–58). RateLimitRouterRegression also writes and deletes a fixed shared rate-limit record (tests/RateLimitRouterRegression.php:64–82). | Unsafe until storage/session paths are isolated and cleanup proves ownership. RateLimitRouterRegression is outside the aggregate runner. |
| Session-mutating | SecurityCsrfRegression and SessionSecurityRegression start, alter, and destroy PHP sessions (tests/SecurityCsrfRegression.php:26–29,130; tests/SessionSecurityRegression.php:29–32 and session lifecycle assertions). RateLimitMiddlewareRegression also starts a session at line 75. Their save-path isolation is not established. | Do not run until a private per-run session path is verified. |
| Database integration | WritPublicationEventRegression loads dotenv and opens PDO using a localhost DSN with DB_DATABASE, DB_USERNAME, and DB_PASSWORD from the environment; it creates a test account and relies on deletion/cascade cleanup (tests/WritPublicationEventRegression.php:78–84 and integration cleanup later in the file). No independent code-controlled allowlist validates the database identity before connection. | Unsafe and prohibited. It may write to whichever local database the configured identity selects. Do not run. |
| Local process/socket fixture | PythonClassificationClientRegression uses temporary fixture/log files, proc_open, cURL, and local loopback HTTP fixtures (tests/Intelligence/Remote/PythonClassificationClientRegression.php:946–1005,1171–1519). | Not part of the aggregate runner; do not run in this task. It requires its own isolation review and PHP/cURL/process prerequisites. No external service behavior was verified. |

The aggregate runner is unsafe because it includes the database integration, shared log/cache, and session-mutating tests. scripts/release/check.sh exits on a dirty tree, then runs Composer validation, PHP syntax checks, the aggregate runner, and migrate.php status; it was not run. The D08/D09 policy decisions are not evidence that test identity validation or filesystem isolation has been implemented.

## 5. PHP and Python environment readiness

| Tool | Local observation | Result |
|---|---|---|
| PHP | No executable found by local PowerShell command discovery. | PHP version, extensions, syntax, and PHP tests NOT VERIFIED. |
| Composer | No executable found by local PowerShell command discovery. composer.json, composer.lock, and vendor/autoload.php exist. | Composer version and dependency runtime NOT VERIFIED. |
| Python | C:\Python314\python.exe; Python 3.14.3. | Windows Python is available; it does not establish the approved Ubuntu Python 3.12 environment. |
| WSL | wsl.exe is present; wsl.exe --list --verbose returned Wsl/EnumerateDistros/Service/E_ACCESSDENIED. | Distribution and approved WSL venv are inaccessible in this inspection. No alternate command or privilege was used. |
| MariaDB/MySQL | Not queried or contacted. | Version, service state, database identity, and schema are NOT VERIFIED. |

D10 remains open. The safe next step is a separately approved environment-owner verification route that can access the existing WSL distribution without installing or changing software. Do not use PHP or database checks until the executables and isolated test boundary are available.

## 6. Security and application findings

No Critical finding was established statically. Findings below are source observations, not exploit reproduction. Priorities express implementation/release impact, not proof of deployed exposure.

| ID / severity | Source evidence and observed behavior | Risk / required direction | Approval and regression evidence |
|---|---|---|---|
| WZP02-SEC-01 — HIGH | WritController edit/update/delete passes Writ query results to WritPolicy, whose parameter is typed App\Models\Writ; Writ::findByPublicId/findWithAuthor return PDO fetchObject results typed only as object (app/Controllers/WritController.php:93–110,118–142,175–200; app/Models/Writ.php:92–127; app/Policies/WritPolicy.php:10–18). CommentController passes Comment query objects to CommentPolicy typed as App\Models\Comment (app/Controllers/CommentController.php:125–181,190–212; app/Models/Comment.php: methods return fetchObject; app/Policies/CommentPolicy.php:10–18). | With ordinary PDO fetchObject behavior, the concrete row object is not the model class expected by the policy, so policy invocation can raise TypeError. This should fail closed, but likely breaks the approved Writ edit journey; runtime outcome was not tested. Establish a consistent subject contract and preserve default-deny authorization. | Separate owner-approved correction; test owner/non-owner edit and delete, missing IDs, comments, and ensure no unauthorized mutation. Writ editing is a Baseline B acceptance path. |
| WZP02-SEC-02 — HIGH | WritPublicationEventRegression.php:78–84 loads .env then opens PDO using environment-selected database name/account; tests/WritPublicationEventRegression.php:88 onward creates records and later cleans up. scripts/test/run.sh:18 includes it. | A test run has a database write path with no independent code-controlled target allowlist or pre-connection identity check. Cleanup cannot make an unapproved target safe. | WZP-02 test-safety package after separate approval. Required tests: missing/mismatched host, port, database, account denied before connection; no cleanup outside a unique test-owned namespace; no .env-only authorization. |
| WZP02-SEC-03 — HIGH (beta blocker) | No content-only Writ-report route/controller/view or operational workflow was found in routes/web.php or inspected app controllers/views; WZP-01.8 and CR-WZP-2026-11-09-01 require it before beta when public publishing is selected. | Public publishing cannot pass its approved safety condition without an accessible, staffed, restricted reporting/response process. This is a release gate; no beta is authorized. | Trust/Safety, security/privacy as applicable, and operations owners must be appointed. Future tests need report intake, authorization, triage/escalation, outcome, evidence, privacy, and operational rehearsal. |
| WZP02-SEC-04 — MEDIUM | AuthController::register trims fields, derives a handle, and hashes the supplied password, but has no explicit required-field, format, length, password-policy, uniqueness/collision, or validation-failure branches (app/Controllers/AuthController.php:50–85). The register view's HTML required/maxlength is client-side. | Invalid or malformed values can reach persistence; data integrity and account creation behavior are not bounded by server policy. Product field rules remain partly unresolved. | Product owner must approve field rules; then implement under a separate package. Test invalid/empty/boundary/Unicode values and prove rejected requests create no account. |
| WZP02-SEC-05 — MEDIUM | RequestBoundaryMiddleware casts CONTENT_LENGTH to int and checks configured max_bytes after the PHP request has reached the front controller; it then checks parsed GET/POST variables (app/Middleware/RequestBoundaryMiddleware.php:11–35; config/security.php:3–10; public_html/index.php:30–36). | Missing/invalid lengths become zero; this middleware does not itself cap a streamed body before SAPI parsing. Effective limits depend on PHP/web-server configuration that was not inspected. | Security/application owner decision; test at an isolated SAPI/web-server boundary plus malformed, missing, and oversized bodies. Do not treat current middleware as raw-body protection. |
| WZP02-SEC-06 — MEDIUM | AuthController::login returns “User not found.” for unknown email and “Invalid credentials.” for wrong password (app/Controllers/AuthController.php:15–30). | Distinguishes registered accounts and can support account enumeration. | Separate security-approved correction; negative login tests should assert generic response equivalence and rate-limit behavior. |
| WZP02-SEC-07 — MEDIUM | Authenticated GET /notifications invokes Notification::markAllRead before rendering (routes/web.php:265–270; app/Controllers/NotificationController.php:9–21; app/Models/Notification.php:43–52). | A safe-method request mutates notification state, including when crawlers/prefetchers or ordinary navigation load it. Notifications are excluded from B. | Do not change under this package. Resolve only before a separately approved notification scope; test GET is read-only and protected write has authentication, CSRF, rate limiting, and user scoping. |
| WZP02-SEC-08 — MEDIUM | POST /bookmarks/{id} restricts the route identifier to digits, while BookmarkController passes it to Writ::findByPublicId; the Writ view submits its seven-character public_id (routes/web.php:252–257; app/Controllers/BookmarkController.php:11–16; app/Models/Writ.php:66–90,111–127; resources/views/writs/show.php:15–25). | Current form/route/lookup contracts do not agree. Bookmarks are excluded from B. | Do not change under this package. If later approved, add route/model positive, negative, unknown-ID, ownership, and no-schema-change regression coverage. |
| WZP02-SEC-09 — MEDIUM | ErrorHandler emits exception messages when debug is enabled and logs exception message, file, and line (core/Error/ErrorHandler.php:55–75,117–158); public_html/index.php enables PHP display errors based on APP_DEBUG (lines 15–28). | Internal details may appear in responses in debug mode and sensitive exception data may enter logs. Current APP_DEBUG value, log access controls, and retention are NOT VERIFIED; no .env was read. | Operations/security review of environment policy and log access/retention; safe generic response and redaction/access tests in an isolated test harness. |
| WZP02-SEC-10 — MEDIUM (Baseline B mismatch) | Routes and views expose features excluded from candidate B: Writ delete, comments/replies/votes, follows, bookmarks, notifications, and Lab. The Writ feed includes the local FeedInspector panel; profile view renders follow counts/control; Writ detail renders bookmark/comment/delete controls (routes/web.php:131–295; resources/views/writs/index.php:50; resources/views/profile/show.php:19–37; resources/views/writs/show.php:18–46,76–97). | Existing code presence does not make these capabilities accepted. The local panel is not Python service integration; nevertheless, the B surface must match the approved exclusions. | Product owner and package owner must map B surfaces before freeze; acceptance checks should verify excluded routes/controls are unreachable in the approved B build. |

## 7. Baseline B implementation traceability

The controlling source is WZP-01.6 plus WZP-01-BASELINE-SCOPE-INVENTORIES.md and WZP-01-BASELINE-B-ACCEPTANCE-SPECIFICATION.md. Source presence is not acceptance evidence. B remains unfrozen.

| Candidate requirement | Current source observation | Gap / dependency | Proposed package and evidence |
|---|---|---|---|
| Public registration and authentication | Routes exist with guest, rate-limit, CSRF, and auth middleware (routes/web.php:39–72); AuthController handles login/register and hashes passwords (AuthController.php:15–85). | Server registration rules are missing; login messages differ; recovery route/view was not found; behavior not runtime tested. | WZP-PUB-01 after product/security decisions; isolated valid/invalid registration, session, generic login, and recovery/support journey tests. |
| Basic public profile | /@{handle} route and ProfileController resolve users and Writs (routes/web.php:160–163; app/Controllers/ProfileController.php:16–47). PO-02 approves the public handle, approved display name, and public Writ attribution. | The current view includes follow counts/button excluded from B. Verify that rendered fields match PO-02 and cover unavailable/empty/privacy states. | WZP-PUB-01; profile disclosure, unavailable/empty, keyboard, and responsive checks. |
| Text Writ publishing and authorized editing | POST /writs is authenticated, rate-limited, and CSRF-protected; WritController trims and publishes; WritPublisher creates the Writ and ID (routes/web.php:110–115; app/Controllers/WritController.php:49–71; app/Services/WritPublisher.php:18–39). | No server-side 1,000 Unicode-code-point enforcement; view maxlength is advisory; storage compatibility is required by PO-04; idempotent retry behavior is not established; policy subject type mismatch likely breaks edits. | WZP-PUB-01 after a separately approved correction plan; positive, boundary, Unicode, retry, authorization, storage-compatibility, and recovery evidence. |
| Public reading and attribution | Writ feed/detail queries join users; feed and search order Writs by created_at descending (app/Models/Writ.php:16–60,153–174). | No runtime visibility test; detail page renders excluded controls; no report action. | WZP-PUB-01; author, visibility, empty/unavailable, keyboard, mobile, and excluded-control checks. |
| Non-AI search and hashtags | SearchController/SearchService searches public users, public Writs, and hashtags; Writs are newest-first, as approved by PO-05; hashtag route exists (routes/web.php:144–152; app/Controllers/SearchController.php:8–35; app/Services/SearchService.php:9–44). | PO-05's fixed qualitative query cases remain required; query bounds and relevance behavior need to match the approved acceptance record. Mandatory WZP-PUB-03 → WZP-API-01 dependency remains. No database performance/runtime checks. | WZP-PUB-03 after WZP-API-01; fixed query suite, negative/empty/unavailable cases, and reviewed qualitative outcomes. |
| Responsive desktop and mobile-browser behavior | Layout includes viewport metadata and CSS media queries; this establishes responsive styling hooks only (resources/views/layouts/app.php; public_html/assets/css/app.css). | PO-07 browser/viewport matrix and live interaction review remain; no browser run. Native apps remain excluded. | WZP-PUB-01 cross-cutting evidence; approved browser/width matrix, task completion, keyboard, and accessibility checks. |
| Accessibility | Source contains forms/icons/templates; no accessibility test was executed. | No formal conformance claim; product-approved observable checklist and reviewer evidence remain. | WZP-PUB-01/WZP-PUB-04 as scoped; keyboard, labels, focus, zoom/reflow, contrast, and error-status evidence. |
| Recovery | No password recovery/reset route was found in routes/web.php or inspected auth views. PO-03 approves no self-service reset in B and no promise of unavailable recovery. | An appointed support owner must approve any assisted process before beta; no recovery implementation or support operation was verified. | WZP-PUB-01/WZP-OPS-01 as applicable; successful/failed/lost-access support journey evidence before beta. |
| Content-only Writ reporting | No public report route/controller/view or operator process found. | WZP-TRUST-01 and WZP-OPS-01 are a mandatory beta dependency if public publishing is selected. | WZP-TRUST-01/WZP-OPS-01; operational intake, restricted access, response, escalation, privacy, evidence, and rehearsal. |

## 8. Dependency and blocker register

| Blocker | Owner/authority | Consequence |
|---|---|---|
| Independent reviewer and delivery owner unassigned | Project Owner appointment required | No independent WZP-02 verification or exit claim; assignments required for later package work. |
| PHP/Composer/WSL verification route unavailable | Environment owner UNASSIGNED; PHP/Composer absent on Windows and WSL access denied | No PHP syntax or regression evidence; do not install or change environment. |
| Aggregate test safety and database-target validation | QA/security/database owners UNASSIGNED | No aggregate, DB, shared-storage, or session-mutating test execution. |
| D07 schema differences and authorized resolver | Database/data owner UNASSIGNED | Preserve both definitions; no schema choice, migration, or database access. |
| Baseline B field, journey, search, storage, recovery, accessibility, and browser evidence | Project Owner/product/security/accessibility authorities as applicable; several unassigned | B cannot be frozen; no beta invitation. |
| Content reporting process | Trust/Safety, support, privacy/legal, and operations owners UNASSIGNED | Public publishing is not beta-ready. |
| WZP-02 exit evidence | Owner/reviewer and QA/security/database owners | This initial report does not complete WZP-02 or satisfy E-17. |

## 9. Recommended implementation sequence (approval required)

1. Obtain Project Owner appointments for a WZP-02 delivery owner and independent verifier, plus database, security, QA, and environment owners where required. No current appointments are inferred.
2. Prepare and approve a narrowly scoped test-safety remediation design: unique test-owned storage paths, safe session configuration, a code-controlled database target allowlist, pre-connection identity checks, and safe cleanup. Do not connect until that design is implemented, reviewed, and separately approved.
3. Verify the existing PHP/Composer environment through an authorized, accessible read-only route. No installation is included.
4. Complete static schema/migration reconciliation with an appointed data/database owner. Preserve the three known differences until that owner supplies evidence; any live comparison requires separate database authorization.
5. After WZP-02 exit evidence and a separate implementation approval, address the highest-priority Baseline B correction plan. The Writ/comment policy subject contract and server-side registration/Writ validation need explicit test, security, and product acceptance plans. Keep notifications and bookmarks out of B unless separately approved.
6. Complete the Baseline B surface/exclusion review, content-reporting operational path, search cases, responsive/accessibility matrix, and other PO-conditions before B freeze and Gate 2.

This is a recommended sequence, not an implementation approval or roadmap status change.

## 10. Required appointments and independent verification

The WZP-01.8 decision appoints no person. Roles still recorded as unassigned include product decision authority, technical lead, database/migration owner, security reviewer, QA lead, independent verifier, Trust/Safety authority, privacy/legal reviewer, support owner, and operations/environment owner. An implementer must not be the sole independent verifier of their security, privacy, safety, or data-integrity work.

Before WZP-02 exit, an independently appointed reviewer should receive the exact source baseline, initial/final Git status, test side-effect map, proposed corrections, isolated test-target evidence, reproducible commands/results, schema difference ledger, environment versions, security findings, B traceability, and unresolved approvals. This report is not an independent audit.

## 11. Work not performed and evidence limitations

- No tests, syntax checks, Composer validation, application requests, or release scripts were executed.
- No database was connected to; no SQL, migration, schema/data write, or target identity check was performed.
- No .env or credential contents were read. The report describes code paths that would load them if the unsafe scripts were run.
- No packages, system configuration, or project runtime files were changed.
- WSL returned E_ACCESSDENIED; its installed distribution, approved Python venv, PHP, Composer, and MariaDB state could not be checked.
- No browser, mobile, accessibility, load, or user-journey verification occurred.
- Local source/doc findings do not establish GitHub, Hostinger, staging, production, or live database state.
- Static code review cannot establish the concrete PDO row class at runtime, effective PHP SAPI body limits, database permissions, actual secrets, or whether debug logging is enabled.

## 12. Recommended next Project Owner decision

Keep WZP-01 CLOSED WITH CONDITIONS and Baseline B APPROVED CANDIDATE, NOT FROZEN. Before approving any runtime correction, appoint WZP-02's delivery owner and independent verifier, identify an authorized PHP/Composer environment-verification route, and separately approve the precise test-safety remediation scope. Keep database access and testing, schema adoption, runtime implementation, beta, production, and deployment outside that approval unless each is expressly authorized.
