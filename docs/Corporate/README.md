# WZ-CORP Architecture Documents

**Status:** Proposed design baseline; not implementation authority
**Owner:** Corporate architecture decision authority (not yet appointed)
**Last reviewed:** 2026-11-07

## Authority and status

These documents describe a proposed WriteZone corporate architecture. They do not establish that a capability, enterprise system, legal entity, role, person or production database state exists. The repository's architecture authority remains [docs/ARCHITECTURE_AUTHORITY.md](../ARCHITECTURE_AUTHORITY.md); implementation claims require source, test and environment evidence.

The original CORP decision register is unavailable. WP1.4's supplied command references CORP-01 through CORP-23; WP1.5.1 references CORP-01 through CORP-22. No mapping or original status is inferred here.

Labels used throughout:

- **APPROVED PRINCIPLE** — explicitly accepted as a governing design principle.
- **PROPOSED** — technical recommendation awaiting the relevant approval.
- **HUMAN DECISION REQUIRED** — business, executive, legal, privacy, security, finance or operations choice.
- **TECHNICAL REQUIREMENT** — required for a safe implementation but not evidence of compatibility.
- **NOT TESTED** — no compatible database, production or runtime test was performed.

## Reading order

1. [Architecture and decision status](WZ-CORP-00-Architecture-and-Decision-Status.md)
2. [ORG physical architecture](WZ-CORP-01-ORG-Physical-Architecture.md)
3. [IAM physical architecture](WZ-CORP-02-IAM-Physical-Architecture.md)
4. [GOV workflow and approval](WZ-CORP-03-GOV-Workflow-and-Approval.md)
5. [GOV audit and evidence](WZ-CORP-04-GOV-Audit-and-Evidence.md)
6. [Domain dependencies and FK register](WZ-CORP-05-Domain-Dependencies-and-FK-Register.md)
7. [Schema reconciliation and migration readiness](WZ-CORP-06-Schema-Migration-and-Readiness.md)
8. [Technical validation and implementation readiness](WZ-CORP-07-Validation-and-Implementation-Readiness.md)

## Physical-model notation

The specifications are documentation only and must not be executed as SQL.

- **ID:** Default surrogate key: id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY. If a table specification explicitly declares PK(...), that table-specific key replaces the default and no separate id column is implied unless listed.
- **CODE:** VARCHAR(64) CHARACTER SET ascii COLLATE ascii_bin NOT NULL.
- **NAME(n):** VARCHAR(n) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL.
- **M (mutable audit metadata):** created_at DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6); created_actor_kind VARCHAR(24) CHARACTER SET ascii COLLATE ascii_bin NOT NULL; created_actor_ref VARBINARY(128) NOT NULL; updated_at DATETIME(6) NULL; updated_actor_kind VARCHAR(24) CHARACTER SET ascii COLLATE ascii_bin NULL; updated_actor_ref VARBINARY(128) NULL. A check requires the two update-actor fields to be both null or both non-null.
- **A (append-only actor metadata):** recorded_at DATETIME(6) NOT NULL; actor_principal_id BIGINT UNSIGNED NULL; actor_kind VARCHAR(24) CHARACTER SET ascii COLLATE ascii_bin NOT NULL; actor_ref VARBINARY(128) NOT NULL; correlation_ref BINARY(16) NULL.
- **T (effective interval):** valid_from DATETIME(6) NOT NULL; valid_to DATETIME(6) NULL; interval is half-open [valid_from, valid_to). A check requires valid_to IS NULL OR valid_to > valid_from.
- All timestamps are UTC. Fields not marked nullable are NOT NULL; fields have no default unless specified.
- Unless explicitly stated otherwise, every FK uses ON DELETE RESTRICT ON UPDATE RESTRICT. No corporate history is cascade-deleted.
- All text tables use InnoDB and utf8mb4 / utf8mb4_unicode_ci. Codes and external identity values use the explicitly stated ASCII/binary types.

MariaDB behavior for checks, JSON, composite FKs, nullable unique indexes, date precision, locking and DDL is **NOT TESTED**. See the validation register.
