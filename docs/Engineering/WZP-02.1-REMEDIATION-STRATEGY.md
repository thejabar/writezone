# WZP-02.1 Remediation Strategy

**Programme:** WZ-MASTER-ROADMAP-2027.2
**Status:** Planning proposal; implementation not authorized
**Authority:** WZP-02.1 Project Owner command
**Baseline:** WZP-01.8 closure decision; WZP-01.6 product decisions

## Executive disposition

This package converts the WZP-02 initial findings into bounded correction proposals. It does not close WZP-02, freeze Baseline B, appoint an owner or reviewer, authorize code changes, database access, tests, migrations, beta, production, or deployment.

- **WZP-01:** CLOSED WITH CONDITIONS under WZP-01.8.
- **Baseline B:** APPROVED CANDIDATE, NOT FROZEN.
- **WZP-02:** Its initial inspection was bounded and read-only; full exit remains open.
- **WZP-02.1:** Planning documents are proposed for Project Owner review. Each source correction needs its own exact implementation approval and required appointments.

The source review confirms that the policy subject mismatch is a type-contract failure before policy logic, and that the current aggregate runner combines database and shared-storage/session side effects. These findings require correction or safe containment before the affected verification and release journeys can be accepted. Other findings and all environment/database state are classified below by evidence level.

## Evidence rules

Use **VERIFIED IN SOURCE** for code, routes, tests, and scripts actually inspected; **DOCUMENTED** for accepted decisions or architecture statements; **INFERENCE** for consequences derived from code; and **NOT VERIFIED** for runtime, PHP execution, live database, environment, or independent-review claims not established in this task. The Architecture Authority requires these distinctions; source presence, a test name, and a schema snapshot do not prove runtime or live state.

All paths and line references below refer to the local `feature/mentor-engine` working tree at HEAD `95527c38c8bd4b554489dcad0bf5dc700a69d4ad`. No remote state was inspected.

## Finding and workstream register

| ID | Finding and evidence | Severity / release relevance | Proposed response | Owner / approval dependency |
|---|---|---|---|---|
| WZP21-AUTH-01 | **VERIFIED IN SOURCE:** Writ and Comment lookups return generic PDO objects; their policies require concrete model classes. Six controller call sites invoke those policies. | High functional risk for the approved Writ-edit journey; source does not establish an authorization bypass. | Align the policy subject contract with the actual row representation while preserving fail-closed owner checks. Add in-memory contract tests and later isolated route-level evidence. | Application engineering and security owners; Project Owner implementation approval; independent verifier. |
| WZP21-TEST-01 | **VERIFIED IN SOURCE:** Aggregate runner includes a database test, shared app-log deletion, shared rate-limit writes, and session-mutating tests. Release script invokes that runner and `migrate.php status`. | High data-protection risk; aggregate and release scripts are not safe to run as-is. | Implement layered test isolation and fail-closed target validation in a separately approved package. | QA, security, and database owners; independent technical verification. |
| WZP21-ENV-01 | **VERIFIED IN THIS INSPECTION:** Windows command discovery found WSL only among PHP, Composer, WSL, Docker, and Podman; WSL enumeration returned `E_ACCESSDENIED`. | Blocking for PHP syntax and PHP test evidence, but not for documentation planning. | Have an appointed environment owner verify the existing Ubuntu toolchain without changing it; use a separately approved portable toolchain only if that route remains unavailable. | Environment/platform owner and Project Owner for any provisioning. |
| WZP21-DB-01 | **VERIFIED IN SOURCE/DOCUMENTATION:** Snapshot and executable baseline differ on `users.handle`, `notifications.actor_id`, and the actor index name. No current database was accessed. | High data-integrity risk if a schema choice or migration is made without authority and evidence. | Preserve each definition; have the appointed data/database owner resolve semantics and approve a reconciliation procedure. | Database/migration and application data owners; Project Owner; separate database-access approval. |
| WZP21-REG-01 | **VERIFIED IN SOURCE:** Registration trims and derives values, hashes the password, creates the row, and logs in without explicit server-side field validation or duplicate-error handling. Login responses distinguish unknown email and bad password. | Medium security/account-integrity risk; blocks accepted registration/authentication acceptance. No exploitation was reproduced. | Specify validation/canonicalization, collision behavior, generic errors, and atomic duplicate handling before a bounded implementation. | Product authority for field rules; application/security owners; Project Owner. |
| WZP21-NOTIF-01 | **VERIFIED IN SOURCE:** Authenticated GET `/notifications` bulk-updates the current user’s rows to read before listing them. | Medium HTTP-semantics defect; notifications are excluded from Baseline B. | If the feature is separately approved, make GET read-only and put state transition behind an authenticated, rate-limited, CSRF-protected POST. | Product and security owners; separate scope and implementation approval. |
| WZP21-BOOK-01 | **VERIFIED IN SOURCE:** Bookmark route accepts digits only; the form submits a seven-character public ID and the controller queries `writs.public_id`. | Medium functional mismatch; bookmarks are excluded from Baseline B. | If separately approved, align the route with the generator’s public-ID alphabet; retain public-ID lookup. | Product and application/security owners; separate scope and implementation approval. |
| WZP21-B-01 | **DOCUMENTED:** WZP-01.6 approves B boundaries and PO-04/05/07 decisions with technical conditions; the acceptance specification retains superseded “requires owner decision” text for some of those decisions. | Release blocker to freezing B, not authority to reopen or silently change an approved decision. | Reconcile the acceptance document to WZP-01.6, then complete its remaining technical and operational evidence. | Product decision authority, Project Owner, and relevant security/accessibility/operations reviewers. |
| WZP21-TRUST-01 | **DOCUMENTED / NOT VERIFIED:** Content-only Writ reporting is approved in scope through WZP01-D03/CR-WZP-2026-11-09-01, but no route or operational process was found in the inspected source. | Mandatory pre-beta condition if public Writ publishing is retained in frozen B. | Appoint Trust/Safety and Operations owners; approve the operating procedure, privacy/retention rules, and evidence plan before implementation or beta. | Project Owner and appointed Trust/Safety, Operations, Security/Privacy/Legal authorities. |

## Dependency-aware remediation order

1. **Appointments and exact approvals:** appoint a package delivery owner, PHP/environment owner, QA/test-isolation owner, database/data owner, security reviewer, and independent verifier as applicable. No appointment is inferred here.
2. **Environment access:** read-only verification of PHP 8.2+, Composer, required extensions, and repository access through the existing WSL distribution. If inaccessible, stop and request a separate environment decision.
3. **Safe test foundation:** establish Layer 1 and Layer 2 isolation before any PHP regression run; establish Layer 3 only after the independent database-target policy is implemented and separately verified. Keep Layer 4 local-fixture tests isolated and opt-in.
4. **Authorization correction:** smallest code correction to the Writ/Comment policy subject contract, followed by isolated policy and route-flow regression evidence. Writ editing is an approved B journey; comments remain excluded from B.
5. **Registration correction:** once server rules and canonicalization are approved, implement validation and safe duplicate handling with isolated positive, negative, collision, and login-error evidence.
6. **Schema reconciliation:** source-only comparison and a decision ledger may proceed alongside steps 2–5. Any live-database comparison, schema adoption, SQL, or migration requires a separately authorized disposable target, appointed database owner, and independent verification.
7. **Deferred capabilities:** notifications and bookmarks remain outside B; do not implement them as part of B stabilization. Obtain separate scope approval before any change.
8. **Baseline B closure:** reconcile specification wording with WZP-01.6; complete PO-04 storage evidence, PO-05 fixed qualitative query cases, PO-07 browser/viewport matrix, and the remaining journey evidence.
9. **Safety operations:** appoint and operationally prepare the content-reporting path before beta if public publishing remains in B.
10. **Independent verification and next approval:** reviewer repeats the authorized checks against exact diff/environment and records residual findings. The Project Owner then approves the next bounded implementation package; this plan itself grants no such approval.

Source-only schema research, environment planning, and acceptance-document reconciliation can proceed in parallel after appointments. Runtime changes cannot bypass environment/test-safety prerequisites. The BETA gate additionally requires content-reporting operations and all relevant product, safety, privacy, support, and audit evidence.

## Approval boundary and cross-references

The proposal units, inventories, risks, tests, rollback boundaries, and approvers are in [WZP-02.1 Implementation Approval Pack](WZP-02.1-IMPLEMENTATION-APPROVAL-PACK.md). Supporting specifications: [authorization review](WZP-02.1-AUTHORIZATION-AND-ACCESS-CONTROL-REVIEW.md), [test safety](WZP-02.1-TEST-SAFETY-AND-ISOLATION-SPECIFICATION.md), [environment plan](WZP-02.1-PHP-COMPOSER-ENVIRONMENT-PLAN.md), [database plan](WZP-02.1-DATABASE-AND-MIGRATION-RECONCILIATION-PLAN.md), [registration review](WZP-02.1-REGISTRATION-AND-ACCOUNT-SECURITY-REVIEW.md), [notifications/bookmarks](WZP-02.1-NOTIFICATION-AND-BOOKMARK-FINDINGS.md), [B acceptance gaps](WZP-02.1-BASELINE-B-ACCEPTANCE-GAP-REGISTER.md), and [ownership register](WZP-02.1-OWNERSHIP-AND-INDEPENDENT-VERIFICATION-REGISTER.md).

**No source, schema, configuration, test, credential, database, or release change is authorized by this planning package.**
