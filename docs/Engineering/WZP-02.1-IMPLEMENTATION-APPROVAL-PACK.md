# WZP-02.1 Proposed Implementation Approval Pack

**Status:** Proposal only — none of the units below is approved for implementation
**Decision authority:** Project Owner; specialist evidence and independent verification remain separate

Each unit is independently scoped. Approval of one unit does not authorize the others. The WZP-02.1 command authorizes the planning documents only.

## AP-01 — Writ and Comment authorization subject contract

| Field | Proposal |
|---|---|
| Objective | Make policy subject types agree with repository row objects without weakening default-deny ownership checks. |
| Included files | `app/Policies/WritPolicy.php`, `app/Policies/CommentPolicy.php`, `tests/AuthorizationRegression.php`. Add `tests/AuthorizationFlowRegression.php` only if its no-DB fixture is confirmed and included in the final approval. |
| Excluded | Routes, account model, database schema, comment scope expansion, admin/RBAC features, delete feature acceptance. |
| Method | Use guarded generic-row/ownership contract or an explicitly approved typed subject projection; preserve user/resource owner equality and deny malformed subjects. |
| Dependencies / risk | PHP environment and Layer 1 test capability. HIGH functional risk; no bypass currently proven. |
| Required tests | Generic PDO-shaped subjects: owner allowed, non-owner denied; absent/null/invalid owner denied; unknown ability denied; controller flow never mutates on denied/exception. Route-level DB test only after Layer 3 authorization. |
| Independent verification | Separate Security reviewer traces actual route-to-policy argument class and repeats regressions; verify mutation happens only after true authorization. |
| Rollback / recovery | Revert only the approved policy/test diff if regression fails; no data migration. Preserve other working-tree changes. |
| Authority | Project Owner implementation approval; application owner and Security reviewer required; independent verifier must be separate. |
| Acceptance | Writ edit owner path works; non-owner/anonymous/invalid resource cannot update; Comment paths (if retained) fail closed; no source path relies on a different subject contract. |

## AP-02 — Read-only PHP/Composer environment verification

| Field | Proposal |
|---|---|
| Objective | Verify current PHP 8.2+ CLI, Composer, required extensions, and repository access using the existing approved WSL environment. |
| Included components | Read-only inspection of `Ubuntu-24.04`, `/mnt/c/Users/DELL/Projects/writezone`, PHP/Composer paths, versions, extensions, lockfile/vendor readability. **No repository files.** |
| Excluded | Installation, package updates, PATH/global configuration changes, `.env`, database, tests, source edits, external CI. |
| Method | Normal-user read-only checks; stop if `E_ACCESSDENIED` persists. Portable fallback requires a new exact approval. |
| Dependencies / risk | Environment owner appointment and accessible WSL session. LOW if strictly read-only. |
| Tests | None; this is environment evidence. Later PHP syntax/tests require separate commands and test-layer approval. |
| Independent verification | Reviewer repeats version/path/extension checks without changing the environment. |
| Rollback / recovery | No state change; if access fails, record limitation and request owner decision. |
| Authority | Project Owner approves verification; environment owner executes; no install privilege. |
| Acceptance | Exact paths/versions/extensions and repository readability recorded; no installation or configuration changes. |

## AP-03 — Layer 1/2 test storage and session isolation

| Field | Proposal |
|---|---|
| Objective | Make pure and filesystem tests deterministic and unable to delete/write shared application data. |
| Included files/components | `scripts/test/run.sh`, `scripts/release/check.sh`, `tests/RateLimiterRegression.php`, `tests/RateLimitMiddlewareRegression.php`, `tests/RateLimitRouterRegression.php`, `tests/ErrorHandlerRegression.php`, `tests/SecurityCsrfRegression.php`, `tests/SessionSecurityRegression.php`, and new `tests/Support/IsolatedTestEnvironment.php`; any added config seam must be named in the implementation plan before approval. |
| Excluded | Database connection/target allowlist, migrations, application feature changes, external services, production paths. |
| Method | Create unique per-run root, isolate logs/rate-limit/session storage, and use containment-checked cleanup. Separate Layer 1 from opt-in Layer 2. Keep release runner away from unsafe Layer 3 and DB status commands by default. |
| Dependencies / risk | Verified PHP runtime; QA/security owners. HIGH data-protection risk if containment is wrong. |
| Required tests | Concurrent runs; unique root; pre-existing sentinel preserved; cleanup contained; session/log/cache writes confined; aggregate invocation selects only approved layers. |
| Independent verification | Review cleanup path resolution, symlink behavior, failure paths and run the approved filesystem-only tests in a fresh isolated temp root. |
| Rollback / recovery | Restore runner behavior by reverting only approved harness changes; never delete application log/cache/session contents as rollback. |
| Authority | Project Owner; QA and Security review; independent reviewer. |
| Acceptance | No test can delete or write outside the unique test-owned root; release runner clearly gates any integration layer. |

## AP-04 — Layer 3 disposable database target policy

| Field | Proposal |
|---|---|
| Objective | Reject unapproved or ambiguous DB identities before connection and verify effective identity before any test write. |
| Included files/components | New `tests/Support/ApprovedTestDatabaseTarget.php`, new `tests/Support/ValidatedTestDatabaseConnection.php`, update `tests/WritPublicationEventRegression.php`, add `tests/TestDatabaseTargetPolicyRegression.php`, update `scripts/test/run.sh` for explicit opt-in. Exact paths are proposed, not created. |
| Excluded | `.env`-authorized targets, production/staging/shared DB, user credentials in source, migration execution, schema changes, PHP app DB configuration. |
| Method | Code-controlled allowlist binds approved host/port/database/account; environment secret supplies password only. Validate before PDO; after connect verify `DATABASE()`, `CURRENT_USER()`, server/version/port/charset/collation before writing; abort without fallback. Use least privilege and per-run row ownership. |
| Dependencies / risk | AP-03; approved disposable identity; database owner, security review, accessible PHP/PDO MySQL. CRITICAL if bypassed. |
| Required tests | Missing/malformed/alternate target denied before factory call; valid approved tuple passes policy; test effective identity mismatch blocks first write; cleanup touches only marked records. Real DB tests only with separate target authorization. |
| Independent verification | Security/database reviewer inspects allowlist source and connection-spy order; independently verifies target identity without printing secrets. |
| Rollback / recovery | Disable Layer 3 invocation; retain test-owned data unless cleanup ownership is proven; no drop/recreate database by default. |
| Authority | Separate Project Owner target and implementation approval; database owner and Security review; independent verifier. |
| Acceptance | `.env` cannot authorize target, all identity mismatch cases fail closed pre-write, disposable identity independently verified, no shared target accepted. |

## AP-05 — Read-only schema/migration source reconciliation

| Field | Proposal |
|---|---|
| Objective | Resolve source/document authority and propose semantics for the three known differences without database access. |
| Included components | `database/schema/README.md`, `database/schema/live-schema.sql`, `database/migrations/2026_09_22_000000_writezone_baseline.php`, `core/Database/Migrator.php`, application read/write paths, and a source-only decision ledger. No files are currently authorized for modification. |
| Excluded | Database connection, SQL execution, migration/schema edits, production-state claims. |
| Method | Appoint database/data owners; compare definitions and all write paths; record approved desired semantics and any later migration plan. |
| Dependencies / risk | Database and data-owner appointment. HIGH data-integrity risk if semantics are guessed. |
| Tests | Static consistency only now; disposable migration tests after AP-04 and separate DB authorization. |
| Independent verification | Reviewer checks full source ledger and, only if separately authorized, exact disposable-target comparison. |
| Rollback / recovery | No source data change in this unit; defer migration and require separate backup/restore plan if later needed. |
| Authority | Project Owner and appointed database/data authorities. |
| Acceptance | D07 each resolved with rationale and evidence, or explicitly left blocked; no live-state conclusion without authorized evidence. |

## AP-06 — Registration and sign-in hardening

| Field | Proposal |
|---|---|
| Objective | Enforce approved server-side input rules, safe canonicalization, race-safe duplicate behavior, and generic login failures. |
| Included files | `app/Controllers/AuthController.php`, `resources/views/auth/register.php`, `resources/views/auth/login.php`, and new `tests/RegistrationSecurityRegression.php`. `routes/web.php` only if separately justified. |
| Excluded | Password reset/email verification/MFA, corporate IAM, schema changes, new analytics or tracking. |
| Method | Approve field policy first; validate before persistence; use existing hashing/session middleware; catch duplicate-key condition generically; preserve passwords without trim/truncation. |
| Dependencies / risk | AP-02, AP-03, product/security decisions, schema/collation evidence for identity uniqueness. MEDIUM security/integrity risk. |
| Tests | Missing/blank/wrong type, format/length/Unicode boundaries, empty/colliding handle, duplicate race (Layer 3 only), no session on rejection, generic login failure, CSRF/rate-limit/session checks. |
| Independent verification | Product confirms field rules; Security reviewer checks enumeration/credential handling; independent tester repeats exact cases. |
| Rollback / recovery | Revert only code/view changes; no database alteration. Preserve existing user accounts and accepted sessions. |
| Authority | Project Owner, Product, Security, application lead; Privacy review as applicable. |
| Acceptance | Approved policy is enforced server-side, no duplicate identity created, failed login wording does not reveal account existence, no unapproved recovery feature. |

## AP-07 — Schema decision and any future migration

| Field | Proposal |
|---|---|
| Objective | After AP-05, implement only an explicitly approved schema resolution, if evidence proves change necessary. |
| Included files/components | **Not determinable yet.** Any exact migration filename, schema snapshot update, runner change, or rollback script must be identified only after D07 closure and a separate migration plan. |
| Excluded | Any migration or database write under this approval pack; live state assumptions; production. |
| Method | Prepare data-preserving forward migration, pre/post checks, backup/restore test, compatibility and rollback/forward-repair strategy. |
| Dependencies / risk | AP-04, AP-05, appointed database/data owners, separately authorized disposable target. HIGH/CRITICAL data risk. |
| Tests | Disposable migration matrix, empty baseline, already-baselined schema, representative old rows/NULL values, failure/rollback and exact engine version. No live testing. |
| Independent verification | Independent database reviewer checks diff, target identity, data preservation, migration output and recovery rehearsal. |
| Rollback / recovery | Do not promise reverse DDL where data loss is possible; prefer backup restore or tested forward repair with explicit owner decision. |
| Authority | Separate Project Owner migration approval and database/security authority. |
| Acceptance | Exact approved schema semantics, reproducible migration, no duplicate objects, no data loss in disposable rehearsal, rollback/recovery verified. |

## AP-08 — Notification GET semantics (deferred feature)

| Field | Proposal |
|---|---|
| Objective | Make GET read-only and move read-state mutation to a protected write action, only if notification scope is separately selected. |
| Included files | `routes/web.php`, `app/Controllers/NotificationController.php`, `app/Models/Notification.php`, `resources/views/notifications/index.php`, new `tests/NotificationReadSemanticsRegression.php`. |
| Excluded | Baseline B inclusion, notification preferences/analytics, bulk policy beyond a separately accepted action. |
| Method | Read-only GET; explicit POST with auth, CSRF, rate limit and user-scoped update. |
| Dependencies / risk | Separate product scope and AP-03; MEDIUM integrity/HTTP semantics. |
| Tests | GET no mutation; POST only current user; CSRF/unauthenticated denied; idempotent retry; Layer 3 for DB persistence. |
| Independent verification | Security/product reviewer checks method semantics and identity scoping. |
| Rollback / recovery | Revert route/controller/view diff; no schema change proposed. |
| Authority | Separate Project Owner scope/implementation approval; product/security owners. |
| Acceptance | No state change on GET; every read transition is authenticated, CSRF-protected, rate-limited, user-scoped and tested. |

## AP-09 — Bookmark identifier contract (deferred feature)

| Field | Proposal |
|---|---|
| Objective | Align bookmark route acceptance with generated public Writ IDs while preserving public-ID lookup. |
| Included files | `routes/web.php`, optional `app/Models/Writ.php` ID validator, `app/Controllers/BookmarkController.php` only if necessary, new `tests/BookmarkPublicIdRouteRegression.php`. |
| Excluded | Baseline B inclusion, internal-ID route semantics, schema changes. |
| Method | Validate generated seven-character alphabet at the route/controller boundary; continue querying `public_id`. |
| Dependencies / risk | Separate product scope and AP-03; MEDIUM functional mismatch, no IDOR established. |
| Tests | Letter/digit IDs, malformed/unknown values, ensure numeric internal ID is not resolved, user-scope and idempotence. Layer 3 for persisted bookmark behavior. |
| Independent verification | Reviewer traces form → route → controller → query and repeats route cases. |
| Rollback / recovery | Revert only route/controller/model/test diff; no schema change. |
| Authority | Separate Project Owner scope and implementation approval; product/security owners. |
| Acceptance | Valid public ID reaches the public-ID query; invalid IDs fail safely; no internal-ID lookup or cross-user bookmark. |

## AP-10 — Baseline B acceptance-document reconciliation

| Field | Proposal |
|---|---|
| Objective | Align earlier acceptance wording with already approved PO-02/04/05 decisions and define remaining observable cases without changing B. |
| Included files | `docs/Engineering/WZP-01-BASELINE-B-ACCEPTANCE-SPECIFICATION.md`, `docs/Engineering/WZP-01-BASELINE-SCOPE-INVENTORIES.md`, `docs/Roadmap/02-WORK-PACKAGE-REGISTER.md`, `docs/Roadmap/05-PRODUCT-ACCEPTANCE.md`. |
| Excluded | Runtime behavior, new product scope, B freeze, numerical success targets without approval. |
| Method | Cite WZP-01.6 as controlling; replace stale “requires decision” text only where decision is already approved; leave technical/operational evidence open. |
| Dependencies / risk | Product authority and Project Owner review; LOW documentation risk. |
| Tests | Link, decision/status consistency, acceptance traceability, and human product review. |
| Independent verification | Product reviewer compares every changed line to WZP-01.6 and original artifact fingerprint/review evidence. |
| Rollback / recovery | Revert only documentation diff; preserve historical decisions. |
| Authority | Project Owner; product/accessibility/privacy authorities for any new unresolved values. |
| Acceptance | Every B outcome maps to approved decision and observable evidence; exclusions remain intact; B remains unfrozen until separate gate. |

## AP-11 — Content-reporting operating design and pre-beta readiness

| Field | Proposal |
|---|---|
| Objective | Convert approved content-only reporting scope into staffed and rehearsed operating procedure. |
| Included components | WZP-TRUST-01/WZP-OPS-01 operating procedure, restricted intake/case evidence, access/retention, response/escalation and rehearsal artifacts. Exact files are to be listed by appointed owners before approval. |
| Excluded | General interaction moderation unless PUB-02 is selected; unapproved legal claims or retention periods; implementation before policy approval. |
| Method | Appoint Trust/Safety, Operations, Security, Privacy/Legal owners; define categories/actions/responses/support handoff; rehearse with synthetic cases. |
| Dependencies / risk | WZP01-D03/CR scope approved; owner appointments required. HIGH safety and privacy risk. |
| Tests | Tabletop and access-control rehearsal; beta gate evidence. No real user reports in planning tests. |
| Independent verification | Independent audit confirms process, access, escalation, evidence and staffing. |
| Rollback / recovery | Suspend public invitations/publishing if the operating path fails; no database schema or deployment step implied. |
| Authority | Project Owner and appointed Trust/Safety, Security, Privacy/Legal, Product, Operations authorities. |
| Acceptance | Complete, staffed, privacy-controlled process and rehearsal evidence exists before beta invitations when public Writ publishing is in B. |

## AP-12 — Independent WZP-02 verification and Project Owner handoff

| Field | Proposal |
|---|---|
| Objective | Verify approved corrections and WZP-02 exit evidence against exact baseline and environment. |
| Included components | Exact approved diff, test matrix/results, environment identity, source/schema ledger, acceptance traceability, risks, and rollback evidence. No new code scope. |
| Excluded | Self-certification by implementer, broad feature development, beta/production/deployment. |
| Method | Appoint verifier distinct from implementer; reviewer re-runs only approved safe layers and checks source-to-test traceability. |
| Dependencies / risk | Relevant AP units completed; QA/security/database appointments; MEDIUM assurance risk. |
| Tests | Reproducible syntax and approved test suites; independent status/diff review; database evidence only if separately authorized. |
| Independent verification | This unit is the independent verification activity; verifier must be appointed before it starts. |
| Rollback / recovery | Return failed unit to implementation or blocked; retain findings and exact evidence; no reset/clean/stash. |
| Authority | Project Owner accepts WZP-02 exit separately; independent verifier attests only evidence. |
| Acceptance | All required findings dispositioned, blockers explicitly retained, evidence reproducible, B unchanged, and next package separately approved. |

## Required Project Owner decision

Choose which proposed unit(s), if any, to authorize; appoint the required delivery owner and independent verifier; identify the approved PHP/Composer route; and state whether Layer 1/2 test-isolation work may begin. Do not authorize Layer 3, schema writes, notifications/bookmarks, beta, production, or deployment by implication. The present command authorizes only this plan and its documentation package.
