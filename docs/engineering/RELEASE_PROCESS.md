# WriteZone Release Process

## Release Identity

Every release is identified by an exact Git commit.

## Required Gate

Run scripts/release/check.sh before release.
The working tree must be clean and all release checks must pass.

## Deployment

The current Hostinger deployment uses the Git repository as the application tree and public_html as the web document root.
Deployment must correspond to a known Git commit.

## Database

Database changes must use the governed migration system.

## Rollback

Application rollback is a Git operation.
Database rollback is a separate operation and must be assessed independently.

## Prohibited Practices

Do not deploy an unknown working tree.
Do not copy arbitrary individual production files.
Do not bypass the release gate.
Do not modify the production schema outside governed migrations.
Documentation does not constitute implementation evidence.
