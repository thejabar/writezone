# WZP-01 Dependency and Exit Criteria

**Date:** 2026-11-09
**Purpose:** Define the handoff from decision closure to WZP-02 and the first bounded runtime correction.

## Package status

**Current status (2026-11-09):** WZP-01 is **CLOSED WITH CONDITIONS** under the separate Project Owner decision WZP-01.8. Baseline B remains **APPROVED CANDIDATE, NOT FROZEN**. WZP-02 is **READY FOR CONTROLLED ACTIVATION** only for the initial local, read-only verification authorized by WZP-01.8; the package is not complete, its independent reviewer and specialist owners remain unassigned, and its exit evidence is not established. This current status supplements, and does not rewrite, the historical WZP-01.6/WZP-01.7 records. Beta, production, deployment, database access, migrations, and runtime implementation remain unauthorized.

## Dependency sequence

1. **WZP-00 — EVIDENCE VERIFIED:** Use the programme baseline and current repository evidence. Its completion is documentation-only and grants no implementation authority.
2. **WZP-01 — decision and owner closure:** D01 remains approved with conditions. The Project Owner approved the bounded B journeys (D02), content-only Writ reporting scope (D03), qualitative task rubric (D04), schema-reference/migration-history distinction (D06), code-controlled test-target policy (D08), test invocation/isolation policy (D09), and WZP-01.6 dispositions PO-01–PO-07. WZP-01.8 records documentary closure with conditions and adopts E-08 only at the documentary boundary. Baseline B remains unfrozen; D05 appointments, D07 schema semantics, and D10 environment readiness remain blockers for the work that requires them. Exact beta operating values remain due before Gate 2/WZP-BETA-01.
3. **WZP-02 — test safety and source/schema reconciliation:** WZP-01.8 separately authorizes the bounded initial local, read-only verification recorded in the WZP-02 plan/report. That authorization does not appoint a delivery owner or independent reviewer, establish WZP-02 exit evidence, or authorize unsafe tests, database operations, migrations, or runtime corrections. Any follow-on activity must satisfy the relevant package entry conditions and receive separate approval.
4. **First runtime correction:** A separate explicit implementation approval must identify the exact behavior, files, tests, security review and acceptance. WZP-01 approval alone does not authorize it.
5. **Subsequent packages:** Proceed only when their roadmap dependencies, product decisions, owners, test environment and package-specific approval are satisfied.

## WZP-01 exit criteria

The following criteria governed WZP-01 closure review. WZP-01.8 records the Project Owner's conditional documentary disposition and expressly adopts the listed blocker-record treatment; it does not convert any blocker into evidence of implementation or authorize dependent activity. A recorded blocker satisfies only the WZP-01 recording requirement where the Project Owner permits it.

### Scope and product decisions

- Separate, versioned A/B/C scope inventories exist, with explicit inclusions, exclusions and conditional dependencies.
- WZP01-D02 approved baseline B journeys and boundaries are recorded in the versioned scope inventory: public account/authentication and basic profiles; text Writ creation/publishing/reading/authorized editing; basic non-AI keyword discovery; responsive desktop/mobile-browser support; and the specified validation, visibility, error, recovery, retry, empty, unavailable, invalid, accessibility and responsive acceptance boundaries. Durable draft/save-resume management and permanent deletion are excluded unless separately approved.
- WZP01-D04 approved qualitative, task-based rubric is recorded and covers account/profile, Writ create/edit/read, discovery usefulness, reporting accessibility/response, feedback/error clarity, accessibility/responsive usability, and safe unsuccessful operations. Numeric targets are optional later; behavioural analytics and engagement-driven tracking are excluded from B. Optional telemetry remains disabled pending separate approval.
- The WZP01-D03 content-only public Writ reporting scope and accountable operating requirements are recorded. Its package/dependency mapping is recorded under CR-WZP-2026-11-09-01. The process must be staffed, operational and evidenced before beta. Interaction reporting/moderation applies only if those features enter a separately approved inventory.
- Out-of-scope API, mobile, corporate, intelligence or analytics details are explicitly deferred rather than assumed.

### Ownership and approvals

- Each package in the first selected scope has an appointed accountable owner, technical delivery assignment, independent verifier and approver, or the explicit appointment blocker required by WZP01-D05. WZP-01.8 is a narrow Project Owner authorization for the initial local, read-only WZP-02 verification only; it does not appoint its owner or reviewer. Those assignments remain required for independent verification and WZP-02 exit.
- Security, QA, data, privacy/legal, Trust/Safety and operations authorities are appointed where the package requires them, or their unresolved decision is explicitly blocking.
- Role separation and independent-review conflicts are recorded.
- Project-owner approval of WZP-01 is recorded with the decision-register version/date.

### Technical decisions for the next package

- WZP01-D06’s approved roles for `database/schema/live-schema.sql` as documented schema reference and the migration chain as executable history are recorded. This is not a statement about any live database.
- WZP01-D07’s schema differences have a named resolver and evidence plan, or the Project Owner’s explicit blocker is maintained in the decision register. Schema changes and database adoption remain blocked while the resolver/evidence plan is absent.
- WZP01-D08’s approved code-controlled allowlist policy and WZP01-D09’s approved safe-invocation policy are documented. No secret or credential may serve as target approval evidence. Database tests remain prohibited until fail-closed validation and isolated execution are implemented and independently verified.
- WZP01-D10’s PHP/Composer environment-verification owner and read-only plan are recorded, or the Project Owner’s explicit blocker is maintained. PHP syntax/test checks remain blocked until the existing environment is verified. Installation or configuration requires separate authorization.
- WZP01-D11 notification behavior, WZP01-D12 bookmark public-identifier behavior, and WZP01-D13 feature-specific assurance remain pending. Notifications and bookmarks are excluded from baseline B and do not block WZP-01 closure. Resolve the applicable decisions before separate implementation approval or inclusion in a later inventory.
- Exact beta geography, eligibility, cohort, duration, notices and support limits may remain open during WZP-01 closure as approved by the Project Owner on 2026-11-09. Record accountable owners and decide these values before Gate 2/WZP-BETA-01 and any beta invitation.
- The required regression/security/user-journey evidence matrix and independent verifier remain unassigned under WZP01-D05. WZP-01.8 authorizes only its specified initial read-only inspection without an appointment; the independent verifier remains a blocker for verification claims, package exit, tests outside the explicitly safe read-only scope, and subsequent implementation.

## WZP-02 entry and staged exit criteria

### WZP-01.8 controlled initial verification authorization

- The exact initial WZP-02 scope is recorded in WZP-02-ENGINEERING-BASELINE-VERIFICATION-PLAN.md and is limited to local read-only inspection and safe documentation checks.
- The Project Owner expressly authorized this bounded initial verification in WZP-01.8. No delivery owner or independent reviewer is appointed by the authorization; both remain explicitly unassigned.
- Relevant routes, controllers, policies, models, tests, schema snapshot and migration files are frozen to a known local Git identity for review.
- No database access is necessary for static inventory, test-side-effect classification or snapshot-to-migration comparison.
- Findings and proposed changes cite source evidence and preserve all pre-existing work.

This permits only the initial source/document/environment inventory and static analysis. It does not clear unsafe test execution, database-dependent work, migration/schema decisions, independent-verification claims, runtime changes, or release activity. The source-only inspection itself does not satisfy WZP-02 exit criteria.

### Test-safety remediation exit

- Pure tests can run without a database, external service, production credential or shared application storage.
- Integration tests are classified and blocked unless their approved target policy validates host, port, database and account before connection.
- Environment-provided connection values cannot independently authorize the target.
- Logs, sessions, rate-limit data and temporary files use isolated test-owned locations.
- Cleanup deletes only records/files created and uniquely identified by that test run.
- Negative tests demonstrate missing, ambiguous, mismatched and unapproved target identity fails before connection or DB side effects.
- Aggregate runner includes only tests safe for its stated mode; DB integration is a separately named opt-in mode.
- Exact commands, results, environment versions and independent review are recorded.

### Source/schema reconciliation exit

- Each snapshot/baseline difference is listed with semantic impact and source references.
- Migration history and active migration discovery behavior are documented.
- No local, Hostinger or production state is inferred from repository artifacts.
- Database-dependent comparisons are deferred until the approved disposable target is verified.
- No migration or schema change is included without separate explicit authorization.

## Future notification/bookmark correction prerequisites

The notification GET mutation and bookmark public-ID route mismatch are technical findings, but both capabilities are excluded from baseline B. Any correction is possible future engineering work, not a current beta prerequisite, and requires separate implementation approval. If either capability is separately approved or included in a later release inventory, the relevant D11-D13 decisions and package evidence must be closed before implementation. The correction may start only after:

1. WZP-01 approves the expected notification interaction (bulk or per-item) and the bookmark URL contract.
2. WZP-02 establishes the relevant test mode and a safe way to exercise controller/model behavior; if database-backed, the target policy and disposable identity must independently pass before connection.
3. The implementation package names exact route/controller/model/view/test files and excludes schema changes and unrelated functionality.
4. Notification reads remain read-only on GET. The explicit write action requires authentication, CSRF, rate limiting and user-scoped authorization.
5. Bookmark route validation matches the current public identifier generator/lookup contract; do not infer format solely from the historical schema snapshot.
6. Regression cases cover read-only GET, allowed write, unauthenticated/CSRF-denied write, cross-user denial, valid public ID and invalid/unknown ID.
7. Relevant PHP syntax, regression, security and user-journey checks pass; an independent reviewer checks the diff.
8. Separate explicit approval authorizes implementation. WZP-01 and this plan are not that approval.

## Decisions that can remain open

These do not block WZP-02 if excluded from the selected scope and no dependency is introduced:

- Mobile framework, device/OS matrix and app-store plan.
- Full public API versioning/authentication details, unless an API consumer is in the first scope.
- Corporate workforce source, IdP/MFA, role catalogue and corporate schema, unless a corporate capability is included.
- AI model/provider, classification quality thresholds and recommendation policy, while intelligence remains disabled/unavailable.
- Numeric analytics targets, telemetry collection and retention when no telemetry is needed; privacy review is required before collection.
- Exact beta geography, eligibility, cohort size, duration, notices and support limits until before Gate 2/WZP-BETA-01; their timing deferral is approved, but the values and owners remain open.
- Launch date and production environment details until the applicable release gates.

WZP-01 must still record whether these domains are included, excluded or conditional in A/B/C. Their detailed design need not be completed merely to start unrelated source-only stabilization.

## Approval handoff

| Handoff | Required approver | Evidence |
|---|---|---|
| Close WZP-01 | Project owner and relevant domain/security/privacy approvers | Approved decision register, scope inventories and appointment record |
| Initial WZP-02 inspection | WZP-01.8 expressly authorizes the bounded local, read-only scope; no owner or independent reviewer is appointed | Exact scope in the WZP-02 plan, source baseline, and explicit record that independent review remains blocked |
| WZP-02 independent verification or follow-on work | Project Owner; required package owner, independent reviewer, and domain authorities must be appointed | Exact follow-on scope, role/conflict record, test and data boundaries, evidence plan, and separate approval |
| Run database-backed tests | Project owner and security authority | Approved disposable target, independent identity policy and pre-connection fail-closed evidence |
| Implement first runtime correction | Project owner for package scope; product and security approvers as applicable | Approved implementation plan, isolated tests, acceptance matrix and reviewer |
| Release any change | Explicit release authorization through GitHub governance | Reviewed exact diff/commit and required quality evidence |

## Current blockers and handoff

- **Specialist appointments:** Product authority, technical lead, database owner, security reviewer, QA lead, independent verifier, Trust/Safety authority, privacy/legal reviewer, and operations owner remain unassigned. WZP-01.8 appoints none; the relevant package and beta blockers remain.
- **D07 schema blocker:** Semantic reconciliation and an authorized resolver/data owner remain outstanding. No schema adoption, database access, migration, or write is authorized.
- **D08/D09 test safety:** The approved policies are not implementation evidence. The aggregate runner remains unsafe because it includes a configured-database integration test and shared-storage/session side effects. Database tests remain prohibited until isolated target validation and independent review are separately authorized and established.
- **D10 environment:** PHP and Composer executables are not available in the inspected Windows session; WSL inventory is inaccessible with E_ACCESSDENIED. The approved WSL PHP environment is therefore not independently verified here.
- **Content reporting and beta:** The approved content-only reporting scope is not implemented or operational. Trust/Safety, privacy/legal as applicable, support, and operations ownership/evidence remain required before beta if public publishing is selected.
- **Product conditions:** PO-03 recovery, PO-04 storage compatibility, PO-05 fixed query cases, PO-07 browser/viewport matrix, and exact beta operating values remain due at their specified implementation/freeze/release gates.
- **D11–D13:** Notification and bookmark decisions remain pending and outside Baseline B; they are not prerequisites for WZP-01 closure or this initial verification.
- **WZP-02 exit:** The initial plan/report do not close WZP-02. Independent review, safe execution containment, environment verification, complete traceability, and the other exit evidence remain outstanding before beta.

WZP-01 is **CLOSED WITH CONDITIONS** under WZP-01.8. Baseline B is **APPROVED CANDIDATE, NOT FROZEN**. WZP-02 is **READY FOR CONTROLLED ACTIVATION** only for the bounded initial verification specified by WZP-01.8; its full package exit remains open. Beta, production, deployment, database operations, migrations, and runtime implementation remain unauthorized.
