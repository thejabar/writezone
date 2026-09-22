# WriteZone Architecture Authority

**Status:** Active
**Version:** 1.0
**Established:** 2026-09-22

## 1. Authority Principle

WriteZone architecture is governed by evidence.

A document, filename, roadmap item, class name, interface, comment, or architectural intention does not by itself prove that a capability exists.

Current implementation status must be established from runtime source code, database/runtime evidence, tests, deployment verification, and then documentation.

Documentation describes architecture and intent. It does not override implementation evidence.

## 2. Canonical Architecture Document

The canonical current architecture handbook is:

`docs/ARCHITECTURE.md`

This is the primary architecture reference for the current WriteZone system.

## 3. Constitutional Governance

The project constitution is:

`docs/Book-I-Constitution/Constitution.md`

The Constitution establishes project-level engineering principles and governance.

It does not replace runtime evidence when determining whether a capability is actually implemented.

## 4. Architecture Decisions

The intended ADR location is:

`docs/Decisions/`

Existing ADR filenames are retained as structural records unless and until their contents are populated and reconciled with the current architecture.

The directory `decisions/` is considered a secondary decision location until its contents are formally reconciled with `docs/Decisions/`.

An ADR records a decision. It does not prove that the resulting implementation exists.

## 5. Required Implementation Status Model

Every major capability audited by WriteZone should use one of these states:

### PLANNED
The capability is intended but implementation has not been established.

### DOCUMENTED
The capability is described in project documentation, but implementation has not been established.

### SCAFFOLDED
Structural code or interfaces exist, but the capability is not functionally implemented.

### PARTIALLY IMPLEMENTED
Some functional components exist, but important capability paths are incomplete.

### IMPLEMENTED
The capability exists in the application runtime and its principal execution path has been verified.

### TESTED
The capability is implemented and supported by relevant automated or regression tests.

### PRODUCTION-VERIFIED
The capability has been verified in the applicable deployed, staging, or production environment.

A capability may have more than one evidence attribute, but implementation, testing, and production verification must remain explicitly distinguishable.

## 6. Documentation Is Not Implementation Evidence

The following are not sufficient on their own to claim that a feature is implemented:

- An empty architecture document
- An ADR filename
- A class or interface name
- A TODO item
- A roadmap checkmark
- A blueprint or design proposal
- A database table without runtime integration
- A controller without an active route
- An engine that is not part of the execution path
- A test fixture that is not connected to production code
- A placeholder implementation that returns unchanged input

## 7. Architecture Reconciliation Rule

When documentation and implementation disagree:

1. Preserve the existing evidence.
2. Identify the discrepancy.
3. Classify the capability using the implementation status model.
4. Record the discrepancy.
5. Correct either the implementation or the documentation.
6. Verify the corrected state.
7. Commit the reconciliation.

No architectural capability should remain marked as complete solely because documentation says it is complete.

## 8. Database Authority

Database governance is a separate R0 workstream.

Until migration reconciliation is completed:

- Repository migrations are not assumed to describe the complete live schema.
- The live database is not assumed to be safely reproducible from the repository.
- Schema drift must be explicitly identified and resolved.
- `migrate.php` must not be treated as a safe production migration mechanism.

R0.2 and R0.3 will establish the database source of truth and migration governance.

## 9. Release and Deployment Authority

Release status must be established from actual deployment evidence.

A Git commit existing on a branch does not by itself prove that the deployed environment contains that commit.

Deployment configuration, branch selection, build logs, tests, migration state, health checks, and rollback procedures must be reconciled during R0.4.

## 10. Audit Baseline

This authority document is established as part of the WriteZone Summit Standard Architecture Audit.

The R0 workstream will reconcile:

- Architecture
- Database
- Migrations
- Deployment
- Testing
- Documentation
- Runtime implementation

The R0 exit condition is:

> Git, database, tests, deployment evidence, runtime behaviour, and architecture documentation describe the same system.

## 11. Scope of This Document

This document establishes governance only.

It does not change application runtime behaviour, database schema, routes, services, engines, ranking, feed behaviour, authentication, intelligence behaviour, or production configuration.

Those changes require their own controlled workstream and verification.
