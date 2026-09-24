# WriteZone Deployment Governance

## Purpose

WriteZone releases are controlled by Git commit identity, repository validation, dependency validation, and migration state.

The live Hostinger application uses the repository root as its application tree.

Repository: /home/u970651331/domains/writezone.org
Public document root: public_html/

There is currently no CI/CD deployment system. Deployment remains deliberate and manual.

## Release Gate

Run scripts/release/check.sh before release.

A release must not proceed when the gate fails.

## Release Identity

Every release must correspond to an exact Git commit.
Individual production files must not be copied or edited as an alternative to a known Git release.

## Database Rule

Production schema changes must use the governed migration system.
Check migration state with: php migrate.php status
Apply governed migrations with: php migrate.php migrate

## Rollback

Application rollback and database rollback are separate operations.

Application rollback means returning the codebase to a previously verified Git commit.

Do not automatically reverse database changes merely because application code is rolled back.

The target application commit must be checked for database compatibility before rollback.

## Current Limitations

CI/CD, automated deployment, automated rollback, health monitoring, deployment locking, and zero-downtime deployment are not currently implemented.

These are future engineering work, not current capabilities.

## R0.4 Exit Condition

R0.4 is complete when the release gate exists, the deployment topology is documented, release and rollback rules are explicit, and releases are tied to exact Git commits.
