# WZP-02.1 Completion and Readiness Report

**Date:** 2026-11-09
**Status:** Documentation package prepared for Project Owner review; implementation not authorized
**Controlling baseline:** WZP-01.6 and WZP-01.8, current local source, and repository architecture authority

## 1. Disposition

WZP-02.1 provides a source-based remediation plan and proposed, independently approvable implementation units. It does not establish WZP-02 exit, approve a runtime correction, close the schema decision, appoint owners, freeze Baseline B, or authorize a database/test operation. WZP-01 is closed with conditions; WZP-02 remains subject to its entry, evidence, ownership, and separate approval requirements.

## 2. Findings carried into the plan

| Finding | Local source evidence | Planning disposition |
|---|---|---|
| Writ/comment authorization subject contract | Database lookup methods return generic PDO row objects, while `WritPolicy` and `CommentPolicy` accept concrete model classes. The typed policy call can fail before the ownership decision and before the following mutation. No authorization bypass was established by static inspection. | AP-01: align the subject contract, preserve default-deny ownership checks, and test PDO-shaped rows and controller mutation ordering. |
| Aggregate test safety | `scripts/test/run.sh` includes filesystem/session tests and `WritPublicationEventRegression.php`; the latter reads `.env`, connects with its selected DB identity, and writes/deletes records without an independent target allowlist. `scripts/release/check.sh` invokes that aggregate and `migrate.php status`, which bootstraps a DB connection. | Do not execute either runner. AP-03 isolates Layer 1/2; AP-04 separately gates any Layer 3 database test. |
| Schema/migration differences | The documented `database/schema/live-schema.sql` and executable baseline migration differ on `users.handle` and `notifications.actor_id` nullability; an actor index name also differs. The schema snapshot is documented as reference evidence, not the executable migration history. | AP-05 records the source-only comparison. D07 semantics remain unresolved; no live DB state was accessed and no migration is proposed as approved. |
| Registration validation | Registration trims and canonicalizes values and persists them without explicit server-side validation for an approved field policy; database uniqueness remains the final race authority. Login messages distinguish unknown account from invalid password. | AP-06 requires approved field rules, server validation, race-safe duplicate handling, and generic login failure behavior. No recovery or schema feature is included. |
| Notification state on GET | Authenticated `GET /notifications` marks the current user's notifications read before listing them. | AP-08 proposes read-only GET and a protected write action only if notifications receive separate product scope; notifications remain excluded from B. |
| Bookmark identifier route | The detail form submits a public Writ ID, but the route accepts only digits; the handler then searches by public ID. Generated IDs use letters and digits. No IDOR was established. | AP-09 proposes route grammar alignment only if bookmarks are separately selected; bookmarks remain excluded from B. |
| Baseline B evidence gaps | Approved scope decisions exist, but some acceptance wording is stale and technical/browser/storage evidence is incomplete. | AP-10 reconciles documentation to WZP-01.6 without reopening approved choices or freezing B. |
| Content reporting readiness | Content-only public Writ reporting scope is approved, but the staffed operational intake/response path and appointed owners are not verified. | AP-11 requires an approved, staffed, privacy-controlled process and rehearsal before beta invitations if public publishing is in B. |

These are source/document observations and proposed dispositions. No runtime behavior was reproduced because tests were not run.

## 3. Safe execution and environment status

- The aggregate regression and release runners are unsafe for this task; neither was executed.
- No individual PHP regression, syntax check, Composer command, database-backed test, migration, or application test was run.
- A local read-only WSL check previously returned `E_ACCESSDENIED` when enumerating distributions. The PHP/Composer validation route therefore remains unverified. AP-02 permits only read-only re-verification through the existing approved WSL environment; any installation or portable fallback needs separate approval.
- No database, `.env`, credential, production, staging, Hostinger, GitHub, or external service was accessed.
- The test specification defines four future layers: pure in-process checks; isolated filesystem/session checks; a disposable database with pre-connection target allowlisting and post-connection identity verification; and controlled loopback service fixtures. These controls are proposals, not implemented or verified.

## 4. Proposed implementation units and approval boundary

The approval pack defines AP-01 through AP-12: authorization subject contract; PHP/Composer environment verification; Layer 1/2 storage isolation; Layer 3 database target policy; source-only schema reconciliation; registration/sign-in hardening; any later schema change; notifications; bookmarks; Baseline B documentation reconciliation; content-reporting operations; and independent WZP-02 verification.

Every AP unit remains **PROPOSED — NOT APPROVED**. Approval of one unit must identify its exact scope and required owners; it does not authorize other units. AP-04 and AP-07 require separate target/migration approval. AP-08/AP-09 remain outside B. AP-11 is an operational beta prerequisite if public Writ publishing remains in B. AP-12 requires a verifier independent from implementation.

## 5. Ownership and readiness

The Project Owner remains the decision authority for package selection and implementation approval. Application engineering, database/data ownership, Security, QA, Product, Trust/Safety, Privacy/Legal, Operations, environment ownership, and independent verification remain role requirements; where no appointment is documented, the role is unassigned. The ownership register records which AP units depend on each role and what evidence each must provide.

WZP-02.1 is ready for Project Owner review as a planning package. WZP-02 implementation is **not ready to start by implication**: safe test-layer execution, PHP/Composer environment evidence, D07 schema semantics, approved target identity for database tests, exact selected implementation units, and applicable owner/reviewer assignments remain gates. Some later decisions can remain open without blocking source-only planning, including notifications/bookmarks while excluded, production deployment details, and numeric programme-wide metrics.

Baseline B remains an approved candidate, not frozen. Public beta invitations remain unauthorized. No proposed implementation unit changes approved product scope.

## 6. Documentation package

The package consists of the following eleven WZP-02.1 documents:

1. `WZP-02.1-REMEDIATION-STRATEGY.md`
2. `WZP-02.1-AUTHORIZATION-AND-ACCESS-CONTROL-REVIEW.md`
3. `WZP-02.1-TEST-SAFETY-AND-ISOLATION-SPECIFICATION.md`
4. `WZP-02.1-PHP-COMPOSER-ENVIRONMENT-PLAN.md`
5. `WZP-02.1-DATABASE-AND-MIGRATION-RECONCILIATION-PLAN.md`
6. `WZP-02.1-REGISTRATION-AND-ACCOUNT-SECURITY-REVIEW.md`
7. `WZP-02.1-NOTIFICATION-AND-BOOKMARK-FINDINGS.md`
8. `WZP-02.1-BASELINE-B-ACCEPTANCE-GAP-REGISTER.md`
9. `WZP-02.1-OWNERSHIP-AND-INDEPENDENT-VERIFICATION-REGISTER.md`
10. `WZP-02.1-IMPLEMENTATION-APPROVAL-PACK.md`
11. `WZP-02.1-COMPLETION-AND-READINESS-REPORT.md`

The Engineering index links the package. Roadmap 02, 03, and 09 record it as a controlled child planning workstream without changing the 32-package baseline or its dependency graph.

## 7. Validation record

Validation was limited to local documentation structure, references, whitespace, and repository-state comparison. The 11 package documents, Engineering index, and three roadmap documents were present (15/15); 53 local Markdown links resolved; fence pairs and same-level heading uniqueness passed; and the approval register contained the ordered AP-01 through AP-12 units. A scoped trailing-tab scan passed. `git diff --check` exited 0; because the package documents are untracked, that Git command does not inspect their contents, which were covered by the direct Markdown and whitespace checks. No application or runtime tests were run.

## 8. Required next decision

The Project Owner may select one or more exact AP units, appoint or designate required role owners, and approve the bounded activity described in each selected unit. Until then, this report authorizes no implementation, database operation, test execution, beta invitation, production change, or release action.
