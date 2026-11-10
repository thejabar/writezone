# WZP-01 Decision Register

**Package:** WZP-01 — Engineering Decision, Ownership and Approval Closure
**Date:** 2026-11-09
**Status (2026-11-09):** WZP-01 **CLOSED WITH CONDITIONS** under WZP-01.8; Baseline B **APPROVED CANDIDATE, NOT FROZEN**; WZP-02 **READY FOR CONTROLLED ACTIVATION** for the bounded initial verification only
**Authority:** [Architecture authority](../ARCHITECTURE_AUTHORITY.md), [WZ-MASTER-ROADMAP-2027.2](../Roadmap/README.md), and [roadmap decision register](../Roadmap/09-RISKS-DECISIONS-AND-CHANGE-CONTROL.md)

## Purpose and status rules

This register records the original WZP01-D01–D14 decision, the WZP-01.6 PO-01–PO-07 dispositions, the separate WZP-01.8 closure decision, outstanding blockers, and required evidence. WZP-01.8 controls the current WZP-01 closure status and authorizes only the bounded initial WZP-02 verification documented in its plan. It makes no specialist appointments and authorizes no runtime implementation, database work, beta, production, or deployment.

Decision statuses use the canonical meanings in Roadmap document 09:

- **APPROVED BASELINE** — explicitly accepted governing principle.
- **APPROVED WITH CONDITIONS** — the named authority approved the decision direction with explicit conditions; dependent implementation or release remains blocked until applicable conditions and separate approvals are satisfied.
- **RECOMMENDED — AWAITING APPROVAL** — a concrete recommendation awaiting its named authority.
- **UNRESOLVED — BUSINESS / LEGAL / SECURITY / TECHNICAL** — a decision or evidence is still required.
- **DEFERRED** — intentionally outside the current target scope.
- **NOT AUTHORIZED** — a separately approved implementation package is required.

The 2026-11-09 Project Owner record uses additional exact dispositions, including **APPROVED WITH DEFINED BOUNDARIES**, **APPROVED WITH EXPLICIT APPOINTMENT BLOCKERS**, **APPROVED**, **EXPLICIT BLOCKER RECORDED**, and **REMAINS PENDING**. Preserve these owner-supplied phrases verbatim in WZP-01 records. They do not extend or crosswalk the separate Roadmap D-01–D-13 decision vocabulary, and they do not alter work-package status.

Work-package statuses remain separate from decision dispositions. WZP-01.8 records WZP-01 as CLOSED WITH CONDITIONS and WZP-02 as READY FOR CONTROLLED ACTIVATION solely for its initial read-only verification scope. Neither status implies WZP-02 completion, Baseline B freeze, appointments, or release authority.

## Existing approved baselines

The following are governing constraints, not new recommendations:

1. Preserve the custom PHP MVC modular monolith; extend it incrementally and do not introduce a framework or microservices without an explicit architecture decision.
2. WriteZone is a Knowledge Network and Knowledge Hub with a content-first direction; do not substitute engagement-only success measures.
3. Preserve public/corporate identity separation. Corporate ORG/IAM/GOV designs do not imply that an IdP, HRMS, ERP, employee population, legal entity or runtime module exists.
4. Follow default-deny security, existing CSRF/session boundaries, backward compatibility, and relevant regression coverage.
5. Database work requires migration inspection, duplicate-object checks, verified target identity, and separate approval for writes. The documented schema snapshot is not proof of the current database.
6. GitHub is the release authority. Preserve the working tree; do not commit, push, merge or deploy without explicit approval.
7. WZP-01 is closed with conditions; WZP-02 is authorized only for the bounded initial local, read-only verification; package status is not runtime or implementation evidence.

The Project Owner Decision Sheet dated 2026-11-08 and the [consolidated Project Owner decision dated 2026-11-09](WZP-01-PROJECT-OWNER-CONSOLIDATED-DECISION.md) confirm the Project Owner authority required for WZP-01 but do not identify an individual or appoint any specialist.

AGENTS.md describes database/schema/live-schema.sql as the documented canonical schema authority. The schema README describes that artifact as a historical reference snapshot and explicitly says it is not the canonical executable migration baseline; docs/ARCHITECTURE_AUTHORITY.md says migration/source reconciliation remains open. WZP01-D06 now approves the distinction between documented reference and executable migration history; the intended reconciled executable authority and semantic conflicts remain unresolved. No live database state is established.

## Decision records

### WZP01-D01 — Release scope and exclusions

- **Description:** Approve distinct programme scope A, beta inventory B, and initial production inventory C, including exclusions and which capabilities are conditional.
- **Why required:** Scope controls product acceptance, testing, safety dependencies, schedule, audit inventory and owner assignments.
- **Existing position:** The Project Owner decision dated 2026-11-08 approves A as the long-term Knowledge Network/Knowledge Hub direction, B in principle as a website-first, content-first candidate, and C as a separately frozen inventory derived from capabilities that pass beta. The consolidated decision dated 2026-11-09 retains D01 without reopening it and resolves several WZP-01 conditions while leaving B unfrozen.
- **Options supported by evidence:** The recorded decision excludes bookmarks, public interactions, notifications, public API implementation, native applications, AI recommendations, corporate administration and behavioral analytics from baseline B. It retains package, acceptance, safety, operational and release conditions; it does not authorize implementation or beta invitations.
- **Implications:** This bounds the initial public candidate while preserving later programme scope. The content-only reporting boundary, B journey boundaries, and qualitative acceptance approach now have Project Owner decisions. Package/dependency records, operational reporting evidence, specialist appointment blockers, versioned scope inventories, WZP-02 exit evidence, WZP-OPS and audit evidence remain outstanding as applicable.
- **Dependencies:** WZP-00; product journeys; WZP-02 source baseline for implementation packages.
- **Decision owner:** WriteZone Project Owner; D01 supplied 2026-11-08 and retained by the consolidated decision dated 2026-11-09. Product/programme lead remains **UNASSIGNED**.
- **Required approval:** Project Owner D01 decision recorded; applicable security/privacy and domain authorities must still approve their respective controls and conditions.
- **Status:** **APPROVED WITH CONDITIONS**.
- **Conditions / closure evidence:** Preserve the decision in a versioned A/B/C inventory and complete WZP-01 exit evidence. WZP01-D02–D04 define the selected B journeys and qualitative rubric; WZP01-D03’s content-only reporting scope must be reconciled in package/dependency records through change control and operated before beta. Required role appointments remain unassigned and must be explicitly tracked as blockers. Exact beta geography, eligibility, cohort, duration, notices, and support limits may remain open through WZP-01 closure but must be decided and assigned before Gate 2/WZP-BETA-01 and any invitation. Before beta, complete WZP-02 exit evidence and WZP-OPS/applicable audit evidence. WZP-02 remains sequenced after WZP-01 closure. This decision does not authorize implementation or beta.

### WZP01-D02 — First-scope user journeys and acceptance

- **Description:** Approve the public journeys to be stabilized first and their observable acceptance cases.
- **Why required:** Existing code does not define intended behavior for every route, invalid input, denied action, recovery state or notification action.
- **Existing position:** The roadmap requires positive, denied, invalid, empty and recovery cases where relevant; the product direction prioritizes useful knowledge discovery and reading value.
- **Options supported by evidence:** Select only existing journeys for the initial inventory; defer features whose requirements or safety paths are not approved. Do not infer numerical success thresholds.
- **Implications:** Tests and implementation can be bounded only after owners identify expected outcomes; unspecified behavior must remain open.
- **Dependencies:** D01; content and safety decisions where applicable.
- **Decision authority:** Project Owner, per the consolidated decision dated 2026-11-09; the specialist product decision authority remains **UNASSIGNED** for subsequent package work.
- **Required approval:** Decision recorded. Product, engineering, security, accessibility, and operations owners retain their respective acceptance responsibilities when appointed.
- **Status:** **APPROVED WITH DEFINED BOUNDARIES** (exact Project Owner disposition).
- **Approved boundaries:** Public registration/authentication, basic public profiles, text Writ create/publish/read/edit, basic non-AI keyword discovery, and responsive desktop/mobile-browser journeys. Server-side validation, ownership, visibility, understandable errors, safe preservation after recoverable failures, duplicate-safe retry behavior, empty/unavailable/invalid/error states, accessibility, and responsive behavior are acceptance requirements. Durable drafts/save-resume and full draft management are excluded unless separately approved after WZP-02 evidence. Permanent Writ deletion is excluded from baseline B; any later deletion requires separate policy decisions.
- **Remaining evidence:** These are approved requirements, not proof that the current implementation meets them. The B inventory must be versioned, and each selected journey must receive implementation and verification evidence in its approved package.

### WZP01-D03 — Shared discussion, permission and moderation authority

- **Description:** Define who may report, triage, moderate, enforce, appeal and administer public discussions, and how permissions are scoped.
- **Why required:** Comments and votes exist, while a complete reporting/enforcement/appeal path and accountable authority are not established. WZP-PUB-02 must not imply platform-wide authority for community roles.
- **Existing position:** Default-deny and scoped authority are established principles. The Project Owner’s 2026-11-09 decision approves a bounded content-only public Writ reporting capability within WZP-TRUST-01, independently of WZP-PUB-02, subject to formal package/dependency change control. Discussion, comments, communities, and interaction moderation remain excluded from B.
- **Options supported by evidence:** Approve only the minimum role/action matrix needed for the selected scope; defer communities or actions whose moderation route is not ready. No role names or staff are assumed.
- **Implications:** Expanding participation without a safe report and response route increases user-safety and operational risk.
- **Dependencies:** D01-D02; formal change control for package/dependency records; Trust/Safety, security, privacy/legal and operations review as applicable.
- **Decision authority:** Project Owner decision dated 2026-11-09. Trust/Safety and security/IAM operational owners remain **UNASSIGNED**.
- **Required approval:** Content-only scope approved with conditions; operational owners and qualified reviewers must accept and operate their control domains before beta.
- **Status:** **APPROVED WITH CONDITIONS**.
- **Approved content-only scope:** User-accessible report action on public Writs; restricted report receipt/access; categorization and triage; urgent safety/spam escalation; authorized review/action; outcome/evidence recording; suitable communication to the reporter; support handoff; and defined access, retention, and audit arrangements. Preferred design is an in-product Writ report action and restricted operator workflow. It must be staffed, operational, and evidenced before beta invitations. No new package ID is authorized.
- **Remaining evidence:** The package/dependency records are aligned under CR-WZP-2026-11-09-01. This decision does not authorize implementation; operational ownership and pre-beta process evidence remain outstanding. Interaction reporting/moderation remains conditional on the separately selected PUB-02 journeys and safety path.

### WZP01-D04 — Product outcomes, metrics and telemetry

- **Description:** Define how the first scope will be judged useful and what product telemetry or feedback may be collected.
- **Why required:** The roadmap prohibits invented targets; beta acceptance needs measurable or rubric-based outcomes and privacy constraints.
- **Existing position:** Product value should reflect knowledge usefulness, trust, accessibility and time well spent, not engagement alone.
- **Options supported by evidence:** Use reviewed qualitative task rubrics; approve numeric thresholds only with source, measurement method and owner; omit telemetry not needed for the decision.
- **Implications:** Targets affect tests and beta operations. Telemetry affects privacy, consent, minimization and retention.
- **Dependencies:** D01-D02 and privacy review if personal or behavioral data is collected.
- **Proposed decision owner:** Product owner — **UNASSIGNED**.
- **Required approval:** The Project Owner approved the qualitative baseline B approach. Privacy/legal review remains required before optional telemetry or other data collection is proposed.
- **Decision authority:** Project Owner, per the consolidated decision dated 2026-11-09.
- **Status:** **APPROVED** (exact Project Owner disposition).
- **Approved acceptance approach:** Use a qualitative task-based rubric assessing account/profile task completion; Writ creation/editing/reading; basic discovery usefulness; report accessibility/response; feedback/error clarity; accessibility/responsive usability; and safe unsuccessful-operation handling. Behavioural analytics and engagement-driven tracking are excluded from baseline B. Optional telemetry remains disabled unless its purpose, privacy assessment, access policy, and retention basis are separately approved. Numeric targets are optional later and are not required for initial B acceptance.
- **Remaining evidence:** Record the rubric in the versioned B acceptance inventory and produce test/review evidence for each selected journey. No telemetry collection is authorized by this decision.

### WZP01-D05 — Accountable owners, delivery roles and decision rights

- **Description:** Appoint role holders for the selected packages and set decision boundaries.
- **Why required:** The roadmap leaves selected-package accountable owners, delivery assignments, independent verifiers and approvers unassigned; safe activation and independent review require appointments.
- **Existing position:** The Project Owner Decision Sheet confirms the Project Owner authority. It does not identify the individual or appoint specialist roles; product, technical, database, security, QA, verification, Trust/Safety, privacy/legal and operations appointments remain outstanding.
- **Options supported by evidence:** Appoint a person to each required role or retain an explicit package blocker. Combine roles only where the package's independent-review rule is not weakened; the implementer cannot verify their own security/privacy/data-integrity/release work.
- **Implications:** No accountable assignment means no safe package start, review or acceptance.
- **Dependencies:** D01; selected package inventory.
- **Proposed decision owner:** Confirmed Project Owner authority; individual identity not recorded in the decision sheet.
- **Required approval:** Project owner; domain authority for domain-specific approvers.
- **Status:** **APPROVED WITH EXPLICIT APPOINTMENT BLOCKERS** (exact Project Owner disposition).
- **Appointment state:** No individual is appointed. Product decision authority, technical architecture/PHP delivery, database/migration, QA, independent verification, security, Trust/Safety, privacy/legal where applicable, and support/operations remain **UNASSIGNED**. The Project Owner retains overall programme decision authority; no specialist authority may be inferred from prior contribution, title, or repository access.
- **Closure evidence:** Record each unassigned role as an explicit blocker against the packages it gates, with accountable owner, delivery, verifier, and approver assignments recorded when appointed. D05's ordinary WZP-02 activation rule requires a delivery owner and independent reviewer. WZP-01.8 later authorizes only an initial local, read-only verification without those appointments; they remain required for independent verification, WZP-02 exit, and follow-on work. Support, Trust/Safety, and applicable security/privacy responsibilities must be operational before beta.

### WZP01-D06 — Schema snapshot and migration authority

- **Description:** Resolve the roles of the documented schema snapshot, migration baseline and migration history without conflating desired schema with actual environment state.
- **Why required:** AGENTS.md names live-schema.sql as documented canonical schema authority, but its README calls it a historical reference rather than an executable baseline; the architecture authority says migration reconciliation is still open.
- **Existing position:** The snapshot is the documented canonical/reference artifact under AGENTS.md; it is not an executable migration. The root baseline is executable migration code but is not proven to match any current database.
- **Options supported by evidence:** Keep the snapshot as the documented reference while separately approving a reconciled executable migration chain; or approve a different version-controlled schema authority after technical review. Do not apply the snapshot or infer live state.
- **Implications:** The choice controls future schema reviews, migrations, verification and rollback. Declaring one file “canonical” without defining its role would preserve ambiguity.
- **Dependencies:** D05; WZP-02 static migration/snapshot reconciliation; database owner appointment.
- **Proposed decision owner:** Database/migration lead — **UNASSIGNED**.
- **Required approval:** Project Owner approval recorded 2026-11-09. A database/data authority remains necessary for source/migration reconciliation and any later semantic decision.
- **Status:** **APPROVED** (exact Project Owner disposition).
- **Approved distinction:** `database/schema/live-schema.sql` is the documented schema reference required by repository governance; the migration chain is the separately governed executable schema history. The reference snapshot is not an executable migration.
- **Remaining evidence:** WZP-02 must reconcile repository source and migration history. Neither artifact is asserted to match a live database; no database access or migration is authorized.

### WZP01-D07 — Resolution authority for known schema differences

- **Description:** Determine intended semantics for users.handle nullability, notifications.actor_id nullability and the notifications actor index naming difference.
- **Why required:** The snapshot and root baseline disagree. Choosing either version by convenience could alter data or conceal drift.
- **Existing position:** Differences are documented in the readiness report; actual local, Hostinger and production states have not been verified in this package.
- **Options supported by evidence:** Approve snapshot semantics, baseline semantics, or an explicitly justified third state after source/history evidence. No choice is made here.
- **Implications:** Nullability changes affect data validation and writes; index name may affect migration idempotency but is not by itself semantic equivalence.
- **Dependencies:** D05-D06; WZP-02 static inventory. Database inspection requires separate authorization and an approved disposable target.
- **Proposed decision owner:** Database/migration lead with application data owner — **UNASSIGNED**.
- **Required approval:** Project owner and relevant data/domain owner; security approval for any test target.
- **Status:** **EXPLICIT BLOCKER RECORDED** (exact Project Owner disposition).
- **Blocker:** The `users.handle` nullability, `notifications.actor_id` nullability, and notifications actor-index differences remain unresolved. No preferred database value is approved. Database/migration resolver and data owner remain unappointed.
- **Required evidence before resolution:** Table-by-table comparison, repository history and migrations, application write-path review, and documented rationale for each semantic. No live database access is authorized; schema changes and adoption decisions remain blocked.

### WZP01-D08 — Test database identity and fail-closed target policy

- **Description:** Decide how tests prove host, port, database name and account are an approved disposable target before opening a connection.
- **Why required:** The existing DB-backed regression reads environment-provided identity; the repository does not prove an independent code-controlled target allowlist or current identity validation.
- **Existing position:** Database writes require explicit approval; the roadmap requires a separately approved disposable target and independent identity validation. Environment configuration alone must not authorize a target.
- **Options supported by evidence:** Implement a code-controlled allowlist plus fail-closed exact identity checks before connections; otherwise prohibit DB-backed tests until policy and identity are verified. Reuse of production credentials/targets is excluded.
- **Implications:** A test target needs independent identity, dedicated least-privilege account, isolated storage and safe test-owned cleanup. No credentials or identity values are recorded here.
- **Dependencies:** D05-D07; security and database owner appointments; separately approved disposable environment.
- **Proposed decision owners:** Security owner and database/migration lead — **UNASSIGNED**.
- **Required approval:** Project Owner policy approval recorded 2026-11-09. Security review and independent verification are required for implementation before database-backed testing.
- **Status:** **APPROVED** (exact Project Owner disposition).
- **Approved policy:** A code-controlled allowlist must fail closed. Before any connection or database-related side effect, host, port, database name, and account must match the approved code-controlled identity. Environment configuration alone cannot authorize a target. Missing, unexpected, or mismatched identity must stop execution.
- **Remaining evidence:** Implement and independently verify this policy before database-backed testing is authorized. This decision does not approve a database identity, connection, migration, or database operation.

### WZP01-D09 — Regression runner side-effect isolation

- **Description:** Decide how to make regression execution safe and reproducible.
- **Why required:** scripts/test/run.sh includes WritPublicationEventRegression.php, which opens PDO and creates/deletes records; ErrorHandlerRegression.php deletes a shared log; RateLimitMiddlewareRegression.php writes/removes shared rate-limit state. RateLimitRouterRegression.php also deletes shared files and is outside the aggregate runner.
- **Existing position:** Repository tests are standalone scripts. WZP-02 requires relevant isolated evidence; the current aggregate cannot be assumed safe.
- **Options supported by evidence:** Split pure tests from integration tests; give each test unique temporary storage and explicit cleanup ownership; keep DB-backed cases disabled until target identity validation passes. A safe runner must not depend on a clean working tree to avoid deleting user work.
- **Implications:** Isolation may require test harness/config changes in WZP-02; a green aggregate result without resource isolation is not acceptable evidence.
- **Dependencies:** D05, D08 and PHP/Composer environment readiness.
- **Proposed decision owner:** Test/quality lead — **UNASSIGNED**.
- **Required approval:** Project Owner policy approval recorded 2026-11-09. QA/security review and independent verification are required for implementation; the decision does not authorize test execution.
- **Status:** **APPROVED** (exact Project Owner disposition).
- **Approved policy:** Pure isolated tests are the default; database integration tests require explicit invocation and separate authorization; file-writing tests use test-owned storage; logs, sessions, rate-limit and shared resources are isolated; cleanup removes only test-owned resources. The aggregate runner is not a safe default until database/shared-storage side effects are contained and independently verified.
- **Remaining evidence:** Document the invocation matrix and implement/verify isolation in a separately authorized engineering package. No test execution is authorized by this decision.

### WZP01-D10 — PHP development and validation environment

- **Description:** Establish an accessible PHP 8.2+ and Composer environment for syntax, isolated PHP regression and project validation.
- **Why required:** The readiness assessment could not find php or Composer in Windows PowerShell; the approved WSL environment check returned E_ACCESSDENIED.
- **Existing position:** composer.json requires PHP ^8.2 and dotenv. The repository guidance expects Composer and PHP syntax/regression checks. Current local environment readiness was not established.
- **Options supported by evidence:** Restore access to the already provisioned Ubuntu WSL environment and verify PHP/Composer/PDO MySQL; or obtain separate approval for another compatible local environment. No system installation is authorized here.
- **Implications:** Without a working runtime, PHP implementation and meaningful test evidence cannot proceed. Composer or PHP version mismatch could invalidate checks.
- **Dependencies:** WZP-02 testing; no dependency for static documentation review.
- **Proposed decision owner:** Engineering/platform lead — **UNASSIGNED**.
- **Required approval:** Project owner for environment changes; operations/security owner for any privileged provisioning.
- **Status:** **EXPLICIT BLOCKER RECORDED** (exact Project Owner disposition).
- **Blocker:** PHP/Composer environment verification owner remains unappointed. The next permitted activity is read-only verification of the existing local/WSL environment by an assigned engineering/platform owner, establishing PHP 8.2+, Composer, required extensions, and repository access.
- **Boundary:** No installation, reconfiguration, software provisioning, or hosting change is authorized. If the existing environment cannot be verified, seek separate approval. PHP checks remain blocked until the environment is verified.

### WZP01-D11 — Notification read-state behavior

- **Description:** Approve the notification journey while restoring safe HTTP semantics.
- **Why required:** GET /notifications invokes markAllRead, so opening the page changes state.
- **Existing position:** This is a source-verified defect; the roadmap includes notification stabilization where applicable. Existing security guidance requires CSRF coverage for state changes.
- **Options supported by evidence:** Make GET display current state only; offer an explicit state-changing action through an authenticated, CSRF-protected and rate-limited POST. Product may select bulk “mark all read” or item-level read actions.
- **Implications:** Bulk versus per-item behavior affects view controls and test cases. Both must be user-scoped and must not let one user mutate another user's notifications.
- **Dependencies:** D01-D03 and D05; WZP-02 safe test setup.
- **Applicability:** This decision is not a baseline B release prerequisite while notifications remain excluded; resolve it before any separately approved notification implementation.
- **Proposed decision owner:** Public product owner — **UNASSIGNED**.
- **Required approval:** Project owner; security reviewer for authorization/CSRF behavior.
- **Status:** **RECOMMENDED — AWAITING APPROVAL**.
- **Recommendation:** GET remains read-only; approve the explicit POST action and choose bulk or item-level UX before implementation.
- **Closure evidence:** Approved journey/route behavior, method/auth/CSRF/rate-limit matrix, user-isolation cases and regression plan.

### WZP01-D12 — Bookmark route identifier contract

- **Description:** Align bookmark route matching with the public Writ identifier used by the controller/model.
- **Why required:** The route constraint accepts numeric segments while BookmarkController passes the value to Writ::findByPublicId.
- **Existing position:** Public identifiers are used by the Writ lookup; the route mismatch is source verified. The schema snapshot records public_id as CHAR(7), but live/current data is not verified here.
- **Options supported by evidence:** Route by the public identifier and validate against its approved generator/format; do not silently switch to internal database IDs unless the public contract is deliberately changed.
- **Implications:** Identifier-format changes are externally visible. The exact accepted format must be derived from reconciled source/schema/generator evidence, not assumed from one snapshot alone.
- **Dependencies:** D01-D02, D05-D07; WZP-02.
- **Applicability:** This decision is not a baseline B release prerequisite while bookmarks remain excluded; resolve it before any separately approved bookmark implementation.
- **Proposed decision owner:** Application engineering lead — **UNASSIGNED**.
- **Required approval:** Product owner for URL behavior; security reviewer for authorization and enumeration implications.
- **Status:** **RECOMMENDED — AWAITING APPROVAL**.
- **Recommendation:** Keep public_id as route identifier and align route validation with the actual generator/lookup contract after WZP-02 source reconciliation.
- **Closure evidence:** Approved URL contract, source/generator format evidence, positive/negative route tests and authorization regression.

### WZP01-D13 — Security and regression acceptance for first correction

- **Description:** Approve verification evidence required for notification and bookmark corrections.
- **Why required:** These paths touch request methods, sessions, authorization and user-owned records.
- **Existing position:** AGENTS.md requires relevant PHP syntax, regression, security and user-journey verification. A reviewed diff and independent review are required by the roadmap package process.
- **Options supported by evidence:** Run isolated route/controller tests and the relevant user journey; add DB-backed cases only against the approved fail-closed disposable target. Do not use aggregate success as a substitute.
- **Implications:** Acceptance evidence must cover allowed and denied paths, invalid identifiers, state preservation, CSRF and user scoping. The author cannot independently verify security findings.
- **Dependencies:** D05, D08-D12; WZP-02.
- **Applicability:** Notification/bookmark-specific acceptance is required only if either excluded capability receives separate implementation approval; baseline B remains unaffected while both are excluded.
- **Proposed decision owners:** Test/quality lead and security reviewer — **UNASSIGNED**.
- **Required approval:** Project owner; security authority for auth/session/CSRF changes.
- **Status:** **RECOMMENDED — AWAITING APPROVAL**.
- **Closure evidence:** Approved test matrix, exact commands and outcomes, syntax/security/user-journey evidence, reviewed diff and independent verifier identity.

### WZP01-D14 — Release and implementation approval boundary

- **Description:** Preserve separation between decision closure, package implementation approval, GitHub release authority and beta/launch approval.
- **Why required:** This package must not silently authorize WZP-02 implementation or runtime corrections.
- **Existing position:** GitHub remains the release authority; roadmap beta and launch require separate written project-owner approval; WZP-01 does not authorize implementation.
- **Options supported by evidence:** Maintain separate approvals for WZP-02, each runtime package, beta, and production/launch.
- **Implications:** Documentation or decision acceptance alone does not establish release readiness.
- **Dependencies:** None; governing baseline.
- **Proposed owner:** Confirmed Project Owner authority for scope/package/beta/launch decisions; individual identity not recorded. GitHub is the release authority; no operator is appointed here.
- **Required approval:** Project owner for each explicit approval; repository release governance for release activity.
- **Status:** **APPROVED BASELINE**.
- **Closure evidence:** Each approval is recorded against exact scope and Git state; no approval is implied by this document.

## Existing decision-record limitations

The architecture authority names docs/Decisions as the intended ADR location and treats decisions/ as secondary until reconciled. The inspected ADR files under docs/Decisions are empty structural placeholders. decisions/AJ-0001.md and AJ-0002.md contain architectural-journal prose about the engine layer and signal/ranking separation; AJ-0003.md is empty. They are useful context but are not treated as formal approval evidence for WZP-01 choices.

## Current disposition

D01 remains **APPROVED WITH CONDITIONS**. The original 2026-11-09 consolidated Project Owner decision records WZP01-D02–D06 and WZP01-D08–D09 as approved within their stated boundaries, records explicit blockers for WZP01-D07 and WZP01-D10, and leaves WZP01-D11–D13 pending while their capabilities remain excluded. D14 remains **APPROVED BASELINE**. WZP-01.6 records approval of PO-01–PO-07 without reopening D01–D14. WZP-01.8 records WZP-01 **CLOSED WITH CONDITIONS** and the bounded initial WZP-02 authorization. Specialist appointments remain unassigned; D07 schema semantics, D08/D09 test-safety implementation, and D10 environment access are not resolved. WZP-02 is **READY FOR CONTROLLED ACTIVATION** only for the initial local, read-only inspection; its independent reviewer and full exit evidence remain outstanding. No database access, migration, unsafe test execution, runtime correction, commit, beta invitation, production launch, or release action is authorized by this register.

## WZP-01.6 Project Owner baseline dispositions

The [WZP-01.6 decision](WZP-01.6-PROJECT-OWNER-CONSOLIDATED-BASELINE-DECISION.md) accepts the exact WZP-01.4 scope inventory and Baseline B Acceptance Specification contents identified by SHA-256 in that record. The accepted artifacts are for closure review; Baseline B remains unfrozen.

| Proposal | Exact Project Owner disposition | Recorded boundary |
|---|---|---|
| PO-01 — Candidate documentation | **APPROVED WITH CONDITIONS** | Accepted for WZP-01 closure review; revision identity recorded; no B freeze or implementation approval. |
| PO-02 — Profile and Writ visibility | **APPROVED** | Public handle and approved display name; public author attribution; public-on-publish Writs; no private/unlisted mode, follow controls, or follower counts in B. |
| PO-03 — Account recovery | **APPROVED WITH CONDITIONS** | No self-service reset in B or promise of unavailable recovery; appointed support owner must approve any assisted process before beta. |
| PO-04 — Writ text limit | **APPROVED WITH TECHNICAL CONDITION** | 1,000 UTF-8 Unicode code points after trimming; verify storage, validation path, and database constraints before implementation. |
| PO-05 — Keyword discovery | **APPROVED** | Public users, public Writs, and hashtags; Writs newest-first; non-AI keyword search without personalization. The fixed qualitative query set from the approved WZP-01.5 option remains to be documented as acceptance evidence. Mandatory WZP-PUB-03 → WZP-API-01 edge unchanged; no API implementation authorized. |
| PO-06 — FeedInspector | **APPROVED FOR EXCLUSION** | Exclude the user-visible FeedInspector/Intelligence panel from B. |
| PO-07 — Accessibility/package boundary | **APPROVED WITH CONDITIONS** | Minimum observable accessibility and responsive-browser acceptance cross-cut WZP-PUB-01; document browser/viewport matrix; full multilingual/global and native-platform requirements remain outside B; no dependency-edge changes without formal change control. |

These dispositions resolve the seven Project Owner choices raised by WZP-01.5. They do not resolve remaining technical conditions, make specialist appointments, freeze B, close WZP-01, or activate WZP-02.
