# WZ-CORP Technical Validation and Implementation Readiness

**Status:** Design package only; implementation, SQL and database work are not authorized
**Validation performed:** Static document/source review only; no tests or database operations

## Readiness assessment

| Area | Status | Evidence or blocking action |
|---|---|---|
| Architecture boundaries | READY WITH CONDITIONS | Principles are documented in CORP-00; recover the original CORP register and resolve its CORP-01–22/23 discrepancy. |
| Domain ownership | READY WITH CONDITIONS | Proposed owners are separated; appoint accountable organisation, workforce, IAM, governance, finance and audit owners. |
| Business policy | BLOCKED | Approve actual entity/unit catalogue, workforce authority, assignment authority, roles, permission catalogue, approval policy and delegation rules. |
| Legal, privacy and retention | BLOCKED | Qualified reviewers must determine jurisdiction-specific handling, privacy notices, retention and evidence rules; none is inferred here. |
| Security architecture | READY WITH CONDITIONS | Default-deny and identity separation are specified; select the IdP, federation protocol, MFA policy, key management, privileged access and incident owners. |
| Logical/physical model | READY WITH CONDITIONS | Proposed table and FK specifications are internally documented; domain owners must accept semantics and target MariaDB behavior must be tested. |
| Existing schema reconciliation | BLOCKED | Historical snapshot and baseline migration differ; no current database or migration-history state was accessed. Complete approved source reconciliation first. |
| Application integration | NOT STARTED | No WZ-CORP runtime module or integration was implemented or verified. |
| Backward compatibility | READY WITH CONDITIONS | Design keeps public users and sessions separate; prove the boundary with source-level and regression evidence before release. |
| Automated testing | NOT STARTED | No corporate tests exist in this design package and none were run. Approve an isolated disposable database before database tests. |
| Migration and rollback | BLOCKED | Canonical baseline, migration history, recovery strategy and supported MariaDB version remain unverified. Migration interface has no down contract. |
| Deployment and operations | NOT STARTED | No corporate deployment, monitoring, backup, restore or operational ownership exists in this package. |

## Required verification matrix before implementation release

| Test group | Required cases | Passing evidence |
|---|---|---|
| Schema and FK constraints | Every FK target key and column order; exact composite keys; RESTRICT behavior; scope XOR checks; migration from a fresh disposable database and an approved adopted baseline. | Versioned isolated test output plus schema introspection from the disposable target. |
| Effective-dated ORG rules | Half-open boundaries, overlapping unit/legal-entity relationships, assignment changes at the same timestamp, primary assignment, position capacity, reporting cycles and concurrent writes. | Regression tests with transaction/concurrency cases; rejected invalid states leave no partial records. |
| IAM identity and scope | Exact issuer+subject binding, duplicate identity rejection, email non-matching, one scope per grant, multiple independent grants, role versioning, expiry, revocation and active-session invalidation. | Isolated authorization regression suite and negative cross-entity access tests. |
| GOV decisions | Workflow-version/step consistency, idempotent submissions and delegations, self-approval/SoD rejection, expiry, cancellation, escalation and resubmission. | State-transition tests with append-only decision evidence. |
| Audit integrity | Sequence uniqueness, hash-chain continuity, exact attempt-to-seal composite references, stale fencing-token rejection, manifest-hash equality, archive mismatch and interrupted external operations. | Fault-injection/concurrency tests and independent verification of generated fixtures. |
| Compatibility and security | Consumer login/session behavior unchanged, default-deny behavior, no route exposure before activation, authorization checks on every resource, secrets outside records/logs. | PHP syntax/security checks, targeted regressions and user-journey review on an isolated environment. |
| Recovery and release | Backup/restore, failed migration handling, forward repair, monitoring/alerting, operator runbook and reviewed deployment plan. | Successful rehearsal reports and separate deployment approval. |

## Implementation sequence and gates

1. **Close design authority:** recover the original decision register; approve domain owners and unresolved business, legal, privacy and security policies. Gate: written architecture approval.
2. **Validate schema compatibility:** approve supported MariaDB version and reconcile the baseline, snapshot and migration history. Gate: reproducible source diff and approved disposable test target.
3. **Implement ORG foundation:** catalogue, effective-dated units/positions/assignments and transactional graph validation. Gate: constraint and concurrency tests pass.
4. **Implement IAM foundation:** corporate principal resolution, issuer/subject bindings, role versions and one-scope grants. Keep public identity untouched. Gate: negative authorization and revocation tests pass.
5. **Implement GOV foundations:** approved workflow references, append-only decisions and auditable grant issuance. Gate: SoD, idempotency and lifecycle tests pass.
6. **Implement audit evidence:** append transactionally, then add sealing/archive only after signer, key custody, storage and recovery are approved. Gate: stale-reservation and tamper-detection tests pass.
7. **Release in stages:** deploy dark/disabled, verify health and audit signals, then enable only approved administrative users and scopes. Gate: separate release and deployment approval.

Each gate authorizes only its named package. A design acceptance is not migration, production-access or deployment approval.

## Local validation record for this documentation package

The permitted validation is limited to Markdown links, required-heading presence, cross-reference consistency, whitespace/diff checks and manual review of the proposed FK matrix against documents 01–04. No PHP/Python tests, SQL, migrations, database connections, services, external systems or production verification are part of this package. Results are recorded in the completion report and do not establish MariaDB compatibility.
