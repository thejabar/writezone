# WZ-IAM Physical Architecture

**Status:** Proposed physical design; no schema or authentication implementation approved
**Domain owner:** WZ-IAM
**Shared types:** [Corporate physical-model notation](README.md#physical-model-notation)

## Responsibility and security boundary

WZ-IAM owns corporate principals, identity bindings, roles, permissions, scoped grants and authorization. An authenticated identity is not itself a permission. A GOV approval is not an IAM grant. A runtime authorization decision is not an approval.

The existing consumer account/session path remains untouched. Corporate login is disabled until an identity provider, MFA, proofing, session and recovery policies are approved. Corporate routes must not treat the consumer session user_id as an IAM principal.

## Table specification

All tables are owned by IAM. FK actions are RESTRICT/RESTRICT. Common ID, M, CODE, NAME and T definitions are in the README. Fields are NOT NULL unless marked NULL; no default is implied unless written.

| Table | Additional columns and nullability | Keys, FKs, checks and indexes | Lifecycle, audit, retention |
|---|---|---|---|
| iam_principal_types | code CODE; name NAME(100); status_code VARCHAR(24) CHARACTER SET ascii COLLATE ascii_bin; M | UQ(code); IX(status_code). | proposed → active → retired; no category is seeded without approval; IAM-R. |
| iam_person_references | person_ref_key BINARY(16); record_state VARCHAR(24) ascii-bin; M | UQ(person_ref_key); IX(record_state); no email/name/natural-key index. | active → restricted/superseded; one person reference may relate to multiple principals; IAM-R. |
| iam_principals | principal_type_id BIGINT UNSIGNED; person_reference_id BIGINT UNSIGNED NULL; principal_state VARCHAR(24) ascii-bin; valid_from DATETIME(6); valid_to DATETIME(6) NULL; session_epoch BIGINT UNSIGNED DEFAULT 0; M | FK principal_type and nullable person reference; IX(person_reference_id, principal_state); IX(type,state); CHECK valid_to is null or later than valid_from; no unique person reference. | pending → active → suspended → revoked; revoked is terminal; IAM-R. |
| iam_identity_providers | provider_code CODE; protocol_code VARCHAR(8) CHARACTER SET ascii COLLATE ascii_bin; issuer VARBINARY(512); provider_state VARCHAR(24) ascii-bin; config_version INT UNSIGNED; M | UQ(provider_code); UQ(issuer); IX(provider_state); protocol is validated against approved protocol set. Issuer is immutable once used. | draft → enabled → disabled → retired; replacement uses a new provider row; contains no secret/private key; IAM-R. |
| iam_external_identities | provider_id BIGINT UNSIGNED; principal_id BIGINT UNSIGNED; issuer_snapshot VARBINARY(512); subject VARBINARY(255); active_binding_hash BINARY(32) NULL; identity_state VARCHAR(12) ascii-bin; bound_at DATETIME(6); revoked_at DATETIME(6) NULL; revocation_reason_code CODE NULL; M | UQ(active_binding_hash); FK provider and principal; IX(principal,state); IX(provider,state); CHECK active means hash non-null and revoked_at null; CHECK revoked means hash null and revoked_at non-null. App verifies issuer_snapshot equals provider issuer. | active → revoked only; preserve issuer/subject and audit; rebind creates a new row; IAM-R. |
| iam_workforce_identity_links | principal_id BIGINT UNSIGNED; workforce_reference_id BIGINT UNSIGNED; link_state VARCHAR(24) ascii-bin; link_source_code CODE; T; M | UQ(principal, workforce_reference, valid_from); FK principal and ORG workforce reference; IX(workforce_reference, valid_from); CHECK T. | proposed → active → revoked; app rejects overlapping duplicate active links; IAM-R. |
| iam_public_account_references | principal_id BIGINT UNSIGNED; public_user_id BIGINT UNSIGNED; link_policy_version INT UNSIGNED; link_state VARCHAR(24) ascii-bin; active_link_hash BINARY(32) NULL; T; M | UQ(active_link_hash); FK principal; deliberately no FK to consumer users until schema reconciliation and link policy approval; IX(principal,state); CHECK active link/hash consistency. | Disabled and empty by default; deferred. Linking never merges password, session, principal or grant; IAM-R. |
| iam_roles | role_code CODE; version_no INT UNSIGNED; name NAME(120); role_state VARCHAR(24) ascii-bin; T; M | UQ(role_code,version_no); IX(state,valid_from); CHECK T. | draft → active → retired; active version and permissions are immutable; IAM-R. |
| iam_permissions | permission_code CODE; description NAME(240); risk_class_code CODE; status_code VARCHAR(24) ascii-bin; M | UQ(permission_code); IX(risk_class_code,status_code). | proposed → active → retired; permission meaning requires owner approval; IAM-R. |
| iam_role_permissions | role_id BIGINT UNSIGNED; permission_id BIGINT UNSIGNED; created_at DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6); created_actor_kind VARCHAR(24) CHARACTER SET ascii COLLATE ascii_bin; created_actor_ref VARBINARY(128) | PK(role_id,permission_id); FK role and permission; IX(permission_id,role_id). | Immutable composition for a specific role version; change creates a new role version; IAM-R. |
| iam_role_grants | principal_id BIGINT UNSIGNED; role_id BIGINT UNSIGNED; scope_kind VARCHAR(16) CHARACTER SET ascii COLLATE ascii_bin; legal_entity_id BIGINT UNSIGNED NULL; org_unit_id BIGINT UNSIGNED NULL; valid_from DATETIME(6); expires_at DATETIME(6) NULL; granted_by_principal_id BIGINT UNSIGNED; approval_decision_ref BINARY(16) NULL; idempotency_ref BINARY(16); issued_at DATETIME(6) | UQ(idempotency_ref); FK principal, role, grantor, nullable legal entity and nullable unit; IX(principal,valid_from,expires_at); IX(role,legal_entity_id,valid_from); IX(role,org_unit_id,valid_from); CHECK exact scope-kind XOR; CHECK expiry > valid_from. | Immutable issued grant; active state is derived from valid time and absence of revocation; approval_decision_ref is opaque and has no GOV FK; IAM-R. |
| iam_grant_revocations | role_grant_id BIGINT UNSIGNED; revoked_by_principal_id BIGINT UNSIGNED; revoked_at DATETIME(6); reason_code CODE; correlation_ref BINARY(16); M | UQ(role_grant_id); FK grant and revoking principal; IX(revoked_at). | One-way append: a grant cannot be reactivated; new access uses a new grant; IAM-R. |
| iam_service_identity_metadata | service_principal_id BIGINT UNSIGNED; service_code CODE; owner_unit_id BIGINT UNSIGNED; purpose NAME(240); service_state VARCHAR(24) ascii-bin; M | PK/FK service_principal_id → iam_principals(id); FK owner unit → org_units(id); UQ(service_code). | pending → active → suspended → retired; deferred until a service identity is approved; IAM-R. |
| iam_service_credential_metadata | service_principal_id BIGINT UNSIGNED; key_id CODE; secret_reference VARBINARY(128); issued_at DATETIME(6); expires_at DATETIME(6) NULL; revoked_at DATETIME(6) NULL; credential_state VARCHAR(24) ascii-bin; M | UQ(service_principal_id,key_id); FK service principal; IX(state,expires_at). | issued → active → revoked/expired; secret_reference points to external secret storage and is not the secret; deferred; IAM-R. |
| iam_authorization_decisions | Removed from initial schema; deferred. | The previous proposal duplicated durable security evidence owned by gov_audit_events. Do not create both without a separate, approved telemetry purpose. | If later justified, define retention, access and volume policy first; IAM-R. |

## Scope integrity

The following CHECK is required on iam_role_grants in addition to FKs to both target tables:

~~~text
(scope_kind = 'legal_entity' AND legal_entity_id IS NOT NULL AND org_unit_id IS NULL)
OR
(scope_kind = 'org_unit' AND org_unit_id IS NOT NULL AND legal_entity_id IS NULL)
~~~

This enforces exactly one stored scope, including the matching scope_kind. Application validation repeats the check and verifies that the selected target is active at the grant's effective time. Descendant-unit inheritance is off. A scoped grant cannot be inferred from a position, manager, email or public account.

## Identity binding and lifecycle

The active identity key is SHA-256 of the exact issuer bytes, one NUL separator byte, then the exact subject bytes. The application computes active_binding_hash; the unique index prevents two non-null hashes. Before binding or recovering from a conflict, compare the stored issuer and subject bytes to guard against a hash collision. Revocation clears the hash and stamps revoked_at while preserving original bytes. Rebinding inserts a new row after independent proofing and approval.

Email is not a principal key. Disabling a provider or principal immediately denies authentication even when old binding evidence remains. Rehire does not restore expired grants. A person may have multiple principals; each principal has independent bindings, grants, session context and revocation.

## Authorization flow

1. Verify the assertion against an enabled, approved provider and required authentication policy.
2. Resolve exact issuer/subject to one active external identity and one active principal.
3. Check principal and workforce-link eligibility as required by the permission.
4. Load versioned grants that are effective now and have no revocation row.
5. Resolve the resource to its owning legal entity or unit and compare exact scope.
6. Evaluate permission and context; deny by default.
7. Record sensitive decisions through the approved GOV audit interface.

~~~mermaid
flowchart LR
    IDP[Approved Identity Provider] --> EI[External identity: exact issuer + subject]
    EI --> P[Corporate principal]
    P --> G[Active, unexpired, unrevoked role grants]
    G --> AZ[Default-deny authorization]
    R[Resource owner and request context] --> AZ
    AZ --> D[Allow or deny]
~~~

## Consumer-session isolation

Corporate login must use a separate corporate session identity/context and middleware. Do not call the existing consumer Auth login with a corporate ID; do not use consumer user_id as an IAM principal. Corporate session cookie name, path/domain, storage, CSRF boundary, expiry and revocation policy require security approval. This specification does not change consumer authentication.

## Retention

Identity, grant and revocation history is restricted and retained according to an approved security/legal schedule. No period is specified. Provider credentials, client secrets and private signing keys are never stored in these tables.
