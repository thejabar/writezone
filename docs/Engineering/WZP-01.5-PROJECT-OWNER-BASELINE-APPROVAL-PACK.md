# WZP-01.5 Project Owner Baseline Approval Pack

**Programme:** WZ-MASTER-ROADMAP-2027.2

**Review date:** 2026-11-09

**Recommendation:** READY WITH CONDITIONS

**Decision status:** No new Project Owner decision is recorded by this pack.

## 1. Executive assessment

The approved-in-principle Baseline B candidate is sufficiently bounded for formal Project Owner review, but it is not ready to be frozen or implemented. The broad journeys and exclusions are approved by WZP01-D01/D02. The WZP-01.4 acceptance specification makes the remaining product choices visible, but the Project Owner has not accepted the candidate documents or resolved those choices. The recommended path is to accept the documented candidate for WZP-01 closure review with the conditions in section 8, while keeping B unfrozen.

WZP-01 remains **OPEN / AWAITING OWNER DECISION**. WZP-02 remains **BLOCKED**. The recorded D05 appointment blockers and D07/D10 resolution blockers may remain as blockers under the consolidated decision; they do not authorize dependent work. No test, database, runtime, beta, or production readiness is established here.

## 2. Decisions already approved

These decisions are controlling and should not be reopened absent new evidence or an approved change request:

| Record | Approved position | What remains outstanding |
|---|---|---|
| D01 | Programme direction A; website-first/content-first B candidate approved in principle; C is a separate post-beta inventory. | B is not frozen; C is intentionally not defined yet. |
| D02 | Public account/authentication, basic public profiles, text Writ create/publish/read/edit, basic non-AI keyword discovery, responsive desktop/mobile-browser journeys, and stated validation/recovery boundaries. | The current source has not passed the acceptance criteria; specific field, visibility, recovery, query, and accessibility boundaries need disposition below. |
| D03 / CR-WZP-2026-11-09-01 | Content-only public Writ reporting belongs in WZP-TRUST-01 independently of WZP-PUB-02; the process must be operational before beta. | The scope is not implemented, staffed, rehearsed, or independently verified. No new package or status change is approved. |
| D04 | Qualitative task-based acceptance; behavioral analytics and engagement tracking excluded; optional telemetry disabled absent separate approval. | Journey evidence and any later telemetry decision. |
| D05 | Unappointed specialist roles may be recorded as explicit blockers; no people were appointed. | WZP-02 delivery owner and independent reviewer must be assigned before activation; operational roles before beta. |
| D06 | `database/schema/live-schema.sql` is the documented reference; migrations remain the executable history. | Repository reconciliation and live state remain unverified. |
| D07 | Schema differences are an explicit blocker; no semantics selected. | Resolver/data owner and technical evidence before schema decisions. |
| D08/D09 | Code-controlled fail-closed target policy and safe test invocation/isolation policy approved. | Implementation and independent verification before database tests or aggregate runner use. |
| D10 | PHP/Composer environment verification is an explicit blocker; only read-only verification is in the recorded boundary. | Appointment and environment verification. |
| D11–D13 | Notifications/bookmarks remain pending and outside B. | Revisit only for separately approved implementation or later inclusion. |
| D14 | Decision, implementation, GitHub release, beta, and production approvals are separate. | Each later action still needs its own approval. |

Exact beta geography, eligibility, cohort, duration, notices, and support limits may remain open until before Gate 2/WZP-BETA-01 under the already approved deferral. This is not an additional decision requested now.

## 3. A/B/C scope summary

- **A — programme direction:** the long-term Knowledge Network and Knowledge Hub, including publishing, discovery, graphs, communities, Trust/Safety, creator capabilities, intelligence, analytics, multilingual support, mobile, and corporate foundations. This is direction, not delivery evidence.
- **B — beta candidate:** public account/authentication, basic profile, text Writ create/publish/read/edit, basic non-AI keyword discovery, responsive browser experience, and required safety, privacy, security, accessibility, recovery, and operations controls. It is approved in principle, not frozen.
- **C — production:** a separately approved inventory derived after beta evidence, issue resolution, product acceptance, and Gate 3. No automatic B-to-C promotion.

The B exclusions remain: durable drafts/save-resume/full draft management, permanent Writ deletion, bookmarks, comments/discussion, follows, notifications, native applications, AI or semantic discovery, behavioral analytics, and corporate administration. Public API endpoints are not in B; the existing WZP-API-01 design dependency for WZP-PUB-03 remains in force.

## 4. Baseline B acceptance assessment

| Journey | Current evidence | Decision/evidence still required |
|---|---|---|
| Registration and authentication | Routes, controller, session regeneration, and rate-limit/CSRF declarations exist in source. Registration lacks explicit server-side validation in the inspected controller; login distinguishes unknown account from incorrect password. | Approve field/handle rules and the recovery boundary; later verify non-enumerating failures, sessions, and denial cases. |
| Public profile | Profile route/controller/view and Writ attribution exist. The view includes follow controls/counts, which are excluded from B. | Approve public fields and visibility; remove excluded surfaces from the frozen B build; test unknown/empty states and privacy. |
| Create and immediately publish a Writ | `WritController::store()` trims content and calls the publisher; the form's 1,000-character limit is client-side only. The publisher makes a new public ID on each call. | Approve text limit/counting and visibility; specify validation, safe error recovery, and duplicate-safe retry; verify server-side behavior. |
| Read and edit | Public feed/detail routes exist; `WritPolicy` checks owner identity for edit. The detail view contains excluded controls, and deletion is excluded. | Approve public visibility and reported-content review behavior; test author denial, invalid identifiers, edit recovery, attribution, and B surface exclusions. |
| Keyword discovery | `SearchService` searches users, Writs, and hashtags; Writ matches use parameterized `LIKE`, newest-first, limited results. | Approve searchable categories and qualitative relevance cases; no semantic, personalized, or AI claims. Preserve the mandatory WZP-API-01 prerequisite. |
| Responsive and accessible web | Viewport metadata and CSS breakpoints are source evidence only. No browser, keyboard, assistive-technology, or responsive journey was tested. | Approve the baseline browser/accessibility matrix and package boundary; obtain qualified review before acceptance. |

Source presence does not establish implementation acceptance. No application tests or user journeys were run for this assessment.

## 5. Content-only reporting assessment

The scope is **approved with conditions and specified**. Intake, restricted access, triage, urgent safety/spam escalation, review/action authority, outcomes/evidence, reporter communication, support handoff, audit, access, and retention are described in the CR specification. Its proposed operating flow and fields are expressly not approved policy.

Current state is separate by evidence class:

| State | Assessment |
|---|---|
| Scope decision | Approved under D03; CR recorded in Roadmap document 09 and reflected in the package register and graph. |
| Specification | Present in `CR-WZP-2026-11-09-01-CONTENT-REPORTING-SCOPE-SPECIFICATION.md`; operational values remain proposed/unresolved. |
| Implemented | No report route/controller/view or operator workflow was found in the inspected source scope. |
| Staffed/operational | No Trust/Safety, support, or operations owner is appointed; no staffed operation was verified. |
| Operationally tested / independently verified | Not performed. No rehearsal, runtime test, or independent audit evidence exists in this review. |

WZP-PUB-02 is conditional only for interaction moderation. Public Writ reporting does not depend on it. Public Writ publishing remains a beta safety dependency on an operational WZP-TRUST-01 path.

## 6. Package dependency findings

- The inspected canonical graph contains no WZP-PUB-01 self-dependency.
- WZP-PUB-03 has a **mandatory** WZP-API-01 dependency. Keep it unless an approved change request changes the graph. This dependency means contract/design work; it does not authorize WZP-API-02 public API implementation.
- WZP-TRUST-01 depends on WZP-01; WZP-PUB-01 is conditional for public Writ report integration; WZP-PUB-02 is conditional for discussion/community moderation only.
- Native apps, corporate systems, AI, WZP-API-02, and interaction packages are not B prerequisites unless separately selected or needed for safety. WZP-OPS-01 and WZP-AUD-01 are release gates, not feature prerequisites.
- Static review found no dependency cycle. Package statuses remain those in Roadmap document 02.

The acceptance specification's former request to “confirm” the API-01 prerequisite contradicted the canonical graph. It has been corrected to record the edge as mandatory, not as a new Project Owner decision.

## 7. Ownership and exit evidence

Product authority, technical architecture/PHP delivery, database/migration, QA, independent verification, security, privacy/legal, Trust/Safety, support, operations/environment, and release operator roles remain unassigned. The Project Owner's authority is confirmed; their identity is not recorded. No specialist appointment is inferred.

The WZP-01 exit register's decision-record criteria are documented or explicitly blocked as allowed by the controlling decision. D07 and D10 remain blockers to their technical work, not unresolved choices that this pack may decide. D08/D09 policy approval is documented, but database tests and aggregate runner use remain blocked pending technical implementation and independent verification. E-16 has been corrected to separate the satisfied blocker-record requirement from blocked WZP-02 verification.

E-01 and E-02 remain pending Project Owner review/acceptance of the A/B/C candidate and B acceptance record. E-08 remains documentary until appointed roles can be checked for actual independence conflicts. E-17 is a beta requirement, not a WZP-01 closure criterion.

## 8. Decisions requested from the Project Owner

These are proposed WZP-01.5 decisions only. None is approved unless the Project Owner records it separately.

| Proposed decision | Recommendation | Alternative | Consequence of deferral |
|---|---|---|---|
| **PO-01 — Accept the WZP-01.4 inventory as closure-review evidence** | Accept the A/B/C document and B acceptance record as the versioned candidate for WZP-01 closure review, while keeping B explicitly unfrozen. At closure, identify the exact document revision/content being accepted. | Return specific sections for correction or defer the affected capability outside B. | WZP-01 remains open; no WZP-02 activation. This does not freeze B or approve implementation. |
| **PO-02 — Public profile and Writ visibility** | B profiles show only the public handle, approved display name, and public Writ attribution. Publish Writs publicly on publish; do not add private/unlisted states. Exclude follow controls/counts. | Narrow profile fields further, or separately define private/unlisted visibility and its access rules. | Profile and publishing acceptance stay open; no private mode may be assumed. |
| **PO-03 — Account recovery boundary** | Exclude self-service password reset from B; state that recovery is unavailable until separately approved. Require an appointed support owner to decide any support-assisted path before beta. | Add a verified-email recovery journey under a separate design, mail delivery, abuse-control, and security review. | Do not promise account recovery; beta support boundaries remain open. |
| **PO-04 — Writ text limit and counting rule** | Use the existing 1,000-character form hint as a candidate maximum, then approve a server rule of 1,000 UTF-8 Unicode code points after trimming whitespace. Confirm storage compatibility before implementation. | Select another limit/counting unit after technical evidence. | Publishing/editing acceptance remains open; the form attribute alone is not enforcement. |
| **PO-05 — Keyword discovery surface** | Retain the existing three result groups (public users, Writs, hashtags), with Writ results newest-first, no personalization, and a fixed qualitative query set. Treat this as keyword search only. | Limit B search to public Writ text and author attribution. | WZP-PUB-03 acceptance remains open. The mandatory API-01 dependency is retained either way unless separately changed. |
| **PO-06 — FeedInspector panel** | Exclude the user-visible “Intelligence” panel from B. It displays local ranking score/confidence/signals and can be mistaken for an AI capability. | Keep only after explicit non-AI labeling, source explanation, user-value acceptance, and review. | B cannot be frozen with this surface unresolved. No Python classifier is established by the panel. |
| **PO-07 — B accessibility/package boundary** | Include the minimum responsive-browser and observable accessibility checks as cross-cutting WZP-PUB-01 acceptance. Keep full locale/global and native-platform scope in WZP-PUB-04 or later, outside B. | Select broader PUB-04 scope and accept its full locale/device criteria and dependencies. | Package allocation remains ambiguous; do not silently create/remove graph edges. |

The Project Owner should approve a recommended option, choose an alternative, or explicitly mark each item deferred/outside B. The scope of D03 reporting is already approved; its report categories, anonymity, action catalogue, retention, coverage, and staffing require appointed Trust/Safety, Security/Privacy, and Operations authorities rather than reapproval of D03.

## 9. Recommended WZP-01 closure conditions

1. Record the Project Owner's disposition for PO-01 through PO-07, or explicitly defer affected items outside B.
2. Accept or return the candidate scope and acceptance records; preserve B as unfrozen until the separate Gate 2 freeze.
3. Retain D05, D07, and D10 blockers exactly as recorded; do not invent appointments or technical resolutions.
4. Confirm the exit register reflects only WZP-01 record criteria; implementation, test, environment, and operational evidence remain later gates.
5. Issue a separate written WZP-01 closure decision only after its exit evidence has been reviewed. This pack does not close it.

## 10. Authorization boundary

This document does not authorize WZP-02, application changes, database access/writes, migrations, tests with side effects, environment provisioning, commits, pushes, merges, beta invitations, deployment, or production activity. WZP-02 requires formal WZP-01 closure, separate Project Owner approval, exact scope, assigned delivery owner and independent reviewer, and safe test boundaries. Beta and production retain their separate gates and approvals.

## 11. References

- [Controlling Project Owner decision](WZP-01-PROJECT-OWNER-CONSOLIDATED-DECISION.md)
- [WZP-01.4 scope inventory](WZP-01-BASELINE-SCOPE-INVENTORIES.md)
- [WZP-01.4 acceptance specification](WZP-01-BASELINE-B-ACCEPTANCE-SPECIFICATION.md)
- [WZP-01.4 exit evidence register](WZP-01-EXIT-EVIDENCE-REGISTER.md)
- [Canonical work-package register](../Roadmap/02-WORK-PACKAGE-REGISTER.md)
- [Canonical dependency graph](../Roadmap/03-DEPENDENCIES-AND-SEQUENCING.md)
- [WZP-01.5 review report](WZP-01.5-REVIEW-AND-RECOMMENDATION-REPORT.md)
