# WZ-CORP Domain Dependencies and FK Register

**Status:** Proposed physical dependency plan; not executed
**Scope:** WZ-ORG, WZ-IAM and WZ-GOV tables in Corporate documents 01–04
**Authority:** [Repository architecture authority](../ARCHITECTURE_AUTHORITY.md)

## Ownership and dependency direction

WZ-ORG owns organization, positions, workforce references and accepted assignments. WZ-IAM owns principals, external bindings, roles, permissions and grants. WZ-GOV owns policy/approval records and audit evidence. All are logical modules in the existing PHP modular monolith and proposed shared database, not separately deployed services.

WZ-IAM may FK to WZ-ORG workforce references and scope rows. WZ-GOV may FK to IAM principals, permissions and grants. WZ-IAM has no FK back to GOV: its approval_decision_ref is an opaque BINARY(16) reference to GOV decision_ref, checked by the grant service. ORG uses opaque actor/source references. No ORG/IAM/GOV table FK points to WZ-INT. No WZ-INT tables are proposed here.

~~~mermaid
flowchart LR
    ORG[WZ-ORG] -->|IAM references ORG workforce and scope IDs| IAM[WZ-IAM]
    IAM -->|GOV references principals, permissions and grants| GOV[WZ-GOV]
    IAM -.->|opaque approval_decision_ref; no FK| GOV
    ORG -.->|opaque actor/source references; no FK| IAM
~~~

## Complete FK-reference audit matrix

All single-column IDs below are BIGINT UNSIGNED. All FKs use ON DELETE RESTRICT ON UPDATE RESTRICT. “PK(id)” means the target primary key exists in the proposed table specification. Composite target keys are stated in their exact column order. This is a design audit, not proof of MariaDB acceptance.

### WZ-ORG references

| Source table and FK columns | Target table and referenced key | Result / notes |
|---|---|---|
| org_jurisdictions.parent_jurisdiction_id | org_jurisdictions.PK(id) | Valid self-FK; CHECK rejects self-parent. |
| org_legal_entity_jurisdictions.legal_entity_id | org_legal_entities.PK(id) | Valid. |
| org_legal_entity_jurisdictions.jurisdiction_id | org_jurisdictions.PK(id) | Valid. |
| org_units.unit_type_id | org_unit_types.PK(id) | Valid. |
| org_unit_relationships.parent_unit_id | org_units.PK(id) | Valid. |
| org_unit_relationships.child_unit_id | org_units.PK(id) | Valid. |
| org_legal_entity_units.legal_entity_id | org_legal_entities.PK(id) | Valid. |
| org_legal_entity_units.unit_id | org_units.PK(id) | Valid. |
| org_locations.region_id | org_regions.PK(id) | Valid. |
| org_locations.jurisdiction_id | org_jurisdictions.PK(id) | Valid nullable FK. |
| org_positions.org_unit_id | org_units.PK(id) | Valid. |
| org_workforce_engagements.workforce_reference_id | org_workforce_references.PK(id) | Valid. |
| org_position_assignments.engagement_id | org_workforce_engagements.PK(id) | Valid. |
| org_position_assignments.position_id | org_positions.PK(id) | Valid. |
| org_reporting_relationships.subordinate_assignment_id | org_position_assignments.PK(id) | Valid. |
| org_reporting_relationships.manager_assignment_id | org_position_assignments.PK(id) | Valid. |
| org_cost_centre_mappings.unit_id | org_units.PK(id) | Valid; deferred table. |
| org_cost_centre_mappings.cost_centre_reference_id | org_cost_centre_references.PK(id) | Valid; deferred table. |

No FK is defined for source_authority_code, source_record_key, external ERP identifiers, approval_decision_ref or actor references. These are explicitly opaque provenance/evidence references; they do not create a dependency on WZ-INT, ERP or IAM.

### WZ-IAM references

| Source table and FK columns | Target table and referenced key | Result / notes |
|---|---|---|
| iam_principals.principal_type_id | iam_principal_types.PK(id) | Valid. |
| iam_principals.person_reference_id | iam_person_references.PK(id) | Valid nullable FK; no unique constraint on person_reference_id. |
| iam_external_identities.provider_id | iam_identity_providers.PK(id) | Valid. |
| iam_external_identities.principal_id | iam_principals.PK(id) | Valid. |
| iam_workforce_identity_links.principal_id | iam_principals.PK(id) | Valid. |
| iam_workforce_identity_links.workforce_reference_id | org_workforce_references.PK(id) | Valid one-way IAM→ORG dependency. |
| iam_public_account_references.principal_id | iam_principals.PK(id) | Valid; optional table deferred. |
| iam_roles | — | No FK. |
| iam_permissions | — | No FK. |
| iam_role_permissions.role_id | iam_roles.PK(id) | Valid. |
| iam_role_permissions.permission_id | iam_permissions.PK(id) | Valid. |
| iam_role_grants.principal_id | iam_principals.PK(id) | Valid. |
| iam_role_grants.role_id | iam_roles.PK(id) | Valid. |
| iam_role_grants.granted_by_principal_id | iam_principals.PK(id) | Valid. |
| iam_role_grants.legal_entity_id | org_legal_entities.PK(id) | Valid nullable FK, paired with scope CHECK. |
| iam_role_grants.org_unit_id | org_units.PK(id) | Valid nullable FK, paired with scope CHECK. |
| iam_grant_revocations.role_grant_id | iam_role_grants.PK(id) | Valid; UNIQUE(role_grant_id) permits one revocation. |
| iam_grant_revocations.revoked_by_principal_id | iam_principals.PK(id) | Valid. |
| iam_service_identity_metadata.service_principal_id | iam_principals.PK(id) | Valid PK/FK; deferred table. |
| iam_service_identity_metadata.owner_unit_id | org_units.PK(id) | Valid; deferred table. |
| iam_service_credential_metadata.service_principal_id | iam_principals.PK(id) | Valid; deferred table. |

iam_external_identities.active_binding_hash, IAM approval_decision_ref, source codes, secret_reference and public_user_id are not FKs. Public account linking stays disabled and has no consumer-table FK until schema reconciliation and an explicit link policy. No IAM table FK points to GOV.

### WZ-GOV references

| Source table and FK columns | Target table and referenced key | Result / notes |
|---|---|---|
| gov_policy_versions.policy_id | gov_policies.PK(id) | Valid. |
| gov_controls.policy_version_id | gov_policy_versions.PK(id) | Valid. |
| gov_workflow_versions.workflow_id | gov_workflows.PK(id) | Valid. |
| gov_sod_rule_members.sod_rule_id | gov_sod_rules.PK(id) | Valid; the rule row itself is one immutable version. |
| gov_sod_rule_members.permission_id | iam_permissions.PK(id) | Valid one-way GOV→IAM dependency. |
| gov_workflow_steps.workflow_version_id | gov_workflow_versions.PK(id) | Valid; UQ(workflow_version_id,id) supports the composite decision FK. |
| gov_workflow_steps.required_permission_id | iam_permissions.PK(id) | Valid. |
| gov_workflow_steps.sod_rule_id | gov_sod_rules.PK(id) | Valid nullable FK to one exact rule version. |
| gov_workflow_requests.requester_principal_id | iam_principals.PK(id) | Valid. |
| gov_workflow_submissions.request_id | gov_workflow_requests.PK(id) | Valid. |
| gov_workflow_submissions.workflow_version_id | gov_workflow_versions.PK(id) | Valid. |
| gov_workflow_submissions.submitted_by_principal_id | iam_principals.PK(id) | Valid. |
| gov_approval_decisions.(request_id,submission_no,workflow_version_id) | gov_workflow_submissions.UQ(request_id,submission_no,workflow_version_id) | Valid exact-order composite FK; prevents version mismatch. |
| gov_approval_decisions.(workflow_version_id,workflow_step_id) | gov_workflow_steps.UQ(workflow_version_id,id) | Valid exact-order composite FK; prevents step/version mismatch. |
| gov_approval_decisions.decided_by_principal_id | iam_principals.PK(id) | Valid nullable actor for system-generated expiry/cancellation records. |
| gov_approval_decisions.delegated_by_principal_id | iam_principals.PK(id) | Valid nullable FK. |
| gov_delegated_authorities.delegator_principal_id | iam_principals.PK(id) | Valid. |
| gov_delegated_authorities.delegate_principal_id | iam_principals.PK(id) | Valid. |
| gov_delegated_authorities.legal_entity_id | org_legal_entities.PK(id) | Valid nullable FK; exact-one-scope CHECK applies. |
| gov_delegated_authorities.org_unit_id | org_units.PK(id) | Valid nullable FK; exact-one-scope CHECK applies. |
| gov_delegated_authorities.approval_decision_id | gov_approval_decisions.PK(id) | Valid; decisions have only opaque target refs, so no cycle. |
| gov_authority_limits.delegation_id | gov_delegated_authorities.PK(id) | Valid; deferred table. |
| gov_access_reviews.legal_entity_id | org_legal_entities.PK(id) | Valid nullable FK; exact-one-scope CHECK applies. |
| gov_access_reviews.org_unit_id | org_units.PK(id) | Valid nullable FK; exact-one-scope CHECK applies. |
| gov_access_reviews.reviewer_principal_id | iam_principals.PK(id) | Valid. |
| gov_access_review_items.review_id | gov_access_reviews.PK(id) | Valid. |
| gov_access_review_items.role_grant_id | iam_role_grants.PK(id) | Valid. |
| gov_access_review_items.reviewer_principal_id | iam_principals.PK(id) | Valid. |
| gov_exceptions.control_id | gov_controls.PK(id) | Valid. |
| gov_exceptions.requester_principal_id | iam_principals.PK(id) | Valid. |
| gov_exceptions.approval_decision_id | gov_approval_decisions.PK(id) | Valid nullable FK. |
| gov_break_glass_records.principal_id | iam_principals.PK(id) | Valid. |
| gov_break_glass_records.legal_entity_id | org_legal_entities.PK(id) | Valid nullable FK; exact-one-scope CHECK applies. |
| gov_break_glass_records.org_unit_id | org_units.PK(id) | Valid nullable FK; exact-one-scope CHECK applies. |
| gov_break_glass_records.activation_decision_id | gov_approval_decisions.PK(id) | Valid nullable FK. |
| gov_audit_stream_heads.stream_id | gov_audit_streams.PK(id) | Valid; stream_id is also the head PK. |
| gov_audit_events.stream_id | gov_audit_streams.PK(id) | Valid. |
| gov_audit_events.actor_principal_id | iam_principals.PK(id) | Valid nullable FK. |
| gov_audit_batch_reservations.stream_id | gov_audit_stream_heads.PK(stream_id) | Valid; current reservation PK is stream_id. |
| gov_audit_batch_attempts.stream_id | gov_audit_streams.PK(id) | Valid. |
| gov_audit_seal_authorizations.stream_id | gov_audit_streams.PK(id) | Valid. |
| gov_audit_seal_authorizations.(attempt_id,stream_id,reservation_ref,fencing_token,first_sequence_no,last_sequence_no,manifest_hash,operation_code,outcome_code) | gov_audit_batch_attempts.UQ(id,stream_id,reservation_ref,fencing_token,first_sequence_no,last_sequence_no,manifest_hash,operation_code,outcome_code) | Valid exact-order composite FK. Authorization CHECK pins operation/outcome to seal_authorize/authorized. |
| gov_audit_batches.stream_id | gov_audit_streams.PK(id) | Valid. |
| gov_audit_batches.(seal_authorization_id,stream_id,reservation_ref,fencing_token,first_sequence_no,last_sequence_no,manifest_hash) | gov_audit_seal_authorizations.UQ(id,stream_id,reservation_ref,fencing_token,first_sequence_no,last_sequence_no,manifest_hash) | Valid exact-order composite FK; batch carries the certified attempt tuple. |
| gov_audit_batch_items.(batch_id,stream_id) | gov_audit_batches.UQ(id,stream_id) | Valid exact-order composite FK. |
| gov_audit_batch_items.(event_id,stream_id,sequence_no,event_hash) | gov_audit_events.UQ(id,stream_id,sequence_no,event_hash) | Valid exact-order composite FK. |
| gov_archive_bundles.batch_id | gov_audit_batches.PK(id) | Valid. |
| gov_evidence_manifests.(batch_id,manifest_hash) | gov_audit_batches.UQ(id,manifest_hash) | Valid exact-order composite FK; binds the stored signed manifest digest to the digest authorized for the sealed batch. |
| gov_evidence_verifications.(manifest_id,batch_id) | gov_evidence_manifests.UQ(id,batch_id) | Valid exact-order composite FK. |
| gov_evidence_verifications.(archive_bundle_id,batch_id) | gov_archive_bundles.UQ(id,batch_id) | Valid exact-order composite FK; Finding 9 key is explicitly present. |

Opaque GOV references are gov_policy_versions.approval_decision_ref, gov_workflow_versions.approval_decision_ref, and gov_workflow_requests.(target_domain,target_ref). They are checked by their owning service because they intentionally avoid an IAM→GOV or same-domain approval-cycle FK. Audit storage and external request references are also opaque provider references.

## Scope constraints audit

Scope-bearing tables are exactly iam_role_grants, gov_delegated_authorities, gov_access_reviews and gov_break_glass_records. Each declares both nullable target FKs and the same XOR/type CHECK documented in WZ-CORP-03. No other proposed ORG/IAM/GOV table contains the scope_kind/legal_entity_id/org_unit_id triple.

## Creation dependency order

1. ORG catalogues and internal references.
2. IAM principal types, people, principals, providers, permissions, roles, workforce links and grants. ORG tables already exist; IAM scope FKs point only to ORG.
3. GOV policy/workflow/SoD catalogues; then requests, submissions and decisions; then governance actions.
4. GOV audit streams/events; then reservations/attempts/seal authorizations/batches/items; then archive/manifests/verifications.

GOV references IAM, but IAM does not FK to GOV. GOV's back-reference to IAM grants is one-way. No circular FK dependency exists. No INT registry or external system is required for initial manual ORG catalogue creation.

## Database vs application enforcement

Database enforcement: PK, exact unique target keys, FKs, RESTRICT actions, scope XOR checks, direct self-edge checks, per-event unique stream sequence, one reservation row per stream, one batch membership per event, valid single-row intervals and numeric bounds.

Application transaction enforcement: current lease/fencing comparison, next-unsealed-range comparison, batch membership continuity/count and hashes, role approval applicability, self-approval/SoD, effective interval non-overlap, position capacity, one primary assignment at an instant, hierarchy cycles, reporting cardinality and target effectiveness. Every such writer must use the same serialized domain service; direct runtime table writes must be denied.

## WP1.5.2.1 finding closure register

| Finding | Resolution in the proposed model | Canonical detail |
|---|---|---|
| Finding 9 — Archive bundle composite FK | Add the exact unique target key UQ(id,batch_id); verification references archive bundle and batch together. | WZ-CORP-04, composite-key correction. |
| Finding 10 — Delegation idempotency | Use idempotency_ref BINARY(16) NOT NULL UNIQUE, immutable after creation; retries reuse it. | WZ-CORP-03, gov_delegated_authorities. |
| Finding 11 — SoD versioning | One gov_sod_rules row is one immutable version; increment version_no for changes; no separate version table. | WZ-CORP-03, gov_sod_rules and gov_sod_rule_members. |
| Finding 12 — Scope consistency | The four scope-bearing tables use the same XOR/type CHECK and individual FKs; no other table has the scope triple. | WZ-CORP-02 and WZ-CORP-03, scope constraints audit. |
| Finding 13 — Audit reservation proof | An immutable authorized attempt is bound through a seal-authorization composite FK to the batch tuple; the manifest FK also matches the batch hash. The writer must verify current reservation/fence/lease under lock. | WZ-CORP-04, reservation-to-batch proof. |

These are proposed schema and transaction rules. Their DDL behavior and concurrency properties remain untested on MariaDB; the application checks cannot protect against unauthorized direct database writes.
