# WZP-02 Engineering Baseline Verification Plan

**Programme:** WZ-MASTER-ROADMAP-2027.2
**Authorization:** WZP-01.8 Project Owner command, dated 2026-11-09
**Status:** READY FOR CONTROLLED ACTIVATION — initial local, read-only verification only
**Package owner:** UNASSIGNED
**Independent reviewer:** UNASSIGNED

## 1. Objective and evidence rules

Establish a source-based, reviewable engineering baseline before feature implementation. This is a verification and planning package, not a feature-development package. Mark conclusions as **VERIFIED IN SOURCE**, **DOCUMENTED**, **INFERENCE/RECOMMENDATION**, or **NOT VERIFIED**. A document, migration, test name, or local configuration is not proof of runtime or live-database state. Do not infer local, GitHub, Hostinger, staging, or production equivalence.

## 2. Authorized scope

The WZP-01.8 command authorizes local read-only inspection of this repository and safe documentation validation for the workstreams below. Preserve all existing tracked and untracked files. The command does not appoint the package owner or reviewer; those roles remain unassigned. This bounded initial inspection is the sole exception to the ordinary appointment blocker and makes no claim of independent review.

### A. Repository and source-of-truth verification

Record branch, HEAD, full working-tree status, tracked modifications, untracked paths, project structure, applicable AGENTS.md, architecture authority, schema snapshot, migrations, test entry points, CI configuration, engineering specifications, and product scope. Cite exact source locations. Do not reset, clean, stash, overwrite, or stage work.

### B. Database and migration reconciliation

Read the documented schema and executable migration sources only. Record their distinct authorities, migration discovery/runner behavior, baseline tables and known differences, including users.handle nullability, notifications.actor_id nullability, and actor index naming. Identify unresolved constraints and what evidence could resolve them. No database connection, SQL execution, live-state inference, schema change, or migration is permitted.

### C. Test safety and isolation

Inventory every test and runner, then classify each as in-memory/read-only, isolated temporary filesystem, database integration, external-service/process, shared-storage/destructive, or unsafe/unclassified. Follow side effects through setup and cleanup; do not trust test names or comments. Do not run the aggregate runner, release gate, any database-backed test, or any test touching shared logs, sessions, rate-limit storage, or non-unique cleanup paths. Propose a safe execution policy; remediation needs separate approval.

### D. Local environment readiness

Inspect executable availability and versions without installation or configuration changes. Record executable paths, PHP/Composer/Python availability, dependency-manager presence, WSL access limitations, and safe alternatives. Do not inspect secrets or .env contents, change PATH, install packages, start services, or access a database.

### E. Security and application baseline

Review source for authentication/session behavior, registration, password/recovery journeys, authorization, CSRF, rate limits, request parsing/limits, validation/output escaping, public identifiers, notification writes, bookmarks, storage, API boundaries, errors/logging, and data exposure. Rank concrete findings with stable IDs, path/line, observed behavior, impact, safe verification method, correction direction, dependency/approval, and required regression coverage. Do not implement fixes.

### F. Baseline B traceability

Map only the approved B candidate: registration/authentication, public profile, text Writ publishing/reading/editing and attribution, length/storage rules, non-AI search/discovery, hashtag discovery, newest-first ordering, responsive desktop/mobile-browser behavior, accessibility, recovery, and content-only reporting. For each item record owner-approved source, current source behavior, gap, dependency, proposed package, and verification evidence. Preserve all exclusions; do not freeze B or add scope.

### G. Independent verification readiness

List evidence a separately appointed reviewer will need for source changes, isolated regressions, security controls, schema/migration integrity, test isolation, accessibility, responsive behavior, and operational readiness. Do not name or appoint a reviewer, and do not call this initial Codex review independent.

## 3. Execution policy

Permitted checks: read-only Git status/history, file inventory, source/document inspection, local executable/version checks that do not disclose secrets, static documentation/link checks, and git diff --check. Do not run scripts/test/run.sh or scripts/release/check.sh: the former includes database and shared-storage/session side effects; the latter calls that runner and migrate.php status, requires a clean tree, and would connect to a database if it passed the clean-tree guard. Do not run any other PHP or Python application test in this initial package. PHP syntax checks are not run when the PHP executable is unavailable.

## 4. Required deliverables and package exit

The controlled initial phase produces this plan and WZP-02-INITIAL-VERIFICATION-REPORT.md. Those documents are not WZP-02 completion evidence by themselves. Full exit requires:

1. Source/schema authority and known differences are clear enough for the next separately authorized task; live state remains a separate question.
2. A demonstrably safe isolated test path exists before test execution, with pre-connection database identity validation where applicable.
3. A safe PHP/Composer verification environment is verified or an approved alternative is recorded.
4. Critical security and data-integrity risks are understood and assigned to packages/owners.
5. Every approved Baseline B requirement maps to implementation and acceptance evidence without changing scope.
6. Required package owners and reviewers are appointed, or remain explicitly blocked; the independent verifier is distinct where required.
7. The Project Owner receives an evidence-based, exact-scope decision request for any correction or implementation work.

The first runtime correction is a separate package. Its approval must specify exact files and behavior, safe test environment, regression/security/user-journey evidence, database need, reviewer, rollback, and acceptance. This WZP-02 plan grants no implementation authority.

## 5. Approval boundaries and handoff

WZP-01 is CLOSED WITH CONDITIONS. Baseline B is APPROVED CANDIDATE, NOT FROZEN. WZP-02 is READY FOR CONTROLLED ACTIVATION only within this initial read-only scope. Beta, production, deployment, migrations, database reads/writes, credentials, external systems, test execution with side effects, runtime changes, and Git release operations remain unauthorized. After the initial report, stop and present evidence for the Project Owner's next decision. Do not begin another work package.
