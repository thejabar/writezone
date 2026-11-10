# Database and Migration Readiness

**Assessment date:** 2026-11-08
**Execution boundary:** Repository inspection only. No database connection, SQL execution or migration was performed.

## Evidence and authority

- database/schema/README.md documents live-schema.sql as a historical reference snapshot generated from a live MariaDB database on 2026-09-22. It records eight tables, InnoDB, utf8mb4 and utf8mb4_unicode_ci, and says the snapshot is not an executable migration or canonical migration baseline.
- The root migration is database/migrations/2026_09_22_000000_writezone_baseline.php. Older migration files are under database/migrations/legacy; Core\Database\Migrator globs root migration files, so the legacy directory is not in the active migration list.
- database/schema/live-schema.sql and the root baseline differ: users.handle is nullable in the snapshot and NOT NULL in the baseline; notifications.actor_id is NOT NULL in the snapshot and nullable in the baseline; the actor index name differs. A source diff is not proof of actual live state.
- docs/ARCHITECTURE_AUTHORITY.md explicitly says repository migrations are not assumed to describe the complete live schema until migration reconciliation is complete.
- No database was accessed. Live, local and Hostinger schema states are NOT VERIFIED in this assessment.

## Database access and migration behavior

Core\Database\Connection builds a PDO connection from configuration and wraps connection exceptions with a generic message. QueryBuilder prepares values but interpolates identifiers, so future dynamic identifiers must be fixed/allowlisted.

Core\Database\Migrator::status() connects and queries table state. migrate() executes migration DDL and records migration history. baseline() writes a migration record. verifyLiveSchema() compares table sets and normalized CREATE TABLE output with the repository snapshot when adopting a baseline. The baseline uses CREATE TABLE IF NOT EXISTS, which can leave pre-existing definitions unchanged and obscure drift. The migration interface has up behavior without a paired down/rollback contract.

The release script runs migration status after tests, so even “status” is not an offline check; it requires a database connection. Do not run it without a separately approved disposable target.

## Readiness assessmen

**BLOCKED for schema change or database-backed release validation.** The project needs a single documented authority for intended schema, a reviewed reconciliation between reference snapshot and migration baseline, a verified migration-history plan, and a separately approved disposable environment. No actual database state or numeric MariaDB/MySQL version compatibility is established by the repository evidence inspected.

### Required reconciliation sequence

1. Freeze the current source migration and snapshot artifacts; record checksums or commit identity in a future evidence record.
2. Compare each table/column/index/constraint semantically, including nullability, defaults, collation, generated behavior and FK actions.
3. Reconcile documented migration history with a separately approved disposable copy/target. Never assume the historical live snapshot is the current live database.
4. Decide and document which artifact is the canonical desired schema and which is historical evidence.
5. Build fail-closed target identification for DB tests; independently verify host, port, database and account before any connection.
6. Test migration forward behavior, repeat/idempotency, failure behavior, data preservation, rollback/forward-repair and version compatibility on disposable data.
7. Require separate approval for any production schema plan, backup/restore rehearsal, and deployment execution.

## Risks and controls

| Risk | Evidence | Required control |
|---|---|---|
| Snapshot/baseline drift | users.handle and notifications.actor_id differences | Resolve desired semantics before migration design |
| Wrong-target test or migration | runner reads local DB settings; no independent target allowlist established in app | Code-controlled allowlist and fail-closed identity checks before connection |
| False idempotency | CREATE TABLE IF NOT EXISTS may not reconcile existing definitions | Compare actual structure; treat idempotency and reconciliation separately |
| No rollback contract | migration interface exposes up without established down | Design data-safe rollback/forward repair and backup evidence |
| Version incompatibility | No approved MariaDB/MySQL version matrix found | Pin candidate versions and run disposable compatibility matrix |
| Release command side effect | migrate.php status connects; release script calls it | Separate offline release checks from explicitly configured DB checks |
| Misleading snapshot authority | Snapshot README calls it historical/reference only | Keep it non-executable and label every state by evidence source |

## Not performed

No .env values, passwords, database contents, production/staging systems, SQL statements or migrations were accessed or run. Actual server version, grants, migration history and schema remain unknown here.
