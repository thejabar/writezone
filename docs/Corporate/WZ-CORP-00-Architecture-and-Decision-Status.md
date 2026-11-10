# WZ-CORP Architecture and Decision Status

**Status:** Proposed architecture; no implementation authorized
**Evidence baseline:** User-provided WP1–WP1.5.2 package prompts/reports and current local source inspection
**Authority:** [Repository architecture authority](../ARCHITECTURE_AUTHORITY.md)

## Purpose

This is the entry point for the proposed WriteZone Corporate Foundation: WZ-ORG (organisation), WZ-IAM (corporate identity and access), WZ-GOV (governance) and later WZ-INT (integration). The design preserves WriteZone's PHP modular monolith and shared MariaDB direction. It does not authorize code, schema, migration, authentication, integration or deployment changes.

## Approved principles

- Keep the existing custom PHP MVC modular monolith; do not introduce microservices or replace it without an explicit architecture decision.
- Keep ORG, IAM and GOV as distinct logical owners. WZ-EXEC is a read-oriented navigation/visibility layer, not an authority over their records.
- Keep public WriteZone identities, sessions and permissions separate from corporate principals and sessions.
- Use default-deny scoped authorization. Do not infer access from job title, email, reporting line or community role.
- A role grant has exactly one scope: one legal entity or one organisational unit. Multiple scopes require independent grants.
- Use effective-dated relationships with inclusive valid_from and exclusive valid_to.
- Workforce status belongs to an approved workforce authority; ERP owns cost-centre master data; IAM owns access grants; GOV owns approvals and governance evidence.
- No automatic scope inheritance. Do not assume an HRMS, ERP, IdP, legal entity, department, role or authority exists.
- The initial organisation catalogue must not depend on an external integration registry. The Python Intelligence Service is not a corporate dependency.
- Do not describe the synchronous in-process event dispatcher as a durable integration bus.

## Proposed decisions awaiting approval

| Topic | Proposed direction | Required authority |
|---|---|---|
| Workforce facts | Keep employment/contract status with the approved workforce source; WZ-ORG holds only the accepted minimal projection. | Workforce/HR and executive owners |
| Assignment authority | WZ-ORG owns the accepted position-assignment graph used for WriteZone organizational scope; HRMS may be a source/proposer. | Workforce/HR and organisation owners |
| Federation | Bind an external identity by exact issuer and subject; never match by email. Keep production login disabled until IdP, MFA and proofing are approved. | Security/IAM |
| Authorization | Version roles and permissions; issue grants only after policy-required approval; no implicit hierarchy inheritance. | Security/IAM and domain owners |
| Audit | Use append-only application records, externally signed manifests and independent archive for tamper-evidence claims. | Security and audit/operations |
| Bootstrap | Permit controlled, attributable organization-catalogue setup; do not create a permanent universal administrator. | Executive, organisation and security owners |

These are recommendations, not approvals.

## Original decision register traceability

The original CORP register and completed WP1.4 report were not located in the repository or supplied as report artifacts. The WP1.4 command refers to CORP-01–CORP-23; WP1.5.1 asks for CORP-01–CORP-22. All original IDs, wording and status remain **UNVERIFIED** until the source register is recovered. No new WP1.5 identifiers replace original IDs.

The supplied WP1.4 command lists decision subjects (identity separation, workforce authority, assignments, scope, federation, MFA, SoD, approval, audit, emergency access and privacy), but does not map those subjects to CORP IDs.

## Evidence and implementation status

The repository contains no WZ-CORP runtime module or corporate physical database schema/migration in the inspected paths; this documentation set is proposed only. **VERIFIED IN SOURCE:** [Auth](../../core/Authentication/Auth.php) stores the consumer user_id session value, [AuthMiddleware](../../app/Middleware/AuthMiddleware.php) checks its presence, and [WritPolicy](../../app/Policies/WritPolicy.php) and [CommentPolicy](../../app/Policies/CommentPolicy.php) compare resource ownership. This is not corporate RBAC. The database snapshot is a documented historical reference, not proof of present live state. This design is therefore **PROPOSED / NOT IMPLEMENTED / NOT TESTED / NOT PRODUCTION-VERIFIED**.

## Approval gates

Before implementation, recover the original register; approve workforce/assignment ownership, IdP/MFA, permission and approval policy, privacy/retention, audit archive/signing, supported MariaDB version, schema reconciliation and operational ownership. Each implementation package then requires separate scope, tests, migration/forward-repair plan and release approval.
