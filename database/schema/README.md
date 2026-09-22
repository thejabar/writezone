# WriteZone Database Schema Reference

## Purpose

`live-schema.sql` is a verified reference snapshot of the WriteZone production/live MariaDB schema.

It is evidence of the schema that existed when R0.2 database forensics were completed. It is **not an executable migration** and must never be applied directly to the live database.

## Verification

The snapshot was generated from the live database using PDO `SHOW TABLES` and `SHOW CREATE TABLE` and independently compared against a fresh read-only regeneration.

Verification result: PASS.

## Current live baseline

- 8 tables captured
- InnoDB
- utf8mb4 / utf8mb4_unicode_ci
- Live schema verified on 2026-09-22

## Important distinction

This file is a forensic/reference artifact. It is not yet the canonical executable migration baseline.

The canonical migration chain will be established separately during R0.3. Existing production data must be preserved during that process.

## Regeneration

Regenerate this artifact only deliberately, after database reconciliation or an intentional schema change. Do not regenerate it as part of normal application deployment.
