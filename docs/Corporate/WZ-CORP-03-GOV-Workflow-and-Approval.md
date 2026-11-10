# WZ-GOV Workflow and Approval Architecture

**Status:** Proposed physical design; approval policies remain unapproved
**Domain owner:** WZ-GOV
**Shared types:** [Corporate physical-model notation](README.md#physical-model-notation)

## Decision model

An approval decision records governance. It does not issue an IAM grant or authorize an application request. IAM grant creation must validate the approved decision against the exact request target, principal, role, scope, expiry and approval policy. Runtime authorization evaluates the grant independently.

Self-approval is prohibited for designated sensitive operations. The designated operation set and approver authority require security and business approval. Requester/approver comparison, delegation validity, SoD rules and approval thresholds are application-transaction checks; a simple FK cannot enforce them.

## Workflow tables

All tables are GOV-owned. FK actions are RESTRICT/RESTRICT. All referenced BIGINT IDs have the same unsigned definition. Fields not marked NULL are NOT NULL; no default is implied unless stated.

| Table | Columns beyond default ID and M/A (an explicit PK replaces default ID) | Keys, FKs and checks | Lifecycle and retention |
|---|---|---|---|
| gov_policies | policy_code CODE; title NAME(160); owner_domain CODE; policy_state VARCHAR(24) ascii-bin; M | UQ(policy_code); IX(owner_domain,policy_state). | draft → active → retired; GOV-R. |
| gov_policy_versions | policy_id BIGINT UNSIGNED; version_no INT UNSIGNED; content_hash BINARY(32); policy_state VARCHAR(24) ascii-bin; valid_from DATETIME(6) NULL; valid_to DATETIME(6) NULL; approval_decision_ref BINARY(16) NULL; M | UQ(policy_id,version_no); FK policy_id → gov_policies(id); IX(policy_state,valid_from); CHECK date pair is both null or valid half-open interval. | draft → approved → active → superseded; immutable after approval; approval reference is opaque to avoid a self-FK cycle; GOV-R. |
| gov_controls | policy_version_id BIGINT UNSIGNED; control_code CODE; description NAME(500); control_state VARCHAR(24) ascii-bin; M | UQ(policy_version_id,control_code); FK policy version; IX(control_state). | draft → active → retired with its policy version; GOV-R. |
| gov_workflows | workflow_code CODE; name NAME(160); workflow_state VARCHAR(24) ascii-bin; M | UQ(workflow_code). | draft → active → retired; GOV-R. |
| gov_workflow_versions | workflow_id BIGINT UNSIGNED; version_no INT UNSIGNED; workflow_state VARCHAR(24) ascii-bin; valid_from DATETIME(6) NULL; valid_to DATETIME(6) NULL; content_hash BINARY(32); approval_decision_ref BINARY(16) NULL; M | UQ(workflow_id,version_no); FK workflow_id → gov_workflows(id); IX(state,valid_from); CHECK date pair is both null or valid half-open interval. | draft → active → retired; active version is immutable; approval reference is opaque; GOV-R. |
| gov_sod_rules | rule_code CODE; version_no INT UNSIGNED; rule_kind CODE; description NAME(300); content_hash BINARY(32); rule_state VARCHAR(24) ascii-bin; M | UQ(rule_code,version_no); IX(rule_code,rule_state). | Each row is one immutable rule version: draft → active → retired. There is intentionally no separate rule-version table. Active changes create a new row with incremented version_no. GOV-R. |
| gov_sod_rule_members | sod_rule_id BIGINT UNSIGNED; permission_id BIGINT UNSIGNED; member_kind CODE; created_at DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6); created_actor_kind VARCHAR(24) ascii-bin; created_actor_ref VARBINARY(128) | PK(sod_rule_id,permission_id,member_kind); FK sod_rule_id → gov_sod_rules(id); FK permission_id → iam_permissions(id); IX(permission_id,sod_rule_id). | Immutable once parent rule is active; exact parent ID pins membership to one version; GOV-R. |
| gov_workflow_steps | workflow_version_id BIGINT UNSIGNED; step_no SMALLINT UNSIGNED; required_permission_id BIGINT UNSIGNED; decision_rule_code CODE; sod_rule_id BIGINT UNSIGNED NULL; min_approvals SMALLINT UNSIGNED; step_state VARCHAR(24) ascii-bin; M | UQ(workflow_version_id,step_no); UQ(workflow_version_id,id); FK version, IAM permission and nullable exact SoD rule row; IX(permission_id); CHECK step_no>0 and min_approvals>0. | Draft-only mutation; immutable with active workflow version; GOV-R. |
| gov_workflow_requests | request_ref BINARY(16); request_kind CODE; target_domain CODE; target_ref VARBINARY(128); requester_principal_id BIGINT UNSIGNED; current_submission_no INT UNSIGNED DEFAULT 0; request_state VARCHAR(24) ascii-bin; idempotency_ref BINARY(16); expires_at DATETIME(6) NULL; M | UQ(request_ref); UQ(requester_principal_id,idempotency_ref); FK requester → iam_principals(id); IX(state,expires_at). target is an opaque typed reference, not a generic FK. | draft → submitted → in_review → approved/rejected/returned/cancelled/expired/superseded; state is a projection of immutable decisions and audit; GOV-R. |
| gov_workflow_submissions | request_id BIGINT UNSIGNED; submission_no INT UNSIGNED; workflow_version_id BIGINT UNSIGNED; submitted_by_principal_id BIGINT UNSIGNED; payload_hash BINARY(32); payload JSON; submitted_at DATETIME(6); A | UQ(request_id,submission_no); **UQ(request_id,submission_no,workflow_version_id)**; FK request, workflow version, submitter; IX(version,submitted_at). | Immutable; return/resubmission creates next number; payload is minimized and restricted; GOV-R. |
| gov_approval_decisions | decision_ref BINARY(16); request_id BIGINT UNSIGNED; submission_no INT UNSIGNED; workflow_version_id BIGINT UNSIGNED; workflow_step_id BIGINT UNSIGNED; decision_sequence_no INT UNSIGNED; decision_code CODE; decided_by_principal_id BIGINT UNSIGNED NULL; delegated_by_principal_id BIGINT UNSIGNED NULL; idempotency_ref BINARY(16); reason_code CODE; decided_at DATETIME(6); A | UQ(decision_ref); UQ(idempotency_ref); UQ(request_id,submission_no,workflow_version_id,workflow_step_id,decision_sequence_no); FK tuple (request_id,submission_no,workflow_version_id) → gov_workflow_submissions in that order; FK tuple (workflow_version_id,workflow_step_id) → gov_workflow_steps in that order; FK nullable actor/delegator → iam_principals(id). | Append-only; step decisions use approved/rejected/returned/escalated/delegated/cancelled/expired/superseded codes as workflow permits. Self-approval/SoD is checked transactionally; GOV-R. |
| gov_delegated_authorities | delegator_principal_id BIGINT UNSIGNED; delegate_principal_id BIGINT UNSIGNED; scope_kind VARCHAR(16) ascii-bin; legal_entity_id BIGINT UNSIGNED NULL; org_unit_id BIGINT UNSIGNED NULL; permitted_action_code CODE; approval_decision_id BIGINT UNSIGNED; idempotency_ref BINARY(16); valid_from DATETIME(6); valid_to DATETIME(6) NULL; delegation_state VARCHAR(24) ascii-bin; M | UQ(idempotency_ref); FK delegator/delegate → IAM principal; FK approval → gov_approval_decisions(id); FK nullable legal entity/unit → ORG; IX(delegate,state,valid_from); CHECK scope XOR below; CHECK no self-delegation; CHECK valid interval. | proposed → approved → active → expired/revoked; immutable creation key; retries reuse the same idempotency_ref; GOV-R. |
| gov_authority_limits | delegation_id BIGINT UNSIGNED; limit_code CODE; limit_value DECIMAL(18,4) NULL; limit_unit_code CODE NULL; M | UQ(delegation_id,limit_code); FK delegation; IX(limit_code). | Deferred until business/finance approval defines any meaningful limits; no limits are seeded; GOV-R. |
| gov_access_reviews | review_ref BINARY(16); scope_kind VARCHAR(16) ascii-bin; legal_entity_id BIGINT UNSIGNED NULL; org_unit_id BIGINT UNSIGNED NULL; reviewer_principal_id BIGINT UNSIGNED; review_state CODE; due_at DATETIME(6); closed_at DATETIME(6) NULL; M | UQ(review_ref); FK nullable entity/unit and reviewer; IX(state,due_at); CHECK scope XOR below. | planned → open → closed/cancelled/expired; GOV-R. |
| gov_access_review_items | review_id BIGINT UNSIGNED; role_grant_id BIGINT UNSIGNED; outcome_code CODE; reviewer_principal_id BIGINT UNSIGNED; reviewed_at DATETIME(6) NULL; reason_code CODE NULL; M | UQ(review_id,role_grant_id); FK review, IAM grant, reviewer; IX(role_grant_id). Reviewed principal is derived from grant to avoid duplicated identity. | pending → retain/revoke/escalate; GOV-R. |
| gov_exceptions | exception_ref BINARY(16); control_id BIGINT UNSIGNED; requester_principal_id BIGINT UNSIGNED; approval_decision_id BIGINT UNSIGNED NULL; exception_state CODE; reason_code CODE; mitigation_text NAME(1000); valid_from DATETIME(6); valid_to DATETIME(6) NULL; M | UQ(exception_ref); FK control, requester, nullable approval; IX(state,valid_to); CHECK valid interval. | requested → approved → active → expired/revoked/rejected; approved exception must have finite expiry; GOV-R. |
| gov_break_glass_records | incident_ref BINARY(16); principal_id BIGINT UNSIGNED; scope_kind VARCHAR(16) ascii-bin; legal_entity_id BIGINT UNSIGNED NULL; org_unit_id BIGINT UNSIGNED NULL; activation_decision_id BIGINT UNSIGNED NULL; reason_code CODE; started_at DATETIME(6); expires_at DATETIME(6); revoked_at DATETIME(6) NULL; review_due_at DATETIME(6); M | UQ(incident_ref); FK principal, nullable scope, nullable decision; IX(principal_id,expires_at); CHECK scope XOR below; CHECK expires_at>started_at. | requested → active → expired/revoked → reviewed; designated sensitive use requires independent approval; GOV-R. |

## Scope CHECK used on all four scope-bearing tables

Apply this invariant to iam_role_grants, gov_delegated_authorities, gov_access_reviews and gov_break_glass_records:

~~~text
(scope_kind = 'legal_entity' AND legal_entity_id IS NOT NULL AND org_unit_id IS NULL)
OR
(scope_kind = 'org_unit' AND org_unit_id IS NOT NULL AND legal_entity_id IS NULL)
~~~

Each table also has individual FKs on both nullable target columns. A null FK column is ignored by the FK; the CHECK is what requires exactly one populated target and matching discriminator. Application code repeats the check and verifies target lifecycle/effective dates and caller authority. No automatic scope inheritance is authorized.

The physical model audit found no other ORG/IAM/GOV table with all three columns named scope_kind, legal_entity_id and org_unit_id. IAM role grants use these columns directly. GOV decisions use a typed opaque target reference and do not hold a grant scope.

## Lifecycle and integrity behavior

- Workflow definitions, workflow versions, steps, permission requirements and SoD rule rows are immutable after activation. Changes create a new version row.
- Requests are mutable projections; all submitted payloads and decisions are append-only. Resubmission creates a new submission number; it never overwrites a previous version.
- Delegation is not copied permission. It is limited to one exact scope, action and interval. A revoke creates an audit event and ends the delegation.
- Access review decisions reference the grant directly. A requested revoke becomes a separate IAM revocation; review evidence remains in GOV.
- Exceptions and break-glass never create or extend an IAM grant by themselves. The specific IAM grant still needs its own policy-approved issuance and exact scope.
- Hard deletes are prohibited for approval, delegation, review, exception and break-glass history.

## Workflow ER diagram

~~~mermaid
erDiagram
    GOV_WORKFLOWS ||--o{ GOV_WORKFLOW_VERSIONS : versions
    GOV_WORKFLOW_VERSIONS ||--o{ GOV_WORKFLOW_STEPS : defines
    GOV_WORKFLOW_VERSIONS ||--o{ GOV_WORKFLOW_SUBMISSIONS : selected_version
    GOV_WORKFLOW_REQUESTS ||--o{ GOV_WORKFLOW_SUBMISSIONS : resubmits
    GOV_WORKFLOW_SUBMISSIONS ||--o{ GOV_APPROVAL_DECISIONS : receives
    GOV_WORKFLOW_STEPS ||--o{ GOV_APPROVAL_DECISIONS : exact_step
    GOV_SOD_RULES ||--o{ GOV_SOD_RULE_MEMBERS : immutable_version_members
    GOV_SOD_RULES ||--o{ GOV_WORKFLOW_STEPS : exact_sod_version
    IAM_PRINCIPALS ||--o{ GOV_WORKFLOW_REQUESTS : requests
    IAM_PRINCIPALS ||--o{ GOV_APPROVAL_DECISIONS : decides
~~~
