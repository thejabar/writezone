# WZP-02.1 Database and Migration Reconciliation Plan

**Status:** Source/document reconciliation proposal; no database access, SQL, or migration performed
**Controlling decisions:** WZP01-D06 approved the distinction of authorities; WZP01-D07 remains blocked

## Authority model

- **DOCUMENTED:** Root `AGENTS.md` names `database/schema/live-schema.sql` as the documented canonical schema authority.
- **DOCUMENTED:** `database/schema/README.md` describes that artifact as a forensic/reference snapshot captured and verified on 2026-09-22, and explicitly says it is not the executable migration baseline.
- **VERIFIED IN SOURCE:** The executable root migration is `database/migrations/2026_09_22_000000_writezone_baseline.php`. `core/Database/Migrator.php:230-242` discovers PHP migrations through a non-recursive `database/migrations/*.php` glob. Legacy scripts under `database/migrations/legacy/` are excluded by that glob; inspection found top-level PDO/DDL behavior in those legacy files, so do not change discovery recursively without a separate design.
- **NOT VERIFIED:** Whether any local, Hostinger, staging, or production database matches either artifact. No database state was queried.

D06 does not say either artifact completely represents the live schema. It says the snapshot is the documented reference while the executable chain is separately governed. The reconciled executable authority and actual target state remain open.

## Source comparison ledger

| Object | `live-schema.sql` snapshot | Root baseline migration | Classification | Impact and missing evidence |
|---|---|---|---|---|
| `users.handle` | `VARCHAR(50) DEFAULT NULL` (`database/schema/live-schema.sql:108-123`). Unique key is declared. | `VARCHAR(50) NOT NULL UNIQUE` (`database/migrations/2026_09_22_000000_writezone_baseline.php:23-35`). | Structural nullability difference; uniqueness is declared in both. | A stricter non-null migration can fail or require a semantic mapping if existing rows contain NULL; a nullable definition permits handle-less records that may not resolve public-handle routes. Existing data, usage, collation, and desired semantics are not verified. |
| `notifications.actor_id` | `BIGINT UNSIGNED NOT NULL` with FK to `users` and cascade (`live-schema.sql:90-105`). | `BIGINT UNSIGNED NULL` with the same referenced table/action (`baseline.php:150-166`). | Structural nullability difference; relationship intent appears aligned in the two source definitions. | A nullable actor can represent actorless/system events but the product meaning is unapproved; a non-null definition requires every insert to name an actor. Current inspected reply path supplies the authenticated actor (`CommentController.php:105-110`), but that is not proof of every writer or future state. `Notification::forUser()` uses a LEFT JOIN (`Notification.php:23-41`). Existing rows and other write paths must be enumerated. |
| Actor index | Key on `actor_id` named `fk_notifications_actor`; the FK is also named `fk_notifications_actor` (`live-schema.sql:98-105`). | Index on `actor_id` named `idx_notifications_actor`; FK named `fk_notifications_actor` (`baseline.php:156-166`). | Declared index name difference; column and FK target are the same in these files. It is not an application-level name reference found in inspected code. | Do not assume physical equivalence or migration idempotency until target server metadata and migration history are verified. An index rename is metadata but may matter to migration tooling or manual operations. |

`database/schema/README.md` documents InnoDB and `utf8mb4_unicode_ci` for its dated snapshot. Do not transfer that claim to a present database or to every migration. The project config sets PDO charset to `utf8mb4` (`config/database.php`), but that does not settle actual database/table collation or server version.

## Runner and target-safety review

- `migrate.php:7-22` loads Dotenv, reads command input, then calls `Connection::getInstance()` before dispatching `status`, `baseline`, or `migrate`. Therefore `php migrate.php status` is not a no-connection command.
- `config/database.php` derives host, port, database, username, and password from `$_ENV` with defaults; no independent test target allowlist was found in the application database configuration.
- `Migrator::status()` queries table and migration metadata. It requires a connection. `baseline()`/`migrate()` are write-capable paths; `ensureMigrationTable()` contains `CREATE TABLE IF NOT EXISTS`, and baseline/migrate invoke schema metadata/DDL operations.
- The DB-backed `tests/WritPublicationEventRegression.php:78-89` loads project `.env` at test runtime and opens PDO using `localhost:3306` plus environment-selected database and account. It writes test rows later in the file. There is no preconnection code-controlled allowlist or post-connect effective-identity guard in this test. `.env` was not read.

## Required source-only reconciliation process

1. Appoint a database/migration owner and an application data owner; preserve D07 as blocked until their documented decision.
2. Compare complete table/column/index/FK definitions in the dated snapshot, root migration, migration interface/runner, and all active application read/write paths. Record one row per difference and any additional drift found.
3. Reconcile migration history from repository source without executing migrations. Confirm how legacy files are intentionally excluded and retain the non-recursive behavior unless an approved technical decision changes it.
4. Resolve each semantic difference with explicit rationale, affected application workflows, data-preservation requirements, and desired fresh-install state. The Project Owner approves scope; the appointed data/domain owner resolves semantics.
5. Prepare a reversible migration and test plan only if a schema change is actually required. No production DDL is inferred from the repository snapshot.

## Separate database verification gate

Any later DB-backed test or live comparison needs a separate explicit authorization. Before connection, a code-controlled immutable allowlist must approve the exact disposable target tuple independently of `.env`; reject empty, ambiguous, unapproved, or mismatched values. Before any write, verify the selected database, effective account, server/port/version, charset/collation, privileges, and test-run identity. Credentials may authenticate to the pre-approved target but must not choose it. Use least-privilege accounts and cleanup limited to current-run-owned records. Independent reviewer repeats the identity proof. No production/staging target, migration, schema write, or database connection is authorized by WZP-02.1.

## Reconciliation decision table

| Difference | Current decision | Required authority | Evidence needed to close |
|---|---|---|---|
| `users.handle` nullability | Unresolved; no preferred definition selected | Project Owner plus appointed database/migration and application data owners | Writer/read-path inventory, migration history, row-impact analysis from an explicitly approved disposable copy if required, intended public identity semantics, rollback plan. |
| `notifications.actor_id` nullability | Unresolved; no preferred definition selected | Project Owner plus appointed notification/product and database owners | All insert paths, actorless-event policy, `LEFT JOIN` handling, current migration history, data integrity and cascade behavior. |
| Actor index name | Unresolved until metadata and tool assumptions are checked | Appointed database/migration owner; security review if target involved | Full index/FK metadata, migration idempotency test on disposable target, evidence no code/tool references the old name. |
| Migration-chain authority and current state | Snapshot/migration distinction documented; actual parity unknown | Project Owner, database owner | Reconciled source ledger and, only under separate access approval, independently captured target state. |
