# WZ-ORG Physical Architecture

**Status:** Proposed physical design; no schema or implementation approved
**Domain owner:** WZ-ORG
**Shared type and metadata conventions:** [Corporate architecture documents](README.md#physical-model-notation)

## Responsibility and boundaries

WZ-ORG owns the approved corporate organisation catalogue, effective-dated hierarchy, position records, accepted position-assignment graph, workforce references and reporting relationships.

The workforce authority owns employment/contract facts and status. WZ-ORG stores only the minimum effective projection needed for organizational placement and authorization context. The workforce authority has not been identified. With no approved HRMS, manual creation of legal entities, units and positions may be operated under an approved two-person procedure; this does not make WZ-ORG the workforce-status authority.

WZ-ORG must not FK to WZ-IAM for actor metadata or to WZ-INT for source registry data. Creation actor metadata is an opaque attribution (created_actor_kind plus created_actor_ref). Source references use an approved origin code and opaque source key, not an integration table.

## Table specification

In the table below, all columns from ID, M and T are exactly as defined in the README. Every FK uses RESTRICT for delete and update. All table ownership is WZ-ORG. Any status or relationship code list marked proposed requires domain-owner approval before seed data is created.

| Table | Additional columns and nullability | Keys, FKs, checks and indexes | Lifecycle, audit, retention |
|---|---|---|---|
| org_jurisdictions | code CODE; name NAME(160); kind_code CODE; parent_jurisdiction_id BIGINT UNSIGNED NULL; status_code VARCHAR(24) CHARACTER SET ascii COLLATE ascii_bin; T; M | UQ(code); FK parent_jurisdiction_id → org_jurisdictions(id); IX(parent_jurisdiction_id); CHECK parent is null or parent_id ≠ id; CHECK T. | proposed → active → retired; preserve references and history; ORG-H. |
| org_legal_entities | code CODE; registered_name NAME(200); display_name NAME(200) NULL; entity_kind_code CODE; status_code VARCHAR(24) ascii-bin; T; M | UQ(code); IX(status_code, valid_from); CHECK T. | proposed → active → suspended/retired; no legal identifier number is stored by default; ORG-H. |
| org_legal_entity_jurisdictions | legal_entity_id BIGINT UNSIGNED; jurisdiction_id BIGINT UNSIGNED; relationship_code CODE; T; M | UQ(legal_entity_id, jurisdiction_id, relationship_code, valid_from); FK entity and jurisdiction; IX(jurisdiction_id, legal_entity_id); CHECK T. | active → closed; any single primary-jurisdiction rule is application-serialized and requires legal approval; ORG-H. |
| org_unit_types | code CODE; name NAME(100); status_code VARCHAR(24) ascii-bin; M | UQ(code); IX(status_code). | proposed → active → retired; no divisions/departments are presumed; ORG-H. |
| org_units | code CODE; name NAME(160); unit_type_id BIGINT UNSIGNED; source_origin_code CODE; source_record_key VARBINARY(128); status_code VARCHAR(24) ascii-bin; T; M | UQ(code); UQ(source_origin_code, source_record_key); FK unit_type_id → org_unit_types(id); IX(unit_type_id, status_code); CHECK T. | proposed → active → suspended → retired; manual keys are opaque generated references; ORG-H. |
| org_unit_relationships | parent_unit_id BIGINT UNSIGNED; child_unit_id BIGINT UNSIGNED; relationship_code CODE; status_code VARCHAR(24) ascii-bin; T; M | UQ(parent_unit_id, child_unit_id, relationship_code, valid_from); FK both columns → org_units(id); IX(child_unit_id, valid_from); IX(parent_unit_id, valid_from); CHECK parent ≠ child; CHECK T. | active → closed; temporal single-parent, same-governing-entity and cycle rules are serialized in the application; ORG-H. |
| org_legal_entity_units | legal_entity_id BIGINT UNSIGNED; unit_id BIGINT UNSIGNED; association_code CODE; status_code VARCHAR(24) ascii-bin; T; M | UQ(legal_entity_id, unit_id, association_code, valid_from); UQ(unit_id, association_code, valid_from); FK entity/unit; IX(unit_id, valid_from); CHECK T. | active → closed; exactly one governing entity per unit/time is enforced transactionally; additional operating associations require approval; ORG-H. |
| org_regions | code CODE; name NAME(120); status_code VARCHAR(24) ascii-bin; M | UQ(code); IX(status_code). | proposed → active → retired; ORG-H. |
| org_locations | code CODE; name NAME(160); region_id BIGINT UNSIGNED; jurisdiction_id BIGINT UNSIGNED NULL; status_code VARCHAR(24) ascii-bin; T; M | UQ(code); FK region and nullable jurisdiction; IX(region_id, status_code); CHECK T. | proposed → active → suspended/retired; no home address or detailed personal location; ORG-H. |
| org_positions | org_unit_id BIGINT UNSIGNED; code CODE; title NAME(160); occupancy_mode VARCHAR(12) CHARACTER SET ascii COLLATE ascii_bin; max_occupancy DECIMAL(7,4); status_code VARCHAR(24) ascii-bin; T; M | UQ(org_unit_id, code); FK unit; IX(unit,status); CHECK mode is exclusive or shared; CHECK capacity > 0; CHECK exclusive capacity = 1; CHECK T. | draft → active → suspended → retired; vacancy is derived from effective assignments, not a duplicate state; ORG-H. |
| org_workforce_references | workforce_ref_key BINARY(16); source_authority_code CODE; source_record_key VARBINARY(128); record_state VARCHAR(24) ascii-bin; source_observed_at DATETIME(6) NULL; M | UQ(workforce_ref_key); UQ(source_authority_code, source_record_key); IX(record_state). | pending → active → suspended → archived; contains no name, email, compensation or HR document; ORG-H. |
| org_workforce_engagements | workforce_reference_id BIGINT UNSIGNED; engagement_kind_code CODE; source_authority_code CODE; source_record_key VARBINARY(128); engagement_state VARCHAR(24) ascii-bin; T; status_observed_at DATETIME(6); M | UQ(source_authority_code, source_record_key); FK workforce_reference_id → org_workforce_references(id); IX(workforce_reference_id, valid_from); CHECK T. | proposed → active → suspended → ended/void; source status remains authoritative; ORG-H. |
| org_position_assignments | engagement_id BIGINT UNSIGNED; position_id BIGINT UNSIGNED; assignment_kind VARCHAR(12) CHARACTER SET ascii COLLATE ascii_bin; occupancy_fraction DECIMAL(7,4); change_ref BINARY(16); approval_decision_ref BINARY(16) NULL; assignment_state VARCHAR(24) ascii-bin; T; M | UQ(change_ref); FK engagement and position; IX(engagement_id, valid_from); IX(position_id, valid_from); CHECK kind primary/secondary; CHECK 0 < fraction ≤ 1; CHECK T. approval_decision_ref is opaque and has no GOV FK. | proposed → active → superseded/cancelled; retry uses the same change_ref; temporal overlap and capacity use serialized transaction; ORG-H. |
| org_reporting_relationships | subordinate_assignment_id BIGINT UNSIGNED; manager_assignment_id BIGINT UNSIGNED; relationship_kind VARCHAR(16) CHARACTER SET ascii COLLATE ascii_bin; status_code VARCHAR(24) ascii-bin; T; M | UQ(subordinate_assignment_id, manager_assignment_id, relationship_kind, valid_from); FK both → org_position_assignments(id); IX(subordinate_assignment_id, relationship_kind, valid_from); IX(manager_assignment_id, valid_from); CHECK not self and T. | proposed → active → closed; cardinality, graph-cycle and assignment-containment checks are serialized; ORG-H. |
| org_cost_centre_references | erp_namespace_code CODE; external_cost_centre_key VARBINARY(128); status_code VARCHAR(24) ascii-bin; M | UQ(erp_namespace_code, external_cost_centre_key); IX(status_code); no FK to ERP or INT. | proposed → active → retired; ERP retains master authority; deferred; ORG-H. |
| org_cost_centre_mappings | unit_id BIGINT UNSIGNED; cost_centre_reference_id BIGINT UNSIGNED; mapping_kind CODE; status_code VARCHAR(24) ascii-bin; T; M | UQ(unit_id, cost_centre_reference_id, mapping_kind, valid_from); FK unit and reference; IX(cost_centre_reference_id, valid_from); CHECK T. | active → closed; allocation and financial semantics require finance approval; deferred; ORG-H. |

## Temporal and concurrency rules

The database can enforce individual interval order, direct FKs, direct self-edge rejection, occupancy bounds and declared uniqueness. It cannot enforce interval non-overlap, total occupancy, single primary assignment across engagements or graph acyclicity with ordinary unique indexes.

All writers use one service and transaction with this lock order:

1. Lock affected governing legal-entity rows in ascending ID order.
2. Lock workforce-reference rows in ascending ID order.
3. Lock position rows in ascending ID order.
4. Lock affected engagements and assignments in ascending primary-key order.
5. Re-read effective intervals, occupancy and relationship graph; validate.
6. Close old interval at T, insert replacement starting at T, append the GOV audit event in the same transaction.
7. Commit.

The transaction checks at most one overlapping primary assignment per worker; shared occupancy sum ≤ position capacity; no overlapping occupancy for exclusive positions; manager/subordinate assignments cover the relationship interval; no operational/functional hierarchy cycle; and the approved relationship cardinality. Cross-entity hierarchy and operational reporting are denied in the initial model. Deadlock retry is bounded to three total attempts using the same idempotency reference; exhaustion returns failure without partial state.

## ER diagram

~~~mermaid
erDiagram
    ORG_JURISDICTIONS ||--o{ ORG_JURISDICTIONS : parent
    ORG_LEGAL_ENTITIES ||--o{ ORG_LEGAL_ENTITY_JURISDICTIONS : jurisdiction_link
    ORG_JURISDICTIONS ||--o{ ORG_LEGAL_ENTITY_JURISDICTIONS : applies_to
    ORG_UNIT_TYPES ||--o{ ORG_UNITS : types
    ORG_UNITS ||--o{ ORG_UNIT_RELATIONSHIPS : parent_or_child
    ORG_LEGAL_ENTITIES ||--o{ ORG_LEGAL_ENTITY_UNITS : governs
    ORG_UNITS ||--o{ ORG_LEGAL_ENTITY_UNITS : associated
    ORG_UNITS ||--o{ ORG_POSITIONS : contains
    ORG_WORKFORCE_REFERENCES ||--o{ ORG_WORKFORCE_ENGAGEMENTS : engages
    ORG_WORKFORCE_ENGAGEMENTS ||--o{ ORG_POSITION_ASSIGNMENTS : assigned
    ORG_POSITIONS ||--o{ ORG_POSITION_ASSIGNMENTS : occupied
    ORG_POSITION_ASSIGNMENTS ||--o{ ORG_REPORTING_RELATIONSHIPS : reports
    ORG_REGIONS ||--o{ ORG_LOCATIONS : contains
    ORG_COST_CENTRE_REFERENCES ||--o{ ORG_COST_CENTRE_MAPPINGS : mapped
    ORG_UNITS ||--o{ ORG_COST_CENTRE_MAPPINGS : owns_mapping
~~~

The diagram shows FK relationships only. Effective constraints, uniqueness, and approval references remain as described in the table specifications.
