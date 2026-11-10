# Architecture and Security Assessmen

**Assessment date:** 2026-11-08
**Status:** Local static assessment; no environment verification.

## Architecture

### Verified source facts

- The application is a custom PHP MVC modular monolith; Composer uses App\ and Core\ PSR-4 roots and requires PHP ^8.2.
- public_html/index.php loads the application, sets display_errors based on APP_DEBUG, registers Core\Error\ErrorHandler and starts a session.
- routes/web.php registers consumer routes and the Lab routes. routes/api.php and routes/admin.php are empty.
- Core\Database\Connection supplies PDO. Application models use direct PDO and Core\Database\QueryBuilder.
- EventDispatcher dispatches synchronously within the PHP process. WritPublisher dispatches WritPublished, but no durable queue/outbox or application listener path was established.
- The private Python service is a separate capability and the PHP remote client is opt-in; ordinary routes do not call it.

### Assessmen

The modular monolith is suitable for incremental public product work. No evidence supports splitting it into microservices. Reuse existing routing, middleware, policies, services and PDO conventions; add durable asynchronous infrastructure only when a documented use case and operations owner exist.

The architecture authority says capability status must be based on source, tests and environment evidence. Historical architecture text that says no publication dispatch path is now stale in one respect: a dispatch call exists; subscriber behavior and durable effects remain absent.

## Security assessmen

| Boundary | Evidence | Finding and action |
|---|---|---|
| Consumer authentication | AuthController login/register; Core\Authentication\Auth | Password verification/hash exist. Login distinguishes unknown user and invalid password, enabling account enumeration; use a uniform response and regression test |
| Registration | AuthController::register | Trims/sanitizes username but does not fully reject malformed input or enforce a reviewed server-side password/email policy; define product/security acceptance before changing |
| Session | Core\Session\Session and Auth | Strict mode and cookie-only behavior are configured; cookie attributes are configurable. Session revocation/device management is not established |
| CSRF | Core\Security\Csrf and route middleware | Token uses hash_equals; state-changing form routes generally use CSRF. Preserve coverage |
| Rate limiting | RateLimitMiddleware and storage-backed limiter | File-backed fixed-window controls exist; storage is shared local state, cleanup and multi-instance semantics are not established |
| Request boundary | RequestBoundaryMiddleware | Checks PHP CONTENT_LENGTH after SAPI parsing and bounds parsed fields. Casting missing/malformed length to integer does not prove raw streaming/framing limits. Confirm web-server limits separately |
| Object authorization | WritPolicy, CommentPolicy, controllers | Current Writ/comment mutation paths use object policies; historical object-type mismatch is not present in inspected controllers |
| Bookmark route | routes/web.php, BookmarkController, Writ::findByPublicId | Route allows numeric segment while lookup expects public ID; correct route contract and test |
| Notification state | routes/web.php, NotificationController::index, Notification::markAllRead | GET changes read state. Make GET read-only and provide a protected POST action |
| Error handling | Core\Error\ErrorHandler; public_html/index.php | Production response is generic unless debug is enabled, but exception message/file/line are written to the application log. Review redaction, retention, permissions and log rotation; APP_DEBUG must remain disabled outside local debug |
| SQL construction | Core\Database\QueryBuilder | Values are prepared/bound; identifiers are interpolated. Keep table/column identifiers internal and allowlisted |
| Output encoding | resources/views and helper/services | Ordinary text is escaped in main views; rich-content and future upload paths need an explicit safe rendering policy |
| Corporate authorization | docs/Corporate only | Proposed WZ-IAM is not current consumer authorization. Do not merge corporate privileges into public accounts |
| Intelligence authentication | Python service and PHP remote client | Local design includes bounded credentials, destination binding and disabled-by-default feature. WP6B status is awaiting technical validation; no production secret or network test was performed |

## Threat priorities

1. Unsafe test execution may write to an unintended database or erase shared runtime files; quarantine or replace unsafe tests before routine release use.
2. GET mutation permits cross-site navigation-triggered state changes; make the route safe and add method/CSRF tests.
3. Incomplete registration and account enumeration weaken identity boundaries; define and test uniform validation/errors.
4. Schema/migration drift can cause incorrect migrations or data loss; no schema changes until authority and target identity are reconciled.
5. Missing reporting/moderation/appeals creates safety gaps if public participation expands; do not expand interactions without approved enforcement paths.
6. API authentication and versioning are absent; do not expose internal/private services as mobile APIs.
7. Error logs can retain exception details; avoid secrets/user payloads in exceptions and define operational log controls.

## Data and trust boundaries

Public consumer identities remain separate from proposed corporate principals. The Python intelligence client is server-side only and disabled by default; credentials never belong in browser configuration. External Font Awesome assets are loaded from a CDN in current layouts; document the dependency and assess integrity/privacy policy before a release that requires strict third-party controls.

No external identity provider, HRMS, ERP, corporate IAM, production secret store, monitoring service, backup, or incident response setup was verified. Do not infer these from documentation.
