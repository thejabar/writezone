# Repository and Module Inventory

**Assessment date:** 2026-11-08
**Evidence:** local source and documentation inspection; no database or deployed environment access.

## Repository shape

The repository is a custom PHP MVC application. Composer declares PHP ^8.2, dotenv, and PSR-4 mappings for App\ to app/ and Core\ to core/. There is no configured PHP test framework or Composer script in composer.json. The browser layer consists of PHP views and static CSS/JavaScript; no package.json or mobile project was found. vendor/ is generated dependency content.

| Area | Responsibility and evidence |
|---|---|
| app/ | Application controllers, models, policies, services, events, feed/ranking, intelligence and language modules |
| core/ | Router, HTTP request/response, database/PDO, auth, sessions, CSRF, middleware, events and error handling |
| routes/ | Web route registration; api.php and admin.php are empty placeholders |
| resources/views/ | PHP templates for current page journeys |
| public_html/ | Front controller and public assets |
| config/, bootstrap/ | Application configuration and initialization |
| database/ | Root migration, migration runner, schema reference and legacy migrations |
| tests/, scripts/test/ | Standalone PHP regression scripts and aggregate shell runner |
| services/intelligence/ | Private Python/FastAPI service foundation and isolated tests |
| docs/ | Architecture, engineering, roadmap, deployment, product and proposed corporate documentation |

## Feature inventory

Status uses the evidence model in docs/ARCHITECTURE_AUTHORITY.md. “Implemented” below means source paths form a usable path in this checkout; it does not mean production-verified. Test coverage varies and is not implied.

| Capability | Status | Evidence and limitation |
|---|---|---|
| Application bootstrap and MVC routing | IMPLEMENTED | public_html/index.php, bootstrap/, core/Application.php, core/Routing/Router.php and routes/web.php |
| Consumer login/session | PARTIALLY IMPLEMENTED | app/Controllers/AuthController.php; core/Authentication/Auth.php; core/Session/Session.php. Login exists; enumeration-resistant errors, recovery/verification and session revocation are incomplete |
| Registration | PARTIALLY IMPLEMENTED | AuthController::register hashes passwords, but validation/policy is incomplete |
| Writ creation, edit and delete | IMPLEMENTED, limited validation | app/Controllers/WritController.php, app/Services/WritPublisher.php, app/Models/Writ.php; no complete draft/media/rich-content lifecycle |
| Public Writ identifiers | IMPLEMENTED with route defect | Writ::findByPublicId exists; bookmark route regex is numeric |
| Comments and replies | IMPLEMENTED, safety gaps | app/Controllers/CommentController.php, app/Models/Comment.php, policies; reporting/moderation/appeals are missing |
| Comment voting | PARTIALLY IMPLEMENTED | CommentVote model/controller supports user vote toggling; it is not a complete reputation or recognition system |
| Profiles and follows | PARTIALLY IMPLEMENTED | profile/follow controllers and models; basic public profiles and following exist |
| Bookmarks | PARTIALLY IMPLEMENTED | BookmarkController and Bookmark model; public identifier route mismatch affects access |
| Notifications | IMPLEMENTED with unsafe read semantics | NotificationController::index invokes markAllRead on GET |
| Keyword search/hashtags/mentions | PARTIALLY IMPLEMENTED | SearchController/SearchService, HashtagService, MentionService; no approved knowledge taxonomy or semantic search |
| Feed | PARTIALLY IMPLEMENTED | app/Engines/Feed/FeedEngine.php and app/Models/Writ.php; feed query has no pagination; ranking result is not sorted by rankAll |
| Recommendation | PLANNED | No production recommendation flow established |
| Knowledge graph | DOCUMENTED/PLANNED | Existing tags and links do not establish a curated knowledge graph |
| Communities and Q&A | MISSING | No established community/Q&A module or routes found |
| Trust, reporting, moderation and appeals | MISSING/PLANNED | No end-to-end reporting/enforcement/appeal system established |
| Administrative control centre | MISSING | admin route placeholder is empty |
| Public JSON API | MISSING | routes/api.php is empty; Lab is the limited JSON action |
| Intelligence Lab | PARTIALLY IMPLEMENTED | /lab and /lab/analyze exist; Lab analysis is bounded by the current service foundation, not a public API |
| PHP-to-Python client | SCAFFOLDED / REPORTED COMPLETE | app/Intelligence/Remote/, config/intelligence.php; disabled by default, no ordinary route/client wiring |
| Python classification | SCAFFOLDED / UNAVAILABLE | services/intelligence/ provides authenticated boundary, health and classification-unavailable behavior; no classifier/model/provider |
| Corporate ORG/IAM/GOV | DOCUMENTED ONLY | docs/Corporate/ proposed designs; no corporate runtime/schema established |
| Background jobs/durable events | MISSING | in-process synchronous EventDispatcher; no queue, worker, durable broker or outbox |
| Media uploads | MISSING | No user media upload/storage workflow found |
| Product analytics | MISSING/UNVERIFIED | No privacy-reviewed product analytics implementation established |
| Multilingual UX | PARTIALLY IMPLEMENTED | app/Intelligence/Language and tests establish language utilities, not a fully localized product |

## Request and execution flow

The web entry is public_html/index.php, which loads Composer/bootstrap, configures display_errors from APP_DEBUG, registers the central error handler and starts the session. routes/web.php registers page and form routes. RequestBoundaryMiddleware is globally applied through the application/router configuration, followed by route-specific auth, CSRF and rate-limit middleware where configured. Controllers use models/services; models use PDO through Core\Database.

The only API-like route discovered is the Lab JSON action. routes/api.php and routes/admin.php are empty. There is no mobile client contract in the repository.

## Module-level follow-up

- Controllers increasingly coordinate validation, policy checks, models, and response redirects. Keep new validation and authorization in existing policy/service boundaries where established; avoid a parallel framework.
- Models include direct SQL and use a shared QueryBuilder. QueryBuilder binds values but interpolates identifiers. Keep identifiers code-controlled and review any future dynamic sort/filter input.
- WritPublisher inserts and runs notification/event side effects synchronously. The dispatcher is in-process and there is no durable event guarantee.
- Views generally escape user-controlled text. Any rich content, upload or generated markup needs a separate reviewed sanitization and media policy.
- Empty zero-byte configuration placeholders (including queue/cache/storage/features) do not represent working infrastructure.

## Existing changes preserved

At baseline, the worktree contained unrelated tracked edits and untracked AGENTS.md, intelligence client/service/tests, Corporate and Roadmap docs, and the roadmap zip. These files are not inventory deliverables and were not modified by this assessment.
