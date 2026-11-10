# WZP-01 Baseline Scope Inventories

**Programme:** WZ-MASTER-ROADMAP-2027.2

**Evidence date:** 2026-11-09

**Package state:** WZP-01 OPEN / AWAITING OWNER DECISION

**Authority:** Project Owner consolidated decision dated 2026-11-09

This is the central WZP-01 scope reference. It records approved direction and the approved-in-principle baseline B candidate. It is not a frozen release inventory, implementation plan, runtime acceptance result, or approval to start a work package. The Project Owner decision remains controlling; the roadmap package register controls package status, and its dependency register controls package edges.

## Status vocabulary

- **APPROVED DIRECTION:** long-term product direction accepted under D01.
- **APPROVED IN PRINCIPLE:** candidate release direction accepted, but the inventory is not frozen.
- **CONDITIONAL:** inclusion or release depends on stated decisions, dependencies, evidence, and gates.
- **EXCLUDED:** outside baseline B under the recorded Project Owner decision.
- **DEFERRED:** intentionally left for a later separately approved scope.
- **NOT IMPLEMENTED:** no corresponding source capability was found in the inspected scope, or the existing code does not meet the stated capability.
- **EVIDENCE REQUIRED:** source presence alone is insufficient; specified tests, review, or operational evidence is missing.
- **NOT AUTHORISED:** implementation or release action requires a separate written approval.

“Source confirmed” means a static source path or behavior was inspected. It does not establish safe end-to-end behavior or database, runtime, staging, production, or deployment state.

## A. Programme direction

WriteZone is a global Knowledge Network and Knowledge Hub. The long-term programme includes creating, connecting, discovering, and preserving useful knowledge, with content quality and users’ time valued above superficial engagement. These are program capabilities, not claims of delivery.

| Programme capability | Direction | Evidence and boundary |
|---|---|---|
| Knowledge publishing and profiles | APPROVED DIRECTION | Public authoring, reading, profiles, and creator capabilities evolve under separately approved packages. Current PHP paths are partial source evidence only. |
| Content, social, and knowledge relationships | APPROVED DIRECTION | Graphs provide context as the product evolves. Existing relations or ranking signals do not establish a complete Social Graph or Knowledge Graph. |
| Discovery and intelligence | APPROVED DIRECTION | Search and recommendation are distinct. Baseline B is non-AI keyword discovery only; semantic classification and AI recommendations are excluded. |
| Trust, recognition, and communities | APPROVED DIRECTION | Reporting and governed participation may evolve through approved Trust/Safety work. Recognition, comments, Q&A, and communities are not implied in B. |
| Multilingual/global experience and mobile | APPROVED DIRECTION | Locales, jurisdictions, native platforms, and support coverage require their own decisions and evidence. Mobile-browser responsiveness is separate from native applications. |
| Analytics and corporate foundations | APPROVED DIRECTION | Analytics require purpose and privacy approval. WZ-ORG/IAM/GOV remain separate corporate domains, not baseline B dependencies unless later selected. |
| Architecture | APPROVED BASELINE | Continue the existing PHP MVC modular monolith incrementally. Do not infer microservices, a workforce system, ERP, IdP, legal entity, or a new framework. |

## B. Website-first, content-first candidate

The candidate includes public registration/authentication, basic profiles, text Writ creation/publishing/reading/editing, basic non-AI keyword discovery, responsive desktop/mobile-browser use, and minimum security, privacy, safety, recovery, and operational controls. D02 approves these journeys and their broad boundaries; it does not say the current source satisfies them.

| Capability / package | Candidate journey and status | Boundaries and exclusions | Dependencies | Current source evidence | Missing implementation, verification, and pre-beta conditions |
|---|---|---|---|---|---|
| Account and authentication — WZP-PUB-01 | APPROVED IN PRINCIPLE; CONDITIONAL | Register, sign in, sign out, and reach an authenticated account. Corporate identities remain separate. Registration, session and error boundaries must be safe. Password recovery inclusion is unresolved; no recovery route was found. | WZP-01, WZP-02. | `routes/web.php:39-72` has guest/auth, rate-limit, and CSRF middleware on state-changing auth routes. `app/Controllers/AuthController.php:15-84` handles login/register. | Server-side validation, duplicate/collision handling, generic login failures, safe recovery boundary, session/user journeys, and negative security tests are required. Beta requires Gate 1 evidence and operational support. |
| Basic public profile — WZP-PUB-01 | APPROVED IN PRINCIPLE; CONDITIONAL | Show only approved public fields and Writ attribution. Follows, social counts, and other interaction surfaces are excluded from B. Exact profile fields/visibility require owner confirmation. | WZP-01, WZP-02. | `routes/web.php:160-163`, `app/Controllers/ProfileController.php:16-47`, `resources/views/profile/show.php:4-100`. | Approve visible fields/privacy and empty/unavailable behavior; remove excluded follow controls from any B surface; verify ownership, disclosure, and responsive/accessibility journeys. |
| Text Writ create/publish — WZP-PUB-01 | APPROVED IN PRINCIPLE; CONDITIONAL | Text-only authoring and public publishing. Durable drafts, save/resume, full draft management, media, and permanent deletion are excluded unless separately approved. Length and Unicode rules are unresolved. | WZP-01, WZP-02; WZP-TRUST-01 safety path whenever public Writ publishing is in B. | `routes/web.php:97-115`; `app/Controllers/WritController.php:43-71`; `app/Services/WritPublisher.php:18-49`; `resources/views/writs/create.php:10-26`. | Server-side empty/length/content validation, public visibility decision, duplicate-safe retry, failure recovery, and a working report path must be verified. Current 1000-character form attribute is not server enforcement. |
| Public Writ reading — WZP-PUB-01 | APPROVED IN PRINCIPLE; CONDITIONAL | Public feed and detail reading, clear author attribution, and empty/unavailable states. Bookmarks, comments, replies, votes, and other interactions are excluded. | WZP-01, WZP-02; WZP-TRUST-01 before beta for public UGC. | `routes/web.php:92-107`; `app/Controllers/WritController.php:24-40,74-91`; `app/Models/Writ.php:29-60,111-129`; `resources/views/writs/index.php`, `resources/views/writs/show.php`. | Confirm visibility and removed/withheld states; ensure B does not expose excluded controls; verify attribution, unavailable content, reporting, and keyboard/mobile reading. |
| Authorized Writ editing — WZP-PUB-01 | APPROVED IN PRINCIPLE; CONDITIONAL | The author edits their own Writ. Permanent delete is excluded; existing edit/delete route and control presence do not make them accepted. | WZP-01, WZP-02. | `app/Policies/WritPolicy.php:10-18`; `app/Controllers/WritController.php:93-173`; edit view has a client-side `maxlength`. | Enforce server validation and owner-only denial for every request; decide visibility after edit; verify invalid identifiers, error recovery, and attribution. Hide/remove the B-excluded delete control and route from the frozen B surface. |
| Basic non-AI keyword discovery — WZP-PUB-03 | APPROVED IN PRINCIPLE; CONDITIONAL | Keyword search only; no semantic, AI, recommendation, or completed Knowledge Graph claim. Search fields, result scope, and relevance rubric need owner acceptance. | Current canonical graph: WZP-01, WZP-02, WZP-PUB-01, and WZP-API-01 for WZP-PUB-03. WZP-API-02 is not required absent approved API endpoints. | `routes/web.php:140-147`; `app/Controllers/SearchController.php:8-35`; `app/Services/SearchService.php:9-44`; `app/Models/Writ.php:153-174`; `resources/views/search/index.php`. | Define query validation/limits, searched fields, empty/invalid/unavailable states, representative query set, and qualitative relevance review. The mandatory API-01 package edge remains until separately changed through approved change control. |
| Responsive browser experience and minimum accessibility — WZP-PUB-01 cross-cutting acceptance | APPROVED IN PRINCIPLE; CONDITIONAL | Desktop and mobile-browser web layouts only. Native Android/iOS and expanded locale/global support are excluded. Baseline accessibility is acceptance evidence, not a claim of formal WCAG conformance. | WZP-01, WZP-02. WZP-PUB-04's broader locale/global capability is not automatically selected. | `resources/views/layouts/app.php:1-8` includes viewport metadata; `public_html/assets/css/app.css` has responsive media queries. | Run an agreed browser/width matrix, keyboard and accessibility review, and owner-approved journey assessment. Roadmap allocation between PUB-01 cross-cutting acceptance and broader PUB-04 scope needs to remain explicit. |
| Public Writ report/response — WZP-TRUST-01 | APPROVED WITH CONDITIONS; CONDITIONAL before beta | Content-only public Writ reporting, independent of WZP-PUB-02. Includes restricted intake, triage, urgent escalation, authorized action, outcome/evidence, reporter communication, support handoff, access/retention/audit. | WZP-01; WZP-PUB-01 for report-action integration. WZP-PUB-02 only for interaction moderation. CR-WZP-2026-11-09-01 is recorded. | NOT FOUND IN INSPECTED SCOPE: no public Writ report route/controller/view or operator workflow was found. | Appoint Trust/Safety and operations owners, decide fields/categories/actions/retention/coverage, implement and rehearse the approved process, and pass Gate 2 before beta invitations. No process is currently operational. |
| Quality, security, and operations gates — WZP-03, WZP-02, WZP-OPS-01, WZP-AUD-01 | CONDITIONAL; NOT AUTHORISED by this document | Applies only to the frozen inventory. Requires isolated evidence and independent review. BETA-01 remains a separate decision. | Current graph and gates; WZP-02 exit is before beta, not a WZP-01 closure prerequisite. | DOCUMENTED ONLY in roadmap/checklists; no tests were run for this package. | Establish safe test isolation, owner/reviewer assignments, technical evidence, operations rehearsal, Gate 2 pass, then obtain separate written beta authorization. |

### Candidate B exclusions and deferred scope

| Capability/surface | B status | Evidence/control note |
|---|---|---|
| Bookmarks | EXCLUDED | Bookmark form/route exists; no bookmark journey is accepted for B. |
| Comments, replies, votes, follows, Q&A, communities | EXCLUDED | Source routes/views expose several of these surfaces. Their interaction and moderation journeys must not be enabled in B absent a separate decision and safety path. |
| Notifications | EXCLUDED | Source includes notifications; behavior and safe state transitions are not accepted for B. |
| Public API endpoints | EXCLUDED | API-01 contract planning is a dependency of WZP-PUB-03 under the current graph; this does not authorize API-02 implementation. |
| Native mobile applications | EXCLUDED / DEFERRED | Responsive browser work is in B; app code, store scope, and device support are not. |
| AI classification, semantic inference, recommendations | EXCLUDED / DEFERRED | Python classifier is unavailable for classification; no content-to-Python workflow is approved. |
| Corporate administration/workforce | EXCLUDED / DEFERRED | Corporate design is not runtime evidence and creates no B dependency. |
| Behavioral analytics/optional telemetry | EXCLUDED | Telemetry remains disabled unless purpose, privacy, access, and retention are separately approved. |
| Durable drafts/save-resume and permanent Writ deletion | EXCLUDED | D02 expressly excludes these from B unless separately approved. |
| FeedInspector “Intelligence” panel | REQUIRES OWNER DECISION | `resources/views/partials/intelligence-panel.php:5-68` renders local ranking scores/confidence/signals on the feed. Static inspection did not show a Python call from this view. The owner specifically requires this panel to be assessed; do not describe it as an AI classifier or assume it is accepted in B. |

## C. Post-beta and future inventory

D01 defines C as a separately frozen initial-production inventory derived from capabilities that pass beta, issue resolution, product acceptance, and Gate 3. C has no approved feature list yet. No capability moves from B to C automatically.

| Future capability | Status and gate |
|---|---|
| Interaction/discussion, communities, Q&A, notifications, bookmarks | DEFERRED / EXCLUDED from B; require separate scope, package, safety, and acceptance decisions. |
| Native Android/iOS and public API expansion | DEFERRED; require platform/consumer, contract, security, support, and package approvals. |
| Semantic Knowledge Graph, AI intelligence, recommendations | DEFERRED; require separate quality, privacy/consent, product, and implementation decisions. |
| Corporate ORG/IAM/GOV systems | DEFERRED and independent of public beta unless selected; no enterprise source systems are assumed. |
| Behavioral analytics | EXCLUDED by default; any future collection requires documented purpose and privacy governance. |

## WZP-01 closure and release boundary

This inventory is a documentary candidate, not a frozen B/C authorization. The Project Owner must review and accept the versioned scope/acceptance records before WZP-01 closure. Baseline B must be frozen through the separate release process before Gate 2. WZP-01 remains OPEN / AWAITING OWNER DECISION; WZP-02 remains BLOCKED; implementation, beta, and production remain NOT AUTHORISED.

### Evidence sources

- [Consolidated Project Owner decision](WZP-01-PROJECT-OWNER-CONSOLIDATED-DECISION.md)
- [Roadmap work-package register](../Roadmap/02-WORK-PACKAGE-REGISTER.md)
- [Roadmap dependency register](../Roadmap/03-DEPENDENCIES-AND-SEQUENCING.md)
- Source paths listed in the capability table; no runtime or database state is asserted.
