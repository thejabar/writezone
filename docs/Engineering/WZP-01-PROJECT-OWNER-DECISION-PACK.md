# WZP-01 Project Owner Decision Pack

**Programme:** WZ-MASTER-ROADMAP-2027.2
**Prepared:** 2026-11-09
**Status:** Historical pre-decision instrument; Project Owner decision recorded 2026-11-09
**Authority:** WZP-01 decision register, dependency and exit criteria, D01 decision, and canonical roadmap

> **Supersession notice:** The Project Owner issued the [consolidated WZP-01 decision dated 2026-11-09](WZP-01-PROJECT-OWNER-CONSOLIDATED-DECISION.md). That decision controls over the pre-decision recommendations and approval wording below. This pack is retained as the decision-preparation record; use the [current closure-readiness report](WZP-01-CLOSURE-READINESS-REPORT.md) and decision register for present status. WZP-01 remains open, WZP-02 remains blocked, and implementation, beta, and launch remain unauthorized.

## 1. Executive decision summary

D01 remains **APPROVED WITH CONDITIONS**. The Project Owner has since recorded decisions for WZP01-D02–D10: B journey boundaries and the qualitative rubric are approved; content-only reporting is approved with conditions; test-target and test-invocation policies are approved; specialist appointments and the D07/D10 matters remain explicit blockers. B is not frozen, WZP-01 remains **OPEN / AWAITING OWNER DECISION** pending closure evidence, and WZP-02 remains **BLOCKED**.

The next checkpoint is now to record and verify closure evidence: versioned inventories and accepted boundaries; content-reporting package/dependency change control; explicit appointment blockers; D07/D10 blocker entries; and approved policy text. Notification and bookmark decisions D11-D13 remain pending while those features stay excluded from B. Exact beta operating values may remain open until before Gate 2/WZP-BETA-01 under the owner-approved timing decision.

The Project Owner has approved a bounded content-only Writ reporting capability within WZP-TRUST-01, independently of WZP-PUB-02, with formal package/dependency reconciliation required. The operational process and evidence remain mandatory before beta. This pack itself does not modify package statuses.

## 2. Confirmed decisions that should not be reopened without cause

- **WZP01-D01 — APPROVED WITH CONDITIONS (2026-11-08):** A is the long-term Knowledge Network / Knowledge Hub direction. B is a website-first, content-first candidate: public account/session/basic profile; text Writ publishing, reading and editing; basic non-AI discovery; responsive desktop and mobile-browser experience; and minimum operational, security, privacy and recovery controls. B is not frozen.
- D01 excludes bookmarks, comments/replies/votes/follows, Q&A, communities, notifications, public API implementation, native applications, AI recommendations, corporate administration and behavioural analytics from baseline B. No such capability becomes required through this pack.
- C is a separate inventory to be frozen after beta evidence, issue resolution, acceptance and Gate 3; there is no automatic B-to-C promotion.
- Public content cannot proceed to beta invitations until a content-reporting and response process is approved and operational.
- **WZP01-D14 — APPROVED BASELINE:** decision approval, implementation approval, GitHub release authority, beta approval and production launch approval are separate.
- WZP-02 exit evidence is required before beta, but it is not a WZP-01 closure prerequisite. No beta invitation or production launch is authorized.

## 3. Pre-decision recommendations (superseded where addressed by the 2026-11-09 decision)

### WZP01-D02 — Select baseline B journeys and recovery behavior

**Question:** Which exact account, profile, writing, reading and discovery journeys belong in the versioned B candidate, and what happens when authoring or publishing fails?

**Recommendation:** Keep the D01 capability boundary. Define account/session/basic-profile journeys; public text Writ create, read and edit; responsive reading/authoring; and non-AI search. Decide explicitly whether B needs a durable “save draft/resume” journey. The current application publishes immediately and has no established draft/recovery flow. Do not claim recovery exists. Define server-side validation, ownership, public visibility, error preservation, retry and deletion/removal behavior before package implementation. Do not include social interactions as a workaround for missing journeys.

**Alternative:** Keep durable drafts and author-controlled deletion outside B, and approve a narrower, explicit recovery contract for text submission and editing. This reduces scope but must not promise recovery the application cannot provide.

**Consequence:** A draft/resume choice may affect persistence/schema and must wait for WZP-02 reconciliation. D01 does not specifically include permanent deletion; decide whether it is excluded or explicitly accepted with privacy, safety and recovery rules.

- **Required for WZP-01 closure:** Yes, selected journeys and observable outcomes must be recorded.
- **Can be deferred safely:** Journeys for excluded capabilities can be deferred; B journeys cannot.

### WZP01-D03 — Close content-only reporting coverage

**Question:** What approved process lets a beta user report a public Writ and ensures accountable triage and response when comments and communities are excluded?

**Recommendation:** Direct an approved scope/dependency adjustment to WZP-TRUST-01: provide a bounded content-only reporting slice independently of WZP-PUB-02, with restricted intake, triage, action, escalation, case outcome and evidence. Keep discussion/community moderation integration conditional on PUB-02. Prefer a user-visible report action on each public Writ with a restricted operator queue; a Project Owner-approved, fully staffed manual channel is a possible alternative if it supplies equivalent intake and evidence. Do not create a new package ID without change control.

At minimum, the approved process must identify the report channel, information collected, receiving role, triage categories, urgent safety/spam escalation, authorized actions, user communication, support handoff, record access/retention authority, audit evidence and coverage hours/response target. No targets, legal duties or retention periods are assumed here.

**Alternative:** Keep TRUST-01 dependent on PUB-02 and delay public beta until a separately approved reporting process is operational. This preserves the current package graph but delays B.

**Consequence:** The current source has no report route or established report workflow. Until an approved channel is operational and exercised, public publishing is not beta-ready.

- **Required for WZP-01 closure:** The reporting authority and package coverage decision are required; operational process and evidence are required before beta.
- **Can be deferred safely:** No, not for a beta that permits public publishing.

### WZP01-D04 — Approve useful outcomes and telemetry boundaries

**Question:** How will B demonstrate knowledge value, and may it collect product telemetry or feedback?

**Recommendation:** Start with a qualitative, task-based rubric for completing account, write/edit/read, search and reporting tasks; assess usefulness, clarity, accessibility, safe failure and time well spent. Keep behavioural analytics and optional telemetry off by default. Collect only feedback necessary to evaluate the approved rubric, with purpose, access, consent, minimization and retention reviewed by the appropriate owner. Set numeric targets only after the owner approves the measure and evidence method.

**Alternative:** Approve a small numeric target set with an evidence source, measurement method and privacy review. Do not use engagement or time-on-site alone as success measures.

- **Required for WZP-01 closure:** An approved qualitative rubric or complete numeric-target definition is required by the current exit criteria.
- **Can be deferred safely:** Unneeded telemetry and behavioural analytics can be excluded; acceptance outcomes for B cannot.

### WZP01-D05 — Appoint required roles or record blockers

**Question:** Who is accountable for each selected package and its independent review?

**Recommendation:** Record role-to-person assignments, decision limits and independence conflicts for the first selected scope. Where no appointment is made, record an explicit blocker instead of assuming a previous contributor or the requester holds the role.

- **Required for WZP-01 closure:** Each selected package must have accountable, delivery, verifier and approver roles appointed or an explicit blocker.
- **Required before WZP-02 work:** WZP-02 delivery owner and independent reviewer must be assigned before package activation.
- **Required before beta:** Support/operations, Trust/Safety, and applicable security/privacy owners must be assigned and able to operate the agreed process.

### WZP01-D06 — Confirm schema-reference and migration-history roles

**Question:** What is the documented reference, and what controls executable schema history?

**Recommendation:** Approve the existing recorded distinction: `database/schema/live-schema.sql` remains the documented schema reference required by AGENTS.md; it is not an executable migration. The migration chain is separately governed executable history, subject to WZP-02 reconciliation. This does not assert that either matches a live database.

**Alternative:** Approve another versioned schema authority after the database owner reconciles the repository documents and migration history.

- **Required for WZP-01 closure:** The artifact roles must be unambiguous under the current exit criteria.
- **Required before schema implementation:** Reconciled executable history and approved migration/adoption path. No database state was checked here.

### WZP01-D07 — Assign resolution of documented schema differences

**Question:** Who will resolve the `users.handle` and `notifications.actor_id` nullability and actor-index differences, and what evidence will govern the choice?

**Recommendation:** Appoint a database/migration resolver and data owner; require a table-by-table source/migration comparison, application write-path review and rationale before choosing semantics. Do not select snapshot or migration values by convenience. Live state is a separate verification and approval.

- **Required for WZP-01 closure:** Name the resolver and evidence plan, or record a blocker; the current exit criteria do not require choosing semantics now.
- **Required before schema changes:** Resolve each affected semantic and obtain separate approval.

### WZP01-D08 and WZP01-D09 — Approve test safety policy

**Question:** What policy must be satisfied before tests can connect to a database or write storage?

**Recommendation:** Approve a code-controlled database target allowlist; fail closed unless host, port, database name and account all match one approved identity before connection or side effects. Environment values must not authorize a target. Classify tests by side effect, make safe isolated tests the default, isolate logs/session/rate-limit and temporary storage, and allow only test-owned cleanup. Keep DB integration explicit and separately gated.

- **Required for WZP-01 closure:** The safe default invocation and target policy must be recorded.
- **Required before WZP-02 database tests:** Identity policy must be implemented and the disposable identity independently re-verified before connection.
- **Required before implementation:** Relevant tests must be isolated; do not run the current aggregate runner as a substitute.

### WZP01-D10 — Assign PHP/Composer environment verification

**Question:** Which approved local environment will provide PHP 8.2+, Composer and required extensions, and who verifies access?

**Recommendation:** First attempt read-only verification of the already provisioned WSL environment by an assigned engineering/platform owner. If inaccessible or incomplete, record the blocker and seek separate environment-provisioning approval; do not install or reconfigure software through this documentation decision.

- **Required for WZP-01 closure:** Record the environment owner and read-only verification plan, or a named blocker.
- **Required before WZP-02 PHP checks:** Verify the interpreter, Composer, extensions and repository access. Prior assessment reported WSL access denied; this task did not re-test it.

### Beta operating boundaries

**Question:** Must geography, eligibility, duration, notices and support limits be fixed to close WZP-01, or can their exact values remain pre-beta gates?

**Recommendation:** Record that these values are required before Gate 2/BETA-01 and beta invitations, but allow them to remain open during WZP-01 closure if the Project Owner explicitly accepts this timing and assigns owners. Current documents consistently make them pre-beta conditions, while the WZP-01 dependency wording also refers generally to closing beta boundaries; record the owner's interpretation rather than assuming it.

## 4. Baseline B acceptance boundary

### Public-content reporting

Source and roadmap evidence establish no report route or runtime reporting process. WZP-TRUST-01 covers reporting, triage, moderation, appeals and evidence, but its current mandatory dependency and integration acceptance require WZP-PUB-02. The current source therefore does not establish coverage for D01's content-only beta condition. The Project Owner must approve the package boundary or defer beta; no package status changes in this pack.

### Publishing, editing, reading and recovery

| Journey area | Current evidence | Decision/acceptance still needed |
|---|---|---|
| Account/profile prerequisites | Registration, sign-in and public profile routes exist. Registration lacks substantive server-side validation; login messages distinguish unknown accounts from bad passwords. | Confirm fields, profile visibility and account/session recovery; require server-side validation and non-enumerating failure behavior in an approved implementation package. Keep consumer identity separate from corporate identity. |
| Draft and recovery | The current create form posts directly to publish; no durable draft/resume flow was established. | Decide whether B includes explicit save/resume or a narrower recovery contract. Do not claim recovery until it is implemented and tested. |
| Publishing | Text Writ create route, controller and publisher exist; the form has a browser maxlength, but server-side validation policy is not established. | Approve content length/validation, public-state disclosure, empty/invalid/error/retry behavior and abuse response. |
| Editing and visibility | Owner-checked edit route exists; no explicit audience/visibility workflow was established in the reviewed path. | Decide edit publication behavior, visibility default, history/recovery expectations and whether hard deletion is excluded. |
| Reading | Public feed/detail and profile Writ lists exist. | Approve reading layout, attribution, empty/not-found states, responsive/accessibility cases and behavior when content is removed or reported. |
| Authorization | Writ update/delete routes invoke `WritPolicy` against the Writ object. | Preserve owner-only server-side authorization; test denied/cross-user paths under the approved safe harness. |

The source also exposes capabilities excluded from B: the navbar links notifications and bookmarks; the Writ detail view renders bookmark and comment controls; profile pages show follows and a Follow action; and routes remain registered for comments, votes, follows, bookmarks and notifications. The Writ list includes a local `FeedInspector` panel titled “Intelligence”; this is not evidence of a Python classifier, but its inclusion in B needs product review. WZP-02 must inventory how the approved exclusions will be hidden or unavailable in the eventual B release candidate; this pack does not prescribe or implement the code changes.

### Basic non-AI discovery

Current `/search` searches users, Writs and hashtags. Writ search uses keyword `LIKE`, newest-first ordering and a 20-row limit. This is not semantic classification, a curated Knowledge Graph or AI ranking. The D01 candidate can use basic non-AI discovery, but WZP-PUB-03's recorded scope also includes stable identifiers, metadata, explicit relationships, provenance and curated discovery, and depends on WZP-API-01 contract work. The Project Owner should choose whether B takes a bounded keyword-search slice or the full PUB-03 package scope. The recommendation is a bounded public-Writ keyword search with clear empty/error states and an owner-reviewed qualitative query rubric; defer semantic inference, personalization and graph claims. Preserve any mandatory PUB-03/API-01 dependency unless the owner approves a scope/dependency change.

## 5. Specialist appointments

The Project Owner's decision authority is confirmed; no individual is identified. No specialist is appointed by this pack.

| Role | Needed for | Current record |
|---|---|---|
| Product decision authority | Writ/profile/search journeys and acceptance | UNASSIGNED |
| Technical architecture / PHP delivery owner | WZP-02 source review and any later PHP package | UNASSIGNED |
| Database/migration owner | Snapshot/migration reconciliation and target policy | UNASSIGNED |
| Test/quality lead | Side-effect inventory and isolated invocation policy | UNASSIGNED |
| Independent verifier | Independent WZP-02 and later security/data-integrity review | UNASSIGNED |
| Security reviewer | Auth, authorization, CSRF, test-target policy and release boundary | UNASSIGNED |
| Trust/Safety authority | Content report, triage, action and escalation policy before beta | UNASSIGNED |
| Privacy/legal reviewer | Data use, notices, reporting records, consent and retention where applicable | UNASSIGNED |
| Support/operations owner | Beta intake, support coverage, incidents, monitoring and recovery | UNASSIGNED |

For WZP-01 closure, assign roles for the selected packages or record explicit blockers. The last four domain roles are required before beta whenever their responsibilities apply; a named role is not proof that the service is operational.

## 6. WZP-01 closure criteria

Use the existing [dependency and exit criteria](WZP-01-DEPENDENCY-AND-EXIT-CRITERIA.md); this pack does not revise them. Before declaring WZP-01 closed, record and approve:

1. Versioned A/B/C inventories with B clearly marked as a candidate until its later freeze; explicit includes, exclusions, conditions and dependencies.
2. Selected B journeys and failure/denial/recovery outcomes, including decisions D02-D04.
3. Content-only report coverage and Trust/Safety authority/package ownership, or a named blocker that keeps dependent B work blocked.
4. Each first-scope package's accountable owner, delivery owner, verifier and approver, or an explicit blocker; separation conflicts recorded.
5. Schema-reference versus executable-migration roles; a named resolver/evidence plan for known differences.
6. Approved default test-target and test-invocation policies; PHP/Composer environment verification owner and plan.
7. Decision register updated with authority, status, evidence and date; no recommendation marked approved without the owner's recorded decision.

Closure does not require D11-D13 while notifications and bookmarks remain excluded. WZP-02 exit evidence, WZP-OPS evidence, Gate 2 and beta authorization remain separate later gates.

## 7. WZP-02 entry dependencies

WZP-02 remains **BLOCKED**. Before its separately approved activation, require WZP-01 closure, exact read-only scope and local source identity, appointed technical owner and independent reviewer, and a test-side-effect inventory. Source-only static inspection does not require a database. Before any DB-backed check, require the independently verified disposable target, implemented fail-closed identity validation, isolated storage and safe test-owned cleanup. Before PHP checks, verify the approved PHP/Composer environment. Never run the aggregate runner until its database and shared-storage effects are contained.

WZP-02 must report source/snapshot/migration differences without claiming live-schema state. Its exit evidence is mandatory before beta, but it does not retrospectively become a WZP-01 closure prerequisite.

## 8. Items deferred beyond baseline B

- WZP01-D11 notifications, WZP01-D12 bookmarks and WZP01-D13 feature-specific testing/independent verification. Keep all pending while excluded; resolve before separate implementation or a later inventory includes them.
- Comments/replies/votes/follows, Q&A, communities, public API implementation, native applications, AI classification/recommendations, corporate administration and behavioural analytics.
- Detailed locale/country and mobile-app matrices, unless an approved B journey requires a related website-browser criterion.
- Exact beta geography, eligibility, cohort size, duration, notices and support limits are approved for deferral until before Gate 2/WZP-BETA-01 by the Project Owner decision dated 2026-11-09. They remain mandatory before any beta invitation, with accountable owners recorded.

## 9. Pre-decision approval wording (superseded; do not use as a new approval)

The following template was supplied before the Project Owner’s consolidated decision. The decision record linked above now controls; this wording is retained only to preserve the history of the decision process.

The Project Owner may copy and complete this wording; brackets require an explicit choice. Silence is not approval.

> I confirm that WZP01-D01 remains APPROVED WITH CONDITIONS and retain its baseline B inclusions and exclusions. I decide WZP01-D02/D03/D04 as follows: [accept or amend the recommended B journeys, content-only reporting package coverage, and qualitative acceptance/no-telemetry default]. I direct [the recommended WZP-TRUST-01 content-only scope/dependency adjustment / an explicitly described alternative], subject to change control and updated package records before implementation. I approve / amend the WZP01-D06/D08/D09 recommendations as recorded here: [decision]. For WZP01-D07, I assign [database/data resolver and evidence plan / explicit blocker]. For WZP01-D10, I assign [PHP/Composer environment verification owner and read-only plan / explicit blocker]. I assign the other required role holders or record these exact blockers: [role-to-person assignments or blockers]. I direct that beta geography, eligibility, duration, notices and support limits be resolved [for WZP-01 closure / before Gate 2 and WZP-BETA-01], with an accountable owner recorded. These decisions do not authorize runtime implementation, database operations, migrations, aggregate tests, beta invitations, production launch, or GitHub release activity. WZP-02 remains blocked unless separately approved after its entry conditions are evidenced.
