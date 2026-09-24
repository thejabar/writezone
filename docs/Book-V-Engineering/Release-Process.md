# WriteZone Release Process

The authoritative deployment governance document is docs/Deployment/README.md.

The executable release gate is scripts/release/check.sh.

Every release must be tied to an exact Git commit and pass the release gate.

Application rollback and database rollback are separate operations.

Documentation is not implementation evidence.
