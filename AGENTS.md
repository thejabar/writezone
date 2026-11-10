# Repository Guidelines

## Product Vision

WriteZone is a global Knowledge Network and Knowledge Hub, not a generic social media platform. Build for creating, connecting, discovering, and preserving useful knowledge; prioritize meaningful discovery and the value of users' time over superficial engagement.

## Product Architecture

The product direction spans the Social Graph, Content Graph, Knowledge Graph, Intelligence Layer, Recommendation Layer, Trust and Recognition, Communities, Creator System, Analytics, and Multilingual and Global Support.

These domains should connect through existing architecture as the product evolves: graphs provide context, intelligence and recommendations support discovery, trust and recognition represent contribution, communities and creator tools support participation, and analytics and global support inform and extend the experience.

This is a direction for incremental, approved development, not a mandate to implement every layer now or rewrite existing architecture.

## User Experience

Follow the approved Knowledge Network / Knowledge Hub direction: content-first pages with an editorial, technology-oriented visual identity and a distinctive WriteZone experience. Keep desktop and mobile layouts responsive, navigation and interaction patterns consistent, and Font Awesome usage coherent with existing icons. Optimize for knowledge discovery and reading value rather than superficial engagement.

## Project Structure

- `app/` contains product code: controllers, models, services, policies, events, and intelligence and feed modules.
- `core/` holds shared framework components such as HTTP, routing, database, authentication, and security.
- `routes/` defines endpoints; `resources/views/` contains PHP templates; `public_html/` is the web root and stores public assets.
- `bootstrap/`, `config/`, and `database/` configure and initialize the app and its migrations. Regression scripts live in `tests/`; project guidance lives in `docs/` and `engineering/`.
- Treat `vendor/` as generated Composer dependencies; do not edit it directly.

## Setup and Development

- `composer install` installs the PHP dependencies (PHP 8.2 or later is required).
- Create a local, untracked `.env` with the MySQL settings `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD`. Never commit credentials.
- `php -S 127.0.0.1:8000 -t public_html` starts PHP's local web server.

## Testing

- `sh scripts/test/run.sh` runs the regression suite. Run an individual check with `php tests/AuthorizationRegression.php` (replace with the relevant test file).
- `sh scripts/release/check.sh` runs the release gate: Composer validation, PHP syntax checks, regression tests, and migration status. It requires a clean working tree.
- For each code change, run relevant PHP syntax checks, regression tests, security checks, and user-journey verification. If a check does not apply or cannot run, state why in the completion report.
- Add regression coverage in `tests/` for behavior changes. Use the existing `*Regression.php` naming pattern and standalone PHP assertion style.

## Engineering Governance

Audit the relevant code, architecture, tests, and documentation before implementation. Preserve existing architecture, reuse established components and services, and check for duplicate functionality before adding new code. Maintain security, maintainability, and backward compatibility. Do not introduce a new framework without explicit approval.

## Database Governance

Treat `database/schema/live-schema.sql` as the documented canonical schema authority, while verifying actual migrations and database state before making schema decisions. Inspect existing migrations before proposing changes and check for existing tables, columns, and indexes to prevent duplicates. Never execute production migrations or database write operations without explicit approval.

## Development Workflow

Follow this sequence for every development task: **Requirement → Repository Inspection → Technical Analysis → Implementation Plan → Approval → Implementation → Testing → Diff Review → Completion Report.** Do not begin implementation until the plan has approval. Preserve the reviewed scope through implementation and report changes and verification results at completion.

## Code Style and Naming

Use PHP with `declare(strict_types=1);`, four-space indentation, and typed parameters and returns. Keep namespaces aligned with Composer's PSR-4 mappings (`App\\` to `app/`, `Core\\` to `core/`). Name classes and files in `PascalCase`; use `camelCase` for methods and variables. Follow nearby code patterns; no formatter or linter is configured in `composer.json`.

## Commits and Pull Requests

Recent commits use short, imperative subjects with category prefixes such as `feat(events):`, `test(security):`, `security:`, and `docs(architecture):`. Keep changes focused and describe the action. PRs should explain the behavior and rationale, list checks run, and call out migration or configuration changes. Include screenshots for user-facing visual changes.

## Git and Deployment Governance

GitHub is the release authority. Preserve uncommitted changes; never reset, overwrite, or discard existing work. Do not commit, push, merge, or deploy without explicit approval.

Protect credentials and private configuration as described under Security and Configuration. Verify repository and deployment state before acting on either.

## Security and Configuration

Keep secrets and local data in `.env` or ignored runtime storage; never expose credentials or private configuration.

For changes touching requests, sessions, authorization, or database errors, preserve the existing security boundaries and add regression coverage.

## Current Project Protection

Preserve the six existing Phase 0.6G changes and the prepared Phase 0.6H patch. Phase 0.6H is review-only until explicitly approved. Do not assume local, GitHub, and Hostinger states are identical; inspect each relevant state independently before reporting or proposing changes.
