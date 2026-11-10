# WZP-02.12 Project Owner Decision and AP Approval Record

**Programme:** WZ-MASTER-ROADMAP-2027.2
**Decision authority:** WriteZone Project Owner
**Decision:** AP-01 and AP-03 APPROVED WITH CONDITIONS
**Approval date:** Earlier approval date not separately recorded; confirmation for this record communicated in WZP-02.12 on 2026-11-09
**Implementation activation:** NOT ACTIVATED

## 1. Project Owner decision

The Project Owner confirms the previously communicated conditional approval of these two bounded units:

- **AP-01 — Writ and Comment authorization subject contract.**
- **AP-03 — Layer 1/2 test storage and session isolation.**

The approvals are limited to each unit's original WZP-02.1 scope, exclusions, safety requirements, acceptance criteria, and independent-review requirements reproduced below. Conditional approval authorizes preparation to satisfy those conditions; it is **not** implementation activation. Neither unit may begin implementation until its required delivery owner, reviewer, and readiness conditions are satisfied and documented. No other AP unit is approved by this decision.

This record is the current Project Owner status reference for AP-01/AP-03. It supersedes the proposal-only status in the WZP-02.1 records only to the extent that those records describe the current approval status. Their original wording remains historical evidence and is not edited or erased.

## 2. AP-01 — conditional approval and exact conditions

**Disposition:** APPROVED WITH CONDITIONS.
**Activation:** NOT ACTIVATED; required appointments and evidence remain outstanding.

| Control | Approved condition, preserving the WZP-02.1 proposal scope |
|---|---|
| Objective | Make policy subject types agree with repository row objects without weakening default-deny ownership checks. |
| Included files | app/Policies/WritPolicy.php, app/Policies/CommentPolicy.php, and tests/AuthorizationRegression.php. Add tests/AuthorizationFlowRegression.php only if its no-database fixture is confirmed and included in the final approval. This record does not include that optional file unless it is confirmed and recorded in the activation decision. |
| Excluded | Routes, account model, database schema, comment scope expansion, admin/RBAC features, and delete-feature acceptance. |
| Method | Use a guarded generic-row/ownership contract or an explicitly approved typed subject projection; preserve user/resource owner equality and deny malformed subjects. |
| Dependencies and risk | Verified PHP environment and Layer 1 test capability. The pack records HIGH functional risk; no authorization bypass is currently proven. |
| Required tests | Generic PDO-shaped subjects: owner allowed; non-owner denied; absent, null, or invalid owner denied; unknown ability denied; controller flow never mutates on denial or exception. A route-level database test requires separate Layer 3 authorization. |
| Independent verification | A Security reviewer separate from delivery traces the actual route-to-policy argument class and repeats regressions; verifies mutation occurs only after successful authorization. |
| Rollback | Revert only the approved policy/test diff if regression fails; no data migration. Preserve every other working-tree change. |
| Acceptance | Writ edit by its owner works; non-owner, anonymous, and invalid-resource attempts cannot update; retained Comment paths fail closed; no source path depends on a different subject contract. |

## 3. AP-03 — conditional approval and exact conditions

**Disposition:** APPROVED WITH CONDITIONS.
**Activation:** NOT ACTIVATED; delivery, QA, Security, and independent-verification appointments remain outstanding.

| Control | Approved condition, preserving the WZP-02.1 proposal scope |
|---|---|
| Objective | Make pure and filesystem tests deterministic and unable to write to or delete shared application data. |
| Included files/components | scripts/test/run.sh, scripts/release/check.sh, tests/RateLimiterRegression.php, tests/RateLimitMiddlewareRegression.php, tests/RateLimitRouterRegression.php, tests/ErrorHandlerRegression.php, tests/SecurityCsrfRegression.php, tests/SessionSecurityRegression.php, and new tests/Support/IsolatedTestEnvironment.php. The original pack requires any added configuration seam to be named in the implementation plan before approval. This record adds no seam; any new seam or file outside this inventory needs explicit scope clarification before activation. |
| Excluded | Database connection/target allowlist, migrations, application feature changes, external services, and production paths. AP-03 does not authorize Layer 3 database tests. |
| Method | Create a unique per-run root; isolate logs, rate-limit storage, and session storage; use containment-checked cleanup; separate Layer 1 from opt-in Layer 2; keep the release runner away from unsafe Layer 3 and database status commands by default. |
| Dependencies and risk | Verified PHP runtime and appointed QA and Security owners. The pack records HIGH data-protection risk if containment is wrong. |
| Required tests | Concurrent runs; unique root; preservation of a pre-existing sentinel; cleanup contained within the owned root; session/log/cache writes confined; aggregate invocation selects only approved layers. |
| Independent verification | Review cleanup path resolution, symlink behavior, and failure paths; run only the approved filesystem-only tests in a fresh isolated temporary root. |
| Rollback | Restore runner behavior only by reverting the approved harness changes. Never delete application logs, cache, or sessions as rollback. |
| Acceptance | No test can write or delete outside its unique test-owned root; the release runner clearly gates any integration layer. |

## 4. Required roles and activation gates

| Role | Responsibility | Independence requirement | Status | Exact appointment/action needed |
|---|---|---|---|---|
| AP-01 application engineering delivery owner | Deliver the approved Writ/Comment authorization subject-contract change and scoped tests. | Cannot be the sole independent Security reviewer or verifier of their own change. | UNASSIGNED | Project Owner records the named appointee, AP-01 authority, scope, and reporting path. |
| AP-01 independent Security reviewer | Review AP-01 authorization behavior, default-deny handling, diff, and regression evidence. | Separate from AP-01 implementation delivery. | UNASSIGNED | Project Owner appoints a reviewer and records review scope and independence. |
| AP-03 QA/test-isolation delivery owner | Deliver the approved Layer 1/2 test-storage and session-isolation changes. | Cannot be the sole independent Security reviewer or verifier of the harness they deliver. | UNASSIGNED | Project Owner records the named appointee, AP-03 authority, scope, and reporting path. |
| AP-03 independent Security reviewer | Review containment, cleanup, test selection, failure behavior, and evidence. | Separate from AP-03 implementation delivery. | UNASSIGNED | Project Owner appoints a reviewer and records review scope and independence. |
| WZP-02 independent verifier | Independently repeat WZP-02 evidence and assess package exit. | Separate from the implementation being verified; Codex cannot verify its own implementation. | UNASSIGNED | Project Owner appoints a verifier with access to exact evidence and acceptance criteria. |
| WZP-02 engineering work-package owner | Own the evidence ledger, dependencies, completion report, and handoff. | Cannot self-verify security, privacy, safety, or data-integrity work. | UNASSIGNED | Project Owner records the assignment, scope, and accountability. |
| Project Owner | Decide scope/activation and accept WZP-02. | Must remain distinct from delivery and independent-review functions. | Authority documented; personal name not recorded. | Retains final package acceptance authority; no appointment is required for this role. |

Before either AP unit is activated, its activation record must name the exact implementer, allowed files, approval conditions, test commands and safety layer, reviewer, verifier, rollback, and acceptance criteria. Appointment combinations require an explicit conflict and independence assessment; none is assumed here. A separate dated Project Owner activation decision is required for each unit after those conditions and appointments are recorded; conditional approval alone is not implementation authority.

## 5. Explicit exclusions and prohibitions

This decision does **not** approve AP-02 installation or environment changes, AP-04 database target policy, AP-05 schema work, AP-06 registration/sign-in changes, AP-07 migrations, or any other AP unit. It does not authorize database access or tests, SQL, migrations, .env access, staging/production/Hostinger access, external services, commits, pushes, merges, deployment, beta invitations, or launch. AP-03 must not be used to make unsafe database tests appear isolated.

## 6. Evidence and decision history

1. [WZP-01.8 Project Owner Closure Decision](WZP-01.8-PROJECT-OWNER-CLOSURE-DECISION.md) closed WZP-01 with conditions and authorized only initial local read-only WZP-02 verification; it made no specialist appointments and granted no runtime implementation authority.
2. [WZP-02.1 Implementation Approval Pack](WZP-02.1-IMPLEMENTATION-APPROVAL-PACK.md) and [WZP-02.1 Completion and Readiness Report](WZP-02.1-COMPLETION-AND-READINESS-REPORT.md) were accurate historical planning records at their issue point: every AP unit was proposal-only.
3. The [WZP-02.1 Ownership and Independent-Verification Register](WZP-02.1-OWNERSHIP-AND-INDEPENDENT-VERIFICATION-REGISTER.md) records the required specialist roles as unassigned.
4. The [WZP-02.10 Completion and Readiness Report](WZP-02.10-COMPLETION-AND-READINESS-REPORT.md) reports the later conditional AP-01/AP-03 approval and notes the documentary conflict and appointment blockers.
5. The [WZP-02.11 Approval Reconciliation Report](WZP-02.11-APPROVAL-RECONCILIATION-REPORT.md) and [Project Owner Decision Record](WZP-02.11-PROJECT-OWNER-DECISION-RECORD.md) correctly recorded that conflict as unresolved at that time; this WZP-02.12 decision supersedes their AP approval status only. The [appointment register](WZP-02.11-OWNERSHIP-AND-REVIEW-APPOINTMENT-REGISTER.md) and [exit-condition register](WZP-02.11-WZP-02-EXIT-CONDITION-REGISTER.md) remain controlling for unassigned roles and incomplete evidence.
6. By this WZP-02.12 instruction, the Project Owner confirms conditional approval for AP-01 and AP-03 only. The original approval event date is not separately recorded; this record documents the confirmation on 2026-11-09.

## 7. Current status

- **AP-01:** APPROVED WITH CONDITIONS; NOT ACTIVATED; delivery and review appointments and Layer 1 readiness remain required.
- **AP-03:** APPROVED WITH CONDITIONS; NOT ACTIVATED; QA/Security appointments and isolation implementation and evidence remain required.
- **Other AP units:** NOT APPROVED by this decision.
- **WZP-02:** OPEN. Environment verification is recorded complete in WZP-02.10; test safety, source/schema reconciliation, required implementation evidence, independent review, independent verification, and Project Owner closure remain outstanding.

This record is an approval status decision, not implementation authorization. The [delivery schedule](WZ-IMPLEMENTATION-DELIVERY-SCHEDULE-2027.md) is planning only and cannot activate a package.
