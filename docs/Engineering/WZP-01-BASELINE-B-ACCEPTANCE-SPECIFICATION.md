# WZP-01 Baseline B Acceptance Specification

**Status:** Proposed observable acceptance specification for the owner-approved-in-principle B candidate

**Authority:** Project Owner decision WZP01-D02 and qualitative rubric WZP01-D04, dated 2026-11-09

**Implementation:** NOT AUTHORISED

**Test execution:** None performed for this documentation task

This document translates approved scope boundaries into reviewable acceptance outcomes. It is not proof of implementation and does not silently decide values the Project Owner or an appointed domain owner has not approved. Items marked **REQUIRES OWNER DECISION** must be resolved before the affected journey is frozen for implementation or beta.

## Acceptance evidence labels

- **SOURCE CONFIRMED:** static source was inspected; behavior still requires tests.
- **DOCUMENTED ONLY:** a policy or decision exists; runtime behavior is not established.
- **NOT FOUND IN INSPECTED SCOPE:** no corresponding route/controller/view was found in the inspected source set.
- **NOT VERIFIED:** static inspection cannot establish the claim.
- **REQUIRES OWNER DECISION:** an outcome, field, threshold, policy, or journey boundary is not established by approved evidence.

Each selected B task must have reproducible success, denial, invalid-input, failure, and recovery evidence as applicable. Product outcome acceptance, engineering review, security/privacy review, independent verification, and release approval remain distinct.

## 1. Account and authentication — WZP-PUB-01

### Registration

| Acceptance outcome | Verification evidence | Current evidence and gap |
|---|---|---|
| Missing, blank, malformed, over-limit, or unsupported registration values are rejected server-side without creating an account. | Isolated positive/negative request tests; confirm no account side effect on each rejection. | `AuthController::register()` trims username/email and hashes the password but has no explicit validation branches (`app/Controllers/AuthController.php:50-85`). The view's `required` attributes are client-side only. **NOT ACCEPTED.** |
| Duplicate email, username, and normalized-handle collisions produce a safe, understandable result and do not create a second identity. | Isolated collision tests against an approved test mode; no production/shared database. | Explicit duplicate/collision handling was not found in the controller. Database constraints were not treated as current state. **REQUIRES OWNER/ENGINEERING DECISION** on public error wording and handle rules. |
| Password rules, username/handle character rules, email canonicalization, and maximum lengths are documented and applied on the server. | Boundary-value and Unicode tests against the approved rules. | The controller removes non-ASCII alphanumeric/underscore characters from a derived handle; no accepted field policy is documented in the inspected registration path. **REQUIRES OWNER DECISION** for product rules; technical validation follows. |
| Successful registration creates one public account, establishes an authenticated session, and gives a clear success result. | Isolated account journey with successful redirect/session and exactly one resulting account. | Source calls `Auth::login()` after `User::create()` (`AuthController.php:63-84`). End-to-end behavior is **NOT VERIFIED**. |

### Sign-in, errors, and session

- A valid account/password pair authenticates and reaches the agreed destination; a failed sign-in has the same generic message/status for unknown account and incorrect password. `AuthController::login()` currently returns “User not found.” separately from “Invalid credentials.” (`AuthController.php:18-30`), so non-enumerating behavior is **NOT ACCEPTED**.
- Rejected login requests do not create or retain an authenticated identity. Failed attempts follow the existing guest, rate-limit, CSRF route boundary (`routes/web.php:46-51`). Verify actual middleware composition and failure behavior; static middleware declarations alone are not end-to-end proof.
- On successful login, the session identifier is regenerated; logout clears authentication and destroys the session. `core/Authentication/Auth.php:10-19,22-25` and `core/Session/Session.php:58-90` provide source evidence. Session configuration uses cookie-only, strict mode, and disables URL session IDs (`Session.php:17-20`); cookie flags come from configuration (`config/session.php`) and must be verified in the accepted environment without inspecting secrets.
- A CSRF-invalid or unauthenticated state-changing request is denied without changing account state. Route composition is source-confirmed in `routes/web.php:46-72`; regression coverage exists in `tests/SecurityCompositionRegression.php` and `tests/SessionSecurityRegression.php`, but neither was executed here.
- Authentication failures must not reveal whether an email exists or expose credentials, stack traces, or internal configuration.

**Account recovery boundary — REQUIRES OWNER DECISION:** No password recovery/reset route was found in `routes/web.php` or the inspected auth views. Decide whether self-service recovery is outside B and how locked-out users are supported before freezing account journeys. Do not imply an email verification or recovery feature exists.

## 2. Public profile — WZP-PUB-01

- A public profile resolves through the approved public handle and returns an unavailable state for an unknown handle without exposing private/internal fields.
- The profile presents only the fields approved for public display and attributes a Writ to the correct public author. `ProfileController::show()` loads a user by handle and their Writs (`app/Controllers/ProfileController.php:16-47`); the view renders username/display name, follower counts, follow control, and Writ attribution (`resources/views/profile/show.php:4-100`).
- **REQUIRES OWNER DECISION:** Approve the exact public profile field/visibility list, including whether display name and username are both public. Do not infer a private-profile mode.
- Follow controls/counts are tied to an excluded social graph journey and must not appear in frozen B. Existing source presence is not acceptance.
- Empty, unavailable, authorization, responsive, and keyboard-accessible profile states are tested with representative profiles and invalid handles. Runtime privacy and accessibility are **NOT VERIFIED**.

## 3. Writ creation and publishing — WZP-PUB-01

- Only authenticated requests may publish; invalid authentication, CSRF, and rate-limit states produce no Writ. Route declarations are source-confirmed at `routes/web.php:97-115`.
- Whitespace-only and empty content are rejected by server-side validation. Content is bounded by a Project Owner-approved maximum and Unicode counting rule. The present `maxlength="1000"` in `resources/views/writs/create.php:13-20` is only a browser hint; `WritController::store()` trims input and immediately calls the publisher (`WritController.php:49-71`). **REQUIRES OWNER DECISION** on the authoritative text limit and counting rule.
- A successful publish gives clear confirmation and the canonical Writ identifier; the resulting Writ has the authenticated author and the approved visibility. `WritPublisher::publish()` generates an ID and creates a Writ (`app/Services/WritPublisher.php:18-49`).
- **REQUIRES OWNER DECISION:** Confirm whether the B visibility model is strictly public-on-publish with no private/unlisted mode. The inspected feed/detail queries have no visibility predicate (`app/Models/Writ.php:29-39,111-129`). Do not rely on a schema snapshot for live behavior.
- Retrying the same submission after an uncertain response must not create an unintended duplicate. Source generates a new public ID and creates a row each call; no idempotency mechanism was found in the inspected path. Require a test that repeats the same logical submission and verifies one published Writ. The technical mechanism requires separate design; durable drafts/save-resume remain excluded.
- On recoverable validation or publishing failure, show a safe, understandable message and preserve submitted content where technically safe. Do not claim durable persistence or promise preservation after browser/session loss.
- Publishing creates public user-generated content. The Writ report/response path in WZP-TRUST-01 must be operational before beta. The current inspected routes/views contain no report action or operator workflow.

## 4. Writ editing — WZP-PUB-01

- The edit route is available only to an authenticated author; another account receives a denial and cannot change the Writ. Static source shows `WritController::edit()` and `update()` calling `WritPolicy::update()`; the policy compares `user_id` (`WritController.php:93-173`, `WritPolicy.php:10-13`). `tests/AuthorizationRegression.php` contains owner/non-owner policy checks but was not run.
- Invalid and unknown public identifiers return a safe unavailable state; identifier enumeration must not expose internal database IDs.
- The same server-side content validation and approved size/Unicode rules used for creation apply to edits. Current `maxlength` is client-side only.
- After a successful edit, content, author attribution, public visibility, and canonical URL remain consistent. Recovery from a failed edit leaves the prior saved content intact and preserves submitted content where safe.
- Permanent deletion is excluded from B. `WritController::delete()` and the delete form exist (`WritController.php:175-223`; `resources/views/writs/show.php:78-97`); the B surface must not present or permit that capability. Any future deletion requires separate approval and retention/privacy/moderation decisions.

## 5. Writ reading — WZP-PUB-01

- Public feed and detail pages show the same public Writ, its approved public author attribution, and readable content. No bookmark, comment, vote, follow, or discussion controls are part of B.
- Empty feed, no results, invalid identifier, unavailable/removed content, and server error have distinct understandable states without leaking internals.
- Reported content follows the WZP-TRUST-01 approved case policy. **REQUIRES TRUST/SAFETY DECISION:** whether content is temporarily hidden, remains visible, or is restricted during review; action authority and user messaging must be defined before beta. Permanent Writ deletion is not a B action.
- Desktop and mobile-browser reading preserves content order, attribution, navigation, and error/report access. Accessibility criteria are in section 8.
- Source confirms a public feed/detail path and current embedded interaction controls (`routes/web.php:92-107`; `resources/views/writs/show.php:13-98`). The accepted B rendering is **NOT VERIFIED**.

## 6. Discovery — WZP-PUB-03

- Discovery is bounded to non-AI keyword search. The interface and release language must not claim semantic search, recommendation, or Knowledge Graph completion.
- Empty query produces a clear initial state; whitespace, malformed, or over-limit queries are handled without an unbounded search; no-match results state that nothing matched; unavailable search produces a generic recoverable error.
- Query handling uses parameterized database queries. Static source shows `Writ::search()` prepares a `LIKE` query, descending-date ordering, and a `LIMIT 20` (`app/Models/Writ.php:153-174`); this does not establish execution safety/performance against a live database.
- **REQUIRES OWNER DECISION:** Exact search scope (public Writ text only, or also user names/handles and hashtags), normalization, query length, result ordering, and representative relevance rubric. The current `SearchService` searches users, Writs, and hashtags (`app/Services/SearchService.php:9-44`) and the search page exposes those groups (`resources/views/search/index.php`).
- Evaluate an owner-approved fixed set of representative queries for expected matches, understandable ordering, empty results, and safe errors. No numeric relevance threshold is approved.
- Current canonical package graph makes WZP-API-01 a prerequisite of WZP-PUB-03; honor that edge unless an approved change revises it. WZP-API-02 is not a prerequisite for a server-rendered GET page unless approved API endpoints are selected.

## 7. Responsive website

Evaluate desktop and mobile-browser layouts separately using a product/accessibility-owner-approved browser and viewport matrix. For each selected journey, verify no clipped controls or content, usable navigation/forms, readable Writ text, visible status/error messages, and no loss of task completion. Do not add native app criteria. The current layout includes viewport metadata and CSS media queries (`resources/views/layouts/app.php:1-8`; `public_html/assets/css/app.css:313,563`); this is not evidence of user-journey acceptance.

**REQUIRES OWNER DECISION:** The supported browser/device matrix and locale/region inventory. Until approved, report only the specific local viewports actually examined; none were exercised in this documentation review.

## 8. Minimum observable accessibility boundary

For every B journey, the future acceptance review should verify:

1. All primary links, buttons, forms, and report actions can be reached and operated by keyboard; focus is visible and order follows the task.
2. Inputs have programmatic names and instructions; errors identify the affected field and are perceivable without relying only on color.
3. Pages use a meaningful title, one page-level heading, ordered headings, and semantic main/navigation regions.
4. Text and interactive state remain perceivable under the project-approved contrast review; zoom/reflow does not hide content or actions at the approved widths.
5. Dynamic success, error, empty, and unavailable states are announced or otherwise exposed to assistive technology.
6. Writ content and attribution remain understandable when visual styling or icons are unavailable; Font Awesome icons do not replace accessible labels.

These are project-level observable checks, not a declaration of WCAG compliance or certification. A qualified accessibility reviewer and product owner must approve the exact test matrix; no assessment was run here.

## 9. Explicit acceptance exclusions

| Excluded capability | B acceptance outcome |
|---|---|
| Bookmarks | No bookmark journey, control, or endpoint is enabled in the frozen B inventory. |
| Comments/replies/votes/follows | No interaction controls or state transitions are presented or accepted. |
| Q&A/communities | No membership, posting, or community-administration journey is enabled. |
| Notifications | No notification delivery/read-state journey is enabled. |
| Public API endpoints | No API implementation is accepted in B unless a separately approved dependency is recorded; current API-01 planning dependency is not API-02 authorization. |
| Native apps | No Android/iOS build, store, offline, or native-device acceptance is part of B. |
| AI/semantic/recommendation | No classifier, semantic search, or recommendation output is accepted or advertised. |
| Corporate administration/workforce | No corporate identity, HRMS, ERP, or administration journey is included. |
| Behavioral analytics/telemetry | No behavioral collection; optional telemetry stays disabled absent separate approval. |
| Durable draft management/permanent deletion | No durable save/resume/full draft management or permanent delete action. |
| FeedInspector panel | **REQUIRES OWNER DECISION** on inclusion in B; it must not be represented as an AI model or accepted by default. |

## 10. Open decisions before B inventory freeze

- Public profile fields/visibility and public Writ visibility model.
- Account recovery scope/support boundary, registration field rules, and login failure wording.
- Authoritative content limit/Unicode counting and duplicate-safe submission behavior.
- Search fields, query limits, result order, and relevance rubric. WZP-API-01 remains a mandatory canonical prerequisite for WZP-PUB-03; it is not an unresolved owner choice absent a separately approved change request.
- Handling of reported content during review and the permitted action catalogue.
- Desktop/mobile browser matrix, accessibility reviewer, and FeedInspector inclusion decision.

These questions do not alter the approved broad B direction. Resolve them through the designated product/security/privacy/Trust/Safety authorities before implementing or freezing the affected journey. No application test, test suite, or runtime check was run for this document.
