# WZP-01 D01 — Project Owner Scope Proposal

**Programme:** WZ-MASTER-ROADMAP-2027.2
**Decision:** WZP01-D01 — Release Scope and Exclusions
**Decision status:** D01 approved with conditions on 2026-11-08 and retained on 2026-11-09; this file is the historical D01 record
**Evidence boundary:** Local repository source and documentation inspected on 2026-11-08. No database, GitHub, staging, production, or deployed runtime was accessed.

> **Recorded decision:** A is approved as the long-term Knowledge Network and Knowledge Hub direction. B is approved in principle as a website-first, content-first candidate with the stated exclusions. C is approved in principle as a separate inventory derived from capabilities that pass beta. Conditions and approvals below remain outstanding. This decision does not authorize implementation, database work, beta invitations, or production launch.

> **Current-record notice:** The 2026-11-09 consolidated Project Owner decision retains D01 and records subsequent WZP-01 dispositions, including B journey boundaries, content-only reporting, qualitative acceptance, blocker assignments, and beta-boundary timing. Use [that decision record](WZP-01-PROJECT-OWNER-CONSOLIDATED-DECISION.md), the updated decision register, and closure-readiness report for the current status. This 2026-11-08 snapshot is preserved as D01 evidence and is not a current list of unresolved decisions.

## 1. Executive recommendation

The Project Owner has approved with conditions a **website-first, content-first** direction for the first public release, with three separate inventories: a long-range programme target (A), a tightly controlled public beta (B), and an independently approved initial production scope (C). Keep the product centered on creating, reading, finding, and preserving useful knowledge. Do not make comments, social engagement, mobile apps, AI, corporate administration, or analytics prerequisites for the first release.

The approved-in-principle B is a small server-rendered website: public account and basic profile journeys; text Writ publishing, reading, and editing; basic non-AI knowledge discovery; responsive desktop/mobile-browser experience; and minimum operational, security, privacy, and recovery controls. Bookmarks and interactions are excluded from baseline B. Every capability remains conditional on approved user journeys, WZP-02 stabilization, relevant package completion, and acceptance evidence. Public user-generated content also needs an approved report, response, and support path before beta invitations. The Project Owner requires a decision on how content-only reporting is covered if discussions are excluded.

Use the same validated capability family as the starting C candidate, then freeze C separately after beta evidence. C additionally requires production operations, recovery, support, privacy, and Gate 3 evidence. Do not automatically promote all B items or add new features. Neither B nor C is ready or authorized today.

## 2. Scope assumptions and evidence limitations

- A, B, and C are separate scope inventories, not implementation or release authority. The Project Owner has approved their direction with conditions; the roadmap defines A as the programme baseline, B as the frozen controlled-beta inventory, and C as the separately frozen first-production inventory ([roadmap governance](../Roadmap/08-BETA-AND-LAUNCH-GOVERNANCE.md)).
- “Evidence status” describes local repository evidence, not completeness, correctness, test coverage, or deployment state. The classifications follow [architecture evidence authority](../ARCHITECTURE_AUTHORITY.md); static source inspection does not prove a functioning or released user journey.
- The inspected baseline is branch `feature/mentor-engine`, HEAD `95527c38c8bd4b554489dcad0bf5dc700a69d4ad`, tracking `origin/feature/mentor-engine` with local Git metadata showing 0 ahead / 0 behind. No remote was contacted; GitHub and deployed environments are not verified.
- No launch country, supported locale, beta cohort, duration, capacity, privacy/retention policy, quantitative target, support schedule, or team appointment is assumed. These require owners and evidence.
- WriteZone's approved Knowledge Network / Knowledge Hub direction is controlling. Engagement, time-on-site, and content volume alone are not acceptance goals.

## 3. Current application capability inventory

The repository contains a custom PHP MVC application. `composer.json` declares PHP `^8.2`; `routes/web.php` contains server-rendered page and form routes. `routes/api.php` and `routes/admin.php` are empty. No mobile project or `package.json` was found. CSS includes mobile-width breakpoints, which is evidence of responsive styling only, not verified responsive journeys.

The public routes in [routes/web.php](../../routes/web.php) include registration/login, Writ feed/detail/create/edit/delete, search, hashtags, profiles, comments/replies/edit/delete, comment votes, follows, bookmarks, notifications, and the Lab. Relevant implementations include [AuthController](../../app/Controllers/AuthController.php), [WritController](../../app/Controllers/WritController.php), [Writ](../../app/Models/Writ.php), [SearchController](../../app/Controllers/SearchController.php), [ProfileController](../../app/Controllers/ProfileController.php), and [NotificationController](../../app/Controllers/NotificationController.php).

Important scope caveats:

- The current Writ flow is text-based; no complete draft/recovery or media publishing product was established. `Writ::feed()` has no limit, while `Writ::search()` is a recency-ordered keyword `LIKE` search limited to 20 results. This is not semantic discovery or a Knowledge Graph.
- Registration currently creates an account without substantive server-side field validation, and login returns different messages for an unknown user and a bad password ([AuthController::register()` / `login()`](../../app/Controllers/AuthController.php)). Scope these journeys only after security and acceptance review.
- Bookmark routing constrains the path parameter as numeric while `BookmarkController::store()` looks up a Writ by its public ID ([web routes](../../routes/web.php), [BookmarkController](../../app/Controllers/BookmarkController.php)). Bookmarks are not a reliable beta capability until the contract is reconciled.
- `NotificationController::index()` calls `Notification::markAllRead()` on GET. Notifications should remain outside B/C until the read flow is read-only and the state-changing action has an approved method and regression evidence.
- Comments, replies, votes, and follows are present in source, but no complete public reporting, moderation, enforcement, or appeal path was found. Their presence does not establish safe release readiness.
- The intelligence transport/configuration exists, but the Python classification endpoint is documented and implemented as unavailable; it is disabled by default and not connected to ordinary PHP user workflows. No model or production recommendation capability is established.
- Corporate architecture is documented under `docs/Corporate/`; this is not evidence of corporate runtime modules, an IdP, HRMS, ERP, or workforce data source.

### Evidence classification key

**VERIFIED IN SOURCE** means the named source structure or route is present. **PARTIALLY IMPLEMENTED** means relevant code exists but the full capability or safe journey is not established. **DOCUMENTED ONLY** means a design or plan exists without corresponding runtime evidence. **PROPOSED** means a product direction is named but not specified or implemented. **NOT FOUND** means no matching implementation was found in the inspected repository. **NOT VERIFIED** means the claim requires runtime, environment, or external evidence that was not accessed.

## 4. Proposed A — complete programme scope

A is a long-range programme target, not a commitment that every domain must ship before an initial public release. It retains the approved knowledge product direction and the incremental PHP MVC architecture. The following A/B/C matrix records the approved direction; each capability remains subject to package approval, ownership, dependencies, acceptance, and release controls.

| Scope item | A: Programme | B: Beta | C: Production | Evidence status | Dependencies | Rationale |
|---|---|---|---|---|---|---|
| Knowledge publishing and reading | **Include** useful knowledge creation, preservation, and reading. | **Conditional include** text Writ publishing, reading, and editing; approve recovery acceptance criteria before implementation. | **Conditional include** only after beta evidence and a separate C freeze. | PARTIALLY IMPLEMENTED | WZP-01, WZP-02, WZP-PUB-01; content, privacy, recovery, and acceptance decisions. | Establishes the Knowledge Hub value; confirm detailed draft/recovery cases under WZP-PUB-01 without implying they are separately approved by D01. |
| Public identity and basic profiles | **Include** consumer account and profile foundations. | **Conditional include** only for approved public account/session/profile fields and journeys. | **Conditional carry-forward** after beta and separate production approval. | PARTIALLY IMPLEMENTED | WZP-01, WZP-02, WZP-PUB-01; security/privacy review. No corporate IAM dependency. | Supports authorship while preserving consumer/corporate identity separation. |
| Non-AI search and discovery | **Include** useful search, metadata, provenance, and explicit knowledge links. | **Conditional include** the approved WZP-PUB-03 non-AI foundation; do not claim a Knowledge Graph or semantic ranking prematurely. | **Conditional carry-forward** only with reviewed relevance evidence. | PARTIALLY IMPLEMENTED | WZP-01, WZP-02, WZP-PUB-01, WZP-API-01, WZP-PUB-03; taxonomy and relevance decisions. | Discovery is central to a Knowledge Hub, but the current search is basic and PUB-03 awaits owner decisions. |
| Save/bookmark knowledge | **Include** a user-controlled way to revisit knowledge. | **Exclude from baseline B** as decided by the Project Owner. | **Exclude from baseline C** unless separately approved after beta. | PARTIALLY IMPLEMENTED | WZP-02 for the known route issue; any future inclusion needs explicit package allocation, authorization and regression evidence. | User value is plausible, but the route is inconsistent and no canonical package explicitly owns full bookmark behavior. |
| Social graph, discussions, comments, and voting | **Include** meaningful connection and knowledge exchange. | **Exclude** from baseline B. | **Exclude** from baseline C; reconsider only as separately approved scope with safety controls. | PARTIALLY IMPLEMENTED | WZP-01, WZP-02, WZP-PUB-01, WZP-API-01, WZP-PUB-02; WZP-TRUST-01 moderation path if a release enables interactions. | Source contains comments/votes/follows, but there is no complete reporting, moderation, enforcement, or appeal path. |
| Q&A and communities | **Include** as later knowledge participation capabilities. | **Exclude.** | **Exclude from baseline.** | NOT FOUND | WZP-01, WZP-02, WZP-PUB-01, WZP-API-01, WZP-PUB-02; full safety path if included. | No implementation was found; community roles and policies need explicit authority. |
| Trust, Safety, and contribution recognition | **Include** reporting, triage, moderation, enforcement, appeals, and transparent recognition. | **Conditional safety dependency:** minimum public-content reporting/response and support must be approved and operational before beta; exclude recognition. | **Include safety controls required by the frozen public scope; exclude recognition from baseline unless approved.** | DOCUMENTED ONLY | WZP-01, WZP-TRUST-01 and WZP-OPS-01 as applicable; resolve how content-only reports are covered when PUB-02 is excluded. | A safe public UGC release needs an accountable response path; no such runtime path or owner is established. |
| Creator capabilities | **Include** tools that help creators produce and maintain useful knowledge. | **Exclude** beyond the approved basic publishing journey. | **Exclude from baseline.** | PROPOSED | Later product requirements and approved WZP-PUB-01 scope; avoid a separate creator subsystem without a decision. | The product direction names a Creator System, but no distinct capability is specified in source. |
| Intelligence and recommendations | **Include** governed intelligence for knowledge quality and discovery as a later capability. | **Exclude** classification, AI assistance, external providers, and recommendations. | **Exclude from baseline** unless separately approved and quality/privacy reviewed. | PARTIALLY IMPLEMENTED | WZP-INT-02 technical validation; WZP-INT-03, WZP-INT-04, and WZP-INT-05 require separate authorization; WZP-INT-06 and WZP-PUB-03 are prerequisites as applicable. | The PHP transport foundation exists, but classification is unavailable and no recommendation implementation is established. |
| Analytics and measurement | **Include** purpose-limited, privacy-governed measurement. | **Exclude behavioral analytics and telemetry by default.** Operational health signals only if approved and minimized. | **Exclude behavioral analytics by default; add only with purpose, privacy, consent, and retention decisions.** | NOT FOUND | WZP-01 product/privacy decisions; WZP-TRUST-02 only if administrative analytics are selected. | Avoid collection without purpose or approval; product acceptance can start with qualitative evidence. |
| Multilingual, global UX, and accessibility | **Include** approved locales, accessible UX, language-aware discovery, and regional support. | **Conditional:** use only owner-approved locale/region and responsive/accessibility criteria; no language is assumed here. | **Conditional:** explicitly approve supported regions/locales and full accessibility evidence for C. | NOT FOUND | WZP-01, WZP-API-01, WZP-PUB-04; owner-approved locale, accessibility, and browser/device criteria. | Existing responsive styling is not proof of accessibility or multilingual support; launch geography is unknown. |
| Public API and external integrations | **Include** versioned, secured APIs for approved future consumers. | **Exclude public API endpoints and unverified external integrations.** API-01 design may still be a prerequisite to PUB-03. | **Exclude from baseline** unless C names a supported consumer and contract. | NOT FOUND | WZP-API-01; WZP-API-02 only for approved endpoints and consumers. | `routes/api.php` is empty; server-rendered web routes are not a public API contract. |
| Corporate foundations and internal systems | **Include** distinct ORG/IAM/GOV foundations and approved internal capabilities. | **Exclude** from public beta. | **Exclude from baseline** unless a public-production requirement is approved. | DOCUMENTED ONLY | WZP-CORP-01, WZP-CORP-02, WZP-CORP-03, WZP-CORP-04, and WZP-CORP-05 with their business, identity, data, and security approvals. | Corporate design is documentation only and is not a prerequisite for a release that excludes corporate users. |
| Native Android and iOS | **Include** first-class mobile applications after evaluation. | **Exclude native apps**; responsive mobile-browser UX remains part of the website candidate. | **Exclude from baseline** unless separately assessed and selected. | NOT FOUND | WZP-MOB-01, WZP-API-01, WZP-API-02, WZP-MOB-02, WZP-MOB-03, plus selected public feature packages. | No mobile platform or app implementation is established; mobile-web support does not require a native project. |
| Administration and operations | **Include** support, safety, audit, monitoring, incident, recovery, and release controls appropriate to each approved domain. | **Require minimum beta support, incident, moderation/report, monitoring, pause, backup/restore, and rollback evidence; no admin console implied.** | **Require production-owned operations, monitoring, support, recovery, and Gate 3 evidence; no admin console implied.** | DOCUMENTED ONLY | WZP-OPS-01, WZP-03, WZP-AUD-01, WZP-AUD-02, WZP-BETA-01. | Release controls are mandatory gates, not optional product features; actual operating capability is not verified. |

## 5. Proposed B — initial controlled public beta

The Project Owner approved this website-first, content-first candidate in principle. It is not a frozen or release-ready inventory. Scope is limited to public website journeys that serve the knowledge core. Work-package completion, test evidence, policy decisions, support readiness, and Gate 2 remain prerequisites.

| Candidate capability | User value and proposed treatment | Existing status | Required work, safeguards, and acceptance | Dependencies and blockers |
|---|---|---|---|---|
| Public account, session, and basic profile | Let a reader register, sign in, manage a basic public profile, and publish under a public identity. **Conditional include.** | PARTIALLY IMPLEMENTED — web routes and controllers exist. | Approve profile visibility and account journeys; strengthen server-side registration validation; review login error behavior, session/CSRF controls, privacy, and denied/invalid cases. Verify successful and failed journeys without claiming account verification features that are not present. | WZP-01, WZP-02, WZP-PUB-01; security/privacy owner and acceptance decisions. No corporate IAM dependency. |
| Text Writ publishing, reading, and editing | Let authors create and readers retrieve useful written knowledge. **Approved in principle; conditional on acceptance and package completion.** | PARTIALLY IMPLEMENTED — create/read/edit/delete routes and models exist. | Approve publishing, editing, and recovery acceptance; resolve WZP-PUB-01 draft/recovery cases; define content lifecycle, visibility, author authorization, validation, removal/retention, empty/error states, responsive reading, and accessible authoring. Keep media/rich-content additions excluded unless separately approved. Bound feed retrieval and validate content journeys before beta. | WZP-01, WZP-02, WZP-PUB-01; content and privacy decisions. Public UGC reporting/support gap remains. |
| Basic non-AI discovery | Help readers locate relevant knowledge. **Conditional include.** | PARTIALLY IMPLEMENTED — `SearchService` and Writ/hashtag/user search exist. | Approve query and relevance criteria. If included, complete the approved WZP-PUB-03 foundation: stable content identifiers, metadata, explicit relationships, provenance, and basic search/discovery. Do not market keyword search as a Knowledge Graph or AI. | WZP-01, WZP-02, WZP-PUB-01, WZP-API-01, WZP-PUB-03. WZP-PUB-03 awaits owner decisions on taxonomy/relevance. |
| Save/bookmark knowledge | Allow a user to revisit useful content. **Excluded from baseline B by the Project Owner decision.** | PARTIALLY IMPLEMENTED — bookmark model/controller/routes exist; current route/identifier mismatch is documented. | Keep excluded. A future scope request must reconcile the route and public-ID contract, identify its package owner, and add authorization and regression evidence. | WZP-02 for the known route defect; separate future scope decision required. Do not silently expand WZP-PUB-01 or WZP-PUB-03. |
| Public content reporting and response | Give beta users a safe path to report harmful or incorrect public content and receive appropriate handling. **Required release dependency; implementation scope unresolved.** | NOT FOUND in the inspected PHP routes/controllers. | Project Owner and Trust/Safety authority must approve minimum report intake, triage, action, escalation, appeal/communication where applicable, privacy, and operating ownership. No unstaffed moderation promise. The package register currently sequences WZP-TRUST-01 after WZP-PUB-02; decide whether content-only reporting is covered by that package or requires an approved package-boundary adjustment. | Beta governance requires reporting, moderation, support escalation, and incident response. WZP-TRUST-01 is AWAITING OWNER DECISION; Trust/Safety owner unassigned. This gap blocks public beta readiness, not unrelated source-only work. |
| Responsive website and accessible core journeys | Provide a coherent desktop and mobile-browser experience for the selected reading and creation journeys. **Conditional include.** | PARTIALLY IMPLEMENTED — templates, viewport metadata, and CSS breakpoints exist; user-journey accessibility is NOT VERIFIED. | Approve browser/device and accessibility criteria; verify keyboard, readable content, navigation, forms, error/recovery states, and representative viewport widths. Mobile-browser support does not mean native apps. | WZP-01, WZP-02; WZP-PUB-04 decisions if locale/accessibility scope is selected. No WZP-MOB-02 dependency. |
| Beta support and operations | Operate the bounded beta safely. **Required gate dependency, not an optional feature.** | DOCUMENTED ONLY — roadmap controls describe requirements; environment operation is NOT VERIFIED. | Assign support/on-call and incident owners; define cohort, period, notices, issue intake, privacy-safe monitoring, pause/disable conditions, backup/restore, and rollback/forward repair evidence. | WZP-OPS-01, WZP-03, WZP-AUD-01, then separate WZP-BETA-01 approval. All are pending, planned, or unauthorized as recorded in the package register. |

Cross-cutting B controls apply to every selected capability: security and privacy review, relevant negative and recovery cases, isolated regression and user-journey evidence, and an accountable acceptor. Server-rendered pages do not require a public API implementation or native mobile app; WZP-API-01 remains a dependency where the canonical PUB-03 package requires it. Responsive mobile-browser coverage is a website acceptance requirement, not mobile-app scope. WZP-OPS-01 and beta governance apply to the complete B inventory. No aggregate PHP test or application test was run for this documentation proposal.

### B default exclusions

Exclude comments/replies/votes/follows, Q&A, communities, notifications, bookmarks, public API implementation, native apps, AI recommendations, corporate administration, and behavioral analytics from baseline B, as decided by the Project Owner. Media and expanded localization remain outside baseline pending separate approval. Future inclusion needs owner approval and its safety, API, privacy, operations, and package dependencies. Exclusion does not mean a feature is unsafe in every design or cleared for a future release.

## 6. Proposed C — initial production inventory

C should be a separately frozen inventory derived from capabilities that successfully pass beta. **Do not automatically carry a B feature into C.** This D01 decision does not approve C-only capabilities; any such addition requires a separate Project Owner scope decision, package approval, dependencies, tests, owner, and Gate 3 audit.

| Production candidate | Proposed C treatment | Evidence and conditions |
|---|---|---|
| B content, account/profile, and discovery capabilities that successfully pass beta | C is a separate, frozen inventory; include only capabilities supported by beta evidence, issue resolution, product acceptance, and Gate 3. | No automatic promotion from B. Any C-only capability requires a separate Project Owner scope decision and its own approved package; bookmarks remain excluded unless separately decided. |
| Public content safety, support, and incident response | Required for the actual public production scope, sized to included publishing and interaction capabilities. | Policy and operating ownership must be approved; the content-only reporting gap must be closed. Any discussions/communities require their full moderation and appeal path. |
| Production operations and recovery | Required: production configuration ownership, monitoring/alerting, incident response, support, backup/restore, and rollback/forward-repair evidence for the exact C inventory. | WZP-OPS-01 and WZP-AUD-02 evidence; no production environment was accessed or verified. |
| C-only user-facing capabilities | Not approved by this decision. | Require a separate Project Owner scope decision, approved package, and full Gate 3 review before inclusion. |

Initial production does not mean completing the whole programme. Native apps, corporate systems, AI, and analytics are not required when absent from frozen C. Production launch, regions/languages, communications, and exact release identity still require separate approval.

## 7. Explicit exclusions and deferred capabilities

The following are **excluded from approved-in-principle B and baseline C**, while remaining possible parts of A or a later separately approved scope:

- **Interactions:** comments, replies, votes, follows, Q&A, and communities. Current interaction code is partial; WZP-PUB-02 requires shared safety/permission/reporting design and stable public foundations. If selected later, its moderation path is a release dependency.
- **Bookmarks:** excluded from baseline B by the Project Owner decision; the current route/identifier mismatch also requires correction before any future scope request.
- **Notifications:** excluded until GET is read-only and an explicit state-changing action has approved semantics and regression evidence.
- **Advanced knowledge graph, semantic classification, and recommendations:** defer beyond the approved non-AI WZP-PUB-03 foundation. WZP-INT-02 validation remains open; WZP-INT-03, WZP-INT-04, and WZP-INT-05 are not authorized, and classification is unavailable.
- **Public API/integrations:** no public API route or contract is established. API-01 contract design and API-02 implementation are distinct; neither is implied by server-rendered web forms.
- **Native Android/iOS:** no platform decision or application exists. Mobile-web responsiveness is included as web acceptance only.
- **Corporate ORG/IAM/GOV/admin:** design documents do not establish runtime capability. Corporate foundations are independent unless a release explicitly includes them.
- **Analytics/behavioral telemetry:** no collection by default. Purpose, consent, minimization, retention, access, and owner approval are required before inclusion. Operational health signals must also be privacy-minimized.
- **Media, creator suites, and expanded localization:** not established in source and not justified as first-release blockers. Supported locale/region decisions remain open.

## 8. Work-package dependency mapping

Use only package IDs in the canonical [work-package register](../Roadmap/02-WORK-PACKAGE-REGISTER.md) and [dependency register](../Roadmap/03-DEPENDENCIES-AND-SEQUENCING.md). Current statuses below are copied from the local roadmap and are not changed by this proposal.

| Candidate scope | Canonical packages | Recorded status and dependency interpretation |
|---|---|---|
| Scope, journeys, policy, owner appointments | WZP-01 | **AWAITING OWNER DECISION.** D01 is **APPROVED WITH CONDITIONS**; D02-D13 remain unapproved or awaiting their stated evidence; D05 specialist appointments remain outstanding. |
| Safe source/test baseline | WZP-02 | **BLOCKED.** Follows approved WZP-01 scope. DB-dependent verification additionally needs a separately approved disposable target and independent identity validation. |
| Account/profile and content creation | WZP-PUB-01 | **PLANNED**; depends on WZP-01 and WZP-02. |
| Search/discovery | WZP-PUB-03 | **AWAITING OWNER DECISION**; depends on WZP-01, WZP-02, WZP-PUB-01, and WZP-API-01. It does not depend on classifier/recommendation packages. |
| Bookmark/save, if selected | WZP-02 plus a Project Owner-approved allocation to an existing package scope | WZP-02 can reconcile the route defect; the register does not explicitly assign the full bookmark capability. Do not invent a package ID or change a package boundary here. |
| Interaction expansion | WZP-PUB-02 | **BLOCKED**; depends on shared WZP-01 design, WZP-02, WZP-PUB-01, and WZP-API-01. It does not require WZP-TRUST-01 implementation first; a release with interactions does require the moderation path. |
| Trust workflows | WZP-TRUST-01 | **AWAITING OWNER DECISION**; canonical package currently follows WZP-PUB-02. Content-only beta reporting needs an explicit scope/ownership decision because B can exclude PUB-02 while still requiring a safe public-content response path. |
| API contracts/implementation | WZP-API-01, WZP-API-02 | API-01 **AWAITING OWNER DECISION**; API-02 **BLOCKED**. A web-only beta may need API-01 planning if required by PUB-03, but does not imply a public API implementation. |
| Intelligence | WZP-INT-02, WZP-INT-03, WZP-INT-04, WZP-INT-05, WZP-INT-06 | INT-02 **AWAITING TECHNICAL VALIDATION**; INT-03, INT-04, and INT-05 **NOT AUTHORIZED**; INT-06 **PLANNED**. All excluded from B and baseline C. |
| Corporate capabilities | WZP-CORP-00, WZP-CORP-01, WZP-CORP-02, WZP-CORP-03, WZP-CORP-04, WZP-CORP-05 | CORP-00 **REPORTED COMPLETE (design only)**; CORP-01 **BLOCKED**; CORP-02, CORP-03, and CORP-04 **AWAITING OWNER DECISION**; CORP-05 **PLANNED**. Excluded unless explicitly selected. |
| Mobile apps | WZP-MOB-01, WZP-MOB-02, WZP-MOB-03 | MOB-01 **AWAITING OWNER DECISION**; MOB-02 **BLOCKED**; MOB-03 **PLANNED**. Excluded from B and baseline C; mobile assessment is a separate decision. |
| Operations and gates | WZP-OPS-01, WZP-03, WZP-AUD-01, WZP-BETA-01, WZP-AUD-02 | OPS-01 **AWAITING OWNER DECISION**; WZP-03 **PLANNED**; AUD-01 **PLANNED**; BETA-01 and AUD-02 **NOT AUTHORIZED**. A beta requires the frozen B dependency inventory and Gate 2 pass, followed by separate project-owner approval. C requires beta exit, OPS readiness, Gate 3, and separate launch approval. |

The canonical dependency graph is acyclic. In particular, WZP-PUB-02 precedes WZP-TRUST-01 implementation, avoiding the former mutual dependency. The proposed content-only beta reporting requirement exposes a **package-coverage question**, not a cycle: resolve whether WZP-TRUST-01 covers content-only reporting or approve another package boundary before implementation. Do not mark WZP-01/WZP-02 or later package statuses complete based on this proposal.

## 9. Security, privacy, and Trust/Safety dependencies

- Keep consumer identity separate from any corporate identity. Enforce authorization on the server for each protected content action; do not infer rights from profile fields.
- Review registration validation, generic authentication failure behavior, session lifecycle, CSRF protection, rate limits, ownership checks, input/output handling, and safe deletion/retention before B.
- Treat publishing as public user-generated content until visibility rules say otherwise. Define report intake, triage, moderation authority, response/escalation, user communication, and appeal handling before inviting beta users. Do not promise response coverage without an appointed owner and operating plan.
- Keep comments, votes, follows, communities, and notifications out of the baseline because their abuse, moderation, privacy, and state-transition requirements are not closed. If later selected, include the entire safety path in the audited release inventory.
- Do not enable optional intelligence, transmit content to the Python service, or use external AI providers in B/C under this proposal. Any future use needs separate quality, privacy, consent, and implementation decisions.
- Do not collect product analytics by default. Any proposed metric must identify purpose, source, access, minimization, consent basis where applicable, retention, and reviewer. No numerical KPI or retention period is inferred.
- Beta and production require privacy-reviewed notices, data handling, support escalation, monitoring, incident response, backup/restore, rollback or forward-repair, and release-specific audit evidence.

## 10. Proposed acceptance principles

Approve observable qualitative rubrics before implementation; do not invent numeric targets. For every selected journey, verify:

1. **Knowledge value:** an author can publish useful material and a reader can understand and retrieve it; content clarity matters more than engagement totals.
2. **Journey completeness:** success, empty, invalid, denied, failure, and recovery states work for each selected flow, including account, publish, read, search, and save where selected.
3. **Security and privacy:** authorization is checked for the correct resource; invalid requests fail safely; user content, credentials, and private data do not leak through errors, logs, or unintended profile exposure.
4. **Discovery quality:** search results and ordering are reviewed against owner-approved representative queries and a documented qualitative relevance rubric; do not imply semantic ranking.
5. **Responsive accessibility:** keyboard and assistive-technology use, readable content, consistent navigation, forms, and agreed desktop/mobile-browser widths are reviewed. Locale and device matrices require approval.
6. **Trust and recovery:** public content has an owned report/response path; abuse, support, incident, disable/pause, backup/restore, and rollback scenarios are rehearsed for the release scope.
7. **Evidence:** requirements map to source changes, regression/security/accessibility checks, user-journey evidence, reviewed diffs, and named acceptors. Static documentation alone is not acceptance evidence.

Acceptance authority follows the roadmap: product accepts user outcomes; engineering accepts architecture; security, privacy/legal, accessibility, Trust/Safety, and Operations owners accept their domains; the Project Owner approves B/C and release decisions. No one approval substitutes for another.

## 11. Major risks and unresolved decisions

| Risk or decision | Impact on the proposed scope | Required closure |
|---|---|---|
| WZP-01 remains open and WZP-02 remains blocked | No stable, fully approved delivery baseline exists. | D01 is approved with conditions; close its remaining conditions and other WZP-01 decisions/appointments. WZP-02 separately closes test/source reconciliation. |
| No content-only report/moderation mapping | Public Writ publication can create harmful content without an established response path. | Trust/Safety and Project Owner decide the minimum content-only control, package owner, authority, support and escalation. |
| Account/profile security and privacy | Existing form code alone does not prove production-safe identity journeys. | Approve fields/visibility and acceptance; address registration and authentication findings in the authorized package. |
| Search/save readiness | Existing basic search needs relevance acceptance; bookmark identifier mismatch can break saved content. | Product approves use cases; WZP-02 resolves the route contract; PUB-03 completes its dependencies and evidence. |
| Notification read mutation | A GET request changes notification state. | Keep notifications excluded until separately corrected and tested. |
| No approved locale, region, cohort, or duration | Beta users, availability, notices, accessibility, and support boundaries cannot be frozen. | Project Owner and product/privacy/operations owners specify these for B and C. |
| Unappointed roles and no operational verification | Independent assurance and safe beta response cannot be demonstrated. | Close D05 appointments for selected packages; establish WZP-OPS-01 evidence. |
| Database/schema and test uncertainty | Database-dependent correctness cannot be assumed from source or schema snapshot. | Follow WZP-02; do not infer live schema or run unsafe aggregate checks. |
| Scope expansion pressure | Mobile, AI, corporate, interactions, or telemetry could multiply dependencies and risk. | Require versioned change control and re-audit the selected inventory rather than adding by default. |

No completion date, staffing capacity, market demand, numeric product KPI, supported country, or deployment status is verified. These gaps must not be filled with estimates presented as commitments.

## 12. Scope alternatives and trade-offs

| Alternative | Benefits | Costs and risks | Recommendation |
|---|---|---|---|
| **1. Bounded website-first knowledge beta** — text publishing/reading/editing, basic public profile, non-AI discovery; bookmarks, interactions, and advanced systems deferred. | Tests the core Knowledge Hub value with the smallest product surface; matches current PHP MVC and avoids mobile/API/AI implementation prerequisites. | Still needs safe account/content handling, reporting and support for public UGC, source stabilization, and search acceptance. | **Owner-approved in principle**, subject to the conditions and gates above. |
| **2. Interaction-rich beta** — add comments, votes, follows, Q&A, or communities. | Tests participation and social learning sooner. | Requires shared permission/reporting/moderation authority, abuse cases, appeals/response operations, and WZP-PUB-02 plus safety dependencies. Current controls and ownership are incomplete. | Defer until the complete safety path and operators are approved. |
| **3. Platform-wide launch** — include native mobile, public API, corporate tools, AI/recommendations, and analytics at first release. | Broad capability story. | Conflicts with current package statuses, absent implementation, unresolved owners, API/mobile prerequisites, consent/quality work, and corporate decisions; creates avoidable scope and release risk. | Do not select as the initial B/C baseline. Keep as future A scope only where approved. |

## 13. Recorded Project Owner decision

On **2026-11-08**, the WriteZone Project Owner recorded **APPROVED WITH CONDITIONS** for WZP01-D01:

1. **A:** approved as the long-term Knowledge Network and Knowledge Hub programme direction, subject to each capability's package approval, dependencies, ownership, acceptance, and release controls.
2. **B:** approved in principle as a website-first, content-first candidate: public account/session/basic profile; text Writ publishing, reading, and editing; basic non-AI knowledge search/discovery; responsive desktop and mobile-browser experience; and minimum operational, security, privacy, and recovery controls. Bookmarks, comments, replies, votes, follows, Q&A, communities, notifications, public API implementation, native applications, AI recommendations, corporate administration, and behavioral analytics are excluded from baseline B.
3. **C:** approved in principle as a separate inventory derived from capabilities that successfully pass controlled beta. C must be frozen after beta evidence, issue resolution, product acceptance, and Gate 3 audit. B is not automatically promoted to C.
4. Public publishing must not proceed to beta invitations before an accountable content-reporting and response process is approved and operational. The Project Owner requires an explicit determination whether WZP-TRUST-01 covers content-only reporting when WZP-PUB-02 interactions are excluded or whether a package-boundary adjustment is needed.
5. D01 approves scope direction only. It does not authorize runtime implementation, database operations, migrations, unsafe tests, commits, pushes, deployment, beta invitations, or production launch.

## 14. Conditions still outstanding

The Project Owner decision lists these conditions before public beta approval:

1. Resolve content-only reporting and Trust/Safety package ownership.
2. Approve publishing, editing, and recovery acceptance criteria; reconcile WZP-PUB-01 draft/recovery cases with the selected journeys.
3. Approve discovery and search acceptance criteria.
4. Appoint support, operations, Trust/Safety, and relevant security/privacy authorities.
5. Approve beta geography, eligibility, duration, notices, and support limits.
6. Complete WZP-02 source/test-safety and schema-reconciliation exit evidence.
7. Complete WZP-OPS and applicable audit-gate evidence.

No beta invitations are authorized. WZP-01 remains **AWAITING OWNER DECISION** until all applicable exit criteria and decisions are recorded; WZP-02 remains **BLOCKED** until its entry conditions are satisfied and separately approved. D01 is **APPROVED WITH CONDITIONS**, but this status does not change either package status or grant implementation authority.
