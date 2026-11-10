# WRITEZONE
# PROJECT OWNER CONSOLIDATED DECISION
## WZP-01 Decision Resolution and Closure Direction

**Decision date:** 9 November 2026
**Programme:** WZ-MASTER-ROADMAP-2027.2
**Decision authority:** Project Owner
**Decision scope:** WZP-01, baseline B governance, technical policy and closure direction

---

## 1. Formal decision

I, as Project Owner, confirm and establish the following decisions for WriteZone.

I retain the decision previously recorded under WZP01-D01 as APPROVED WITH CONDITIONS, dated 8 November 2026. I do not reopen or reverse that decision.

I approve the outstanding recommendations and decisions below, subject to the stated limitations, exclusions, evidence requirements and approval boundaries.

Where a specialist appointment cannot currently be made, I explicitly record the appointment as an outstanding blocker. No individual is to be assumed to hold an unassigned role.

This decision establishes the governance direction for WZP-01. It does not constitute implementation approval, database approval, migration approval, GitHub release approval, beta authorization or production launch approval.

## 2. Product scope and acceptance decisions

### WZP01-D02 — Baseline B journeys

**Decision: APPROVED WITH DEFINED BOUNDARIES**

I approve the following baseline B candidate journeys:

1. Public account registration and authentication.
2. Basic public user profiles.
3. Text Writ creation and publishing.
4. Public Writ reading.
5. Authorised Writ editing.
6. Basic non-AI keyword-based discovery.
7. Responsive desktop and mobile-browser experiences.

The following acceptance boundaries apply:

- Registration and authentication must have appropriate server-side validation and safe error responses.
- Publishing and editing must enforce server-side validation and ownership.
- Public content must have a clearly defined visibility model.
- Validation and publishing failures must provide understandable feedback.
- Recoverable failures should preserve submitted content where technically safe.
- Retrying a publishing operation must not unintentionally create duplicate Writs.
- Reading and discovery must have defined empty, unavailable, invalid and error states.
- Accessibility and responsive behaviour must form part of acceptance.

**Draft policy:** Durable draft storage, save/resume and a full draft-management system are excluded from baseline B unless separately approved after WZP-02 evidence.

**Deletion policy:** Permanent Writ deletion is excluded from baseline B. Any later deletion capability requires separate decisions covering privacy, moderation, recovery, ownership and audit implications.

These decisions do not claim that the listed journeys already meet acceptance criteria. Implementation and verification remain future authorised work.

### WZP01-D03 — Content-only reporting and safety

**Decision: APPROVED WITH CONDITIONS**

I approve a bounded content-only reporting capability within WZP-TRUST-01, independently of WZP-PUB-02.

The existing dependency and package records must be reconciled through formal change control. No new package ID is authorised by this decision.

The approved scope must cover:

- A user-accessible way to report a public Writ.
- Restricted receipt and access to reports.
- Report categorisation and triage.
- Urgent safety and spam escalation.
- Defined authority to review and take action.
- Case outcome and evidence recording.
- Appropriate communication with the reporting user.
- Support handoff and operational responsibility.
- Defined access, retention and audit arrangements.

The preferred design is an in-product reporting action on public Writs with a restricted operator workflow.

The process must be operational and evidenced before beta invitations. A proposed interface or an unstaffed reporting queue does not satisfy this condition.

Discussion, comments, communities and interaction moderation remain excluded from baseline B.

### WZP01-D04 — Product outcomes and measurement

**Decision: APPROVED**

Baseline B will use a qualitative, task-based acceptance rubric.

The rubric must assess:

- Successful completion of account and profile tasks.
- Writ creation, editing and reading.
- Basic discovery usefulness.
- Reporting accessibility and response.
- Clarity of feedback and error handling.
- Accessibility and responsive usability.
- Safe handling of unsuccessful operations.

Behavioural analytics and engagement-driven tracking remain excluded from baseline B.

Optional telemetry must remain disabled unless a separate, documented purpose, privacy assessment, access policy and retention basis are approved.

Numeric success targets may be introduced later if a reliable measurement method is approved. They are not required for the initial qualitative acceptance approach.

## 3. Ownership and accountability decisions

### WZP01-D05 — Specialist appointments

**Decision: APPROVED WITH EXPLICIT APPOINTMENT BLOCKERS**

The following roles are required for the selected programme scope:

- Product decision authority.
- Technical architecture and PHP delivery owner.
- Database and migration owner.
- Test and quality lead.
- Independent verifier.
- Security reviewer.
- Trust and Safety authority.
- Privacy/legal reviewer, where applicable.
- Support and operations owner.

No individual is appointed by this decision.

The Project Owner retains overall programme decision authority. Specialist authority must not be inferred from prior contribution, job title or repository access.

For each selected package, the ownership record must identify its accountable owner, delivery owner, verifier and approver, or explicitly record the outstanding appointment as a blocker.

WZP-02 must not be activated until its delivery owner and independent reviewer are assigned.

Support, Trust/Safety and applicable security/privacy responsibilities must be assigned and operational before beta.

## 4. Technical governance decisions

### WZP01-D06 — Schema reference and migration authority

**Decision: APPROVED**

I approve the distinction between:

- `database/schema/live-schema.sql` as the documented schema reference required by the repository governance instructions.
- The migration chain as the separately governed executable schema history.

The schema reference must not be treated as an executable migration.

This decision does not establish that either artifact matches the live database. That remains subject to WZP-02 source reconciliation and separately authorised verification.

### WZP01-D07 — Schema differences

**Decision: EXPLICIT BLOCKER RECORDED**

The documented differences involving:

- `users.handle` nullability;
- `notifications.actor_id` nullability;
- the actor index definition/name;

must be resolved using repository history, application write paths and migration evidence.

No preferred database value is approved by this decision.

A database/migration resolver and data owner remain unappointed. This is an explicit blocker for schema changes and database adoption decisions.

The evidence plan must include a table-by-table comparison, migration history, application write-path review and a documented rationale for every resolution.

No live database access is authorised.

### WZP01-D08 — Database test-target policy

**Decision: APPROVED**

I approve a code-controlled, fail-closed database target policy.

Before any database connection or database-related side effect, the test environment must validate the approved:

- Host.
- Port.
- Database name.
- Database account.

All required values must match a code-controlled approved identity.

Environment configuration alone must never authorise a database target.

Any missing, unexpected or mismatched identity must stop execution before connection or side effects.

Implementation and independent verification of this policy are required before database-backed testing is authorised.

### WZP01-D09 — Test invocation and isolation policy

**Decision: APPROVED**

I approve the following testing policy:

1. Pure, isolated tests are the default.
2. Database integration tests require explicit invocation and separate authorization.
3. Tests that write files must use test-owned storage.
4. Logs, sessions, rate-limit state and other shared resources must be isolated.
5. Cleanup may remove only resources created and owned by the relevant test.
6. The current aggregate test runner must not be treated as a safe default until its database and shared-storage side effects are contained and independently verified.

The test policy must be documented before WZP-01 closure. Its technical implementation and verification belong to the appropriately authorised engineering package.

### WZP01-D10 — PHP and Composer environment

**Decision: EXPLICIT BLOCKER RECORDED**

A PHP/Composer environment verification owner remains unappointed.

The next permitted activity is a read-only verification of the existing local/WSL environment by an assigned engineering/platform owner.

The verification must establish PHP 8.2+, Composer, required extensions and repository access.

No installation, environment reconfiguration, software provisioning or hosting change is authorised through this decision.

If the existing environment cannot be verified, the issue must be reported for separate approval.

## 5. Deferred technical decisions

### WZP01-D11 — Notifications

**Decision: REMAINS PENDING**

Notifications remain excluded from baseline B.

The notification GET mutation finding remains documented. No notification implementation is authorised.

Resolve D11 only if notifications receive separate implementation approval or enter a later approved inventory.

### WZP01-D12 — Bookmarks

**Decision: REMAINS PENDING**

Bookmarks remain excluded from baseline B.

The public-identifier mismatch remains documented. No bookmark implementation or correction is authorised.

Resolve D12 only if bookmarks receive separate implementation approval or enter a later approved inventory.

### WZP01-D13 — Feature-specific assurance

**Decision: REMAINS PENDING**

Feature-specific regression, security and independent verification requirements for D11/D12 remain pending while those capabilities are excluded.

General engineering quality and security controls continue to apply to baseline B.

D13 becomes applicable when the relevant feature is separately approved or included in a later inventory.

### WZP01-D14 — Approval boundary

**Decision: APPROVED BASELINE, RETAINED**

The distinction between the following authorities remains mandatory:

- Project and scope decisions.
- Implementation approval.
- GitHub release authority.
- Beta authorization.
- Production launch authorization.

No approval in one category automatically authorises another.

## 6. Baseline B boundaries and exclusions

The approved candidate remains website-first and content-first.

The following remain excluded unless separately approved:

- Bookmarks.
- Comments, replies and voting.
- Follows and social interaction features.
- Q&A and communities.
- Notifications.
- Public API implementation, except where a separately approved dependency demonstrably requires it.
- Native Android and iOS applications.
- AI classification, semantic inference and recommendations.
- Corporate administration and workforce systems.
- Behavioural analytics.

Basic keyword search must not be represented as semantic discovery, AI search or a completed Knowledge Graph.

The Writ listing's local FeedInspector "Intelligence" panel must be assessed against the approved exclusions before the B release inventory is frozen.

The eventual baseline B inventory must explicitly identify included and excluded user-facing surfaces.

## 7. Beta operating boundaries

**Decision: APPROVED TO DEFER EXACT VALUES UNTIL BEFORE GATE 2 / BETA-01**

The following details may remain open during WZP-01 closure:

- Beta geography.
- Eligibility.
- Cohort size.
- Duration.
- User notices.
- Support limits and operating hours.

They must be decided, assigned to accountable owners and recorded before Gate 2/BETA-01 and before any beta invitation.

This deferral does not waive any beta safety, privacy, security, support or operational requirement.

## 8. WZP-01 closure direction

I direct that the decision register and ownership matrix be updated to reflect the decisions above.

WZP-01 may proceed towards closure only when its existing exit criteria have been evidenced, including:

1. The versioned A/B/C scope inventories and conditional baseline B boundaries.
2. The selected B journeys and observable acceptance outcomes.
3. The content-only reporting authority and package coverage decision.
4. Required package roles assigned or explicitly recorded as blockers.
5. Schema-reference and migration-history authority clearly documented.
6. Schema-difference resolver and evidence plan recorded, or explicit blocker maintained.
7. Database test-target and safe test-invocation policies approved and documented.
8. PHP/Composer environment verification owner and plan recorded, or explicit blocker maintained.
9. All decisions accurately recorded with evidence, authority and date.

WZP-01 must not be marked closed until its existing exit criteria are satisfied.

A recorded decision is not a substitute for evidence.

## 9. WZP-02 direction

WZP-02 remains BLOCKED until:

- WZP-01 is formally closed.
- WZP-02 receives separate Project Owner approval.
- Its exact read-only scope is established.
- A technical delivery owner and independent reviewer are assigned.
- Its test-side-effect inventory and safe execution boundaries are established.
- Any database-backed activity has separately satisfied the target-identity and test-isolation requirements.

Source-only static inspection must remain separate from database verification.

WZP-02 exit evidence is required before beta, not before WZP-01 closure.

## 10. Implementation and release authorization

This decision does not authorise:

- Application-code changes.
- Database access or SQL execution.
- Schema changes or migrations.
- Test execution with database or shared-storage side effects.
- Environment provisioning or reconfiguration.
- Git staging, commits, pushes or merges.
- Deployment.
- Beta invitations or activation.
- Production release or launch.

Any such activity requires its own approved work package and the relevant authority.

## 11. Final Project Owner direction

The consolidated decisions above are the approved governance direction for WZP-01, subject to the explicit blockers and conditions recorded in this document.

Codex is instructed to record these decisions faithfully, reconcile the relevant decision register, ownership matrix, dependency criteria and roadmap documentation, and report any inconsistency.

Codex must not invent appointments, mark blockers resolved, alter unrelated package statuses, close WZP-01, unblock WZP-02 or begin implementation.

**Final status after this decision:**

- D01: APPROVED WITH CONDITIONS.
- D02: APPROVED WITH DEFINED BOUNDARIES.
- D03: APPROVED WITH CONDITIONS.
- D04: APPROVED.
- D05: APPROVED WITH EXPLICIT APPOINTMENT BLOCKERS.
- D06: APPROVED.
- D07: EXPLICIT BLOCKER RECORDED.
- D08: APPROVED.
- D09: APPROVED.
- D10: EXPLICIT BLOCKER RECORDED.
- D11-D13: PENDING / DEFERRED.
- D14: APPROVED BASELINE.
- WZP-01: OPEN / AWAITING OWNER DECISION until closure evidence is recorded.
- WZP-02: BLOCKED.
- Baseline B: APPROVED IN PRINCIPLE, NOT FROZEN.
- Implementation: NOT AUTHORISED.
- Beta: NOT AUTHORISED.
- Production launch: NOT AUTHORISED.