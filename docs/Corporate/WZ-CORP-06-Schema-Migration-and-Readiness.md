# WZ-CORP Schema Reconciliation and Migration Readiness

**Status:** Proposed controls; no schema change or migration authorized
**Evidence boundary:** Repository files inspected read-only; no database was accessed
**Authority:** [Architecture authority](../ARCHITECTURE_AUTHORITY.md) · [Schema reference notes](../../database/schema/README.md)

## Repository evidence

- **DOCUMENTED IN EXISTING DESIGN:** `database/schema/README.md` describes `database/schema/live-schema.sql` as a historical reference snapshot of eight tables, verified on 2026-09-22. It is not an executable migration or the canonical executable baseline.
- **VERIFIED IN SOURCE:** `database/migrations/2026_09_22_000000_writezone_baseline.php` defines the current top-level baseline. It uses `CREATE TABLE IF NOT EXISTS`; the root migration runner discovers only `database/migrations/*.php`, not nested legacy files.
- **VERIFIED IN SOURCE:** `MigrationInterface` exposes `up(PDO)` only. No down/rollback contract exists.
- **VERIFIED IN SOURCE:** `Migrator::migrate()` blocks an existing unbaselined database. `Migrator::baseline()` compares table names and normalized `SHOW CREATE TABLE` output with the snapshot before recording the baseline. These paths were inspected, not executed.
- **NOT VERIFIED:** No local, GitHub, Hostinger, staging or production database state was inspected in this package. The snapshot and migration source cannot establish present live state.

## Directly verified source discrepancies

| Object | Baseline migration | Historical schema snapshot | Reconciliation requirement |
|---|---|---|---|
| `users.handle` | `VARCHAR(50) NOT NULL UNIQUE` | `VARCHAR(50) DEFAULT NULL`, unique | Decide and document the canonical nullability and preserve existing rows before any migration design. |
| `notifications.actor_id` | `BIGINT UNSIGNED NULL` | `BIGINT UNSIGNED NOT NULL` | Determine whether system-generated notifications exist and define a safe data rule before changing nullability. |
| Notification actor index | `idx_notifications_actor(actor_id)` | `fk_notifications_actor(actor_id)` | Confirm the indexed column and intended stable name; avoid adding a duplicate index. |

These are source-to-snapshot comparisons, not a claim of complete live-schema drift. A complete table-by-table semantic comparison and migration-history reconciliation remain prerequisites.

## Corporate schema integration principles

1. Keep corporate tables in the existing MariaDB database and PHP modular monolith unless an approved decision changes that direction.
2. Use WZ-ORG, WZ-IAM and WZ-GOV table prefixes and explicit module-owned repositories/services. Do not alter public user/session tables to represent workforce identities.
3. Keep optional public-account links disabled and avoid a foreign key to the consumer `users` table until a linking policy and legacy-schema reconciliation are approved.
4. Prefer forward-only, additive migrations for the corporate foundation. Use RESTRICT relationships for corporate history; do not cascade-delete organization, identity, approval or audit records.
5. Never apply `live-schema.sql`. Do not use `CREATE TABLE IF NOT EXISTS` as a substitute for checking that an existing table has the expected definition.

## Required reconciliation sequence

1. Freeze a reviewed source commit and inventory all migration files, their ordering, execution status evidence and schema documents; nested legacy files must be classified explicitly.
2. Compare every table, column, nullability, default, index, constraint, engine and collation in the snapshot against the baseline source. Record each difference and its data consequences.
3. Establish an explicitly approved disposable test database and verify its identity before any database operation. That environment is not authorized by this document.
4. Approve one executable schema authority and a data-preserving baseline/adoption path. Keep live-database inspection and production migration as separate approvals.
5. Implement only a reviewed forward migration after its isolated migration and rollback/forward-repair strategy passes. Because the interface has no down method, recovery should default to a corrective forward migration and restore plan, not a destructive reverse script.
6. Before release, require a backup/restore rehearsal, migration status evidence, post-migration constraint checks and an independently reviewed deployment plan.

## Approval boundary

The physical corporate tables in documents 01–04 are proposals. MariaDB support for their composite keys, CHECK constraints, collations, temporal precision, transaction locking and DDL behavior is **NOT TESTED**. No DDL or migration is approved by this readiness document.
