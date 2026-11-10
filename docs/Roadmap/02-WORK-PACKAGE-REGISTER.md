# Programme Work-Package Register

**Baseline:** WZ-MASTER-ROADMAP-2027.2
**Package count:** 32
**Status note (2026-11-09):** This register reflects the Project Owner's WZP-01.8 closure decision, the bounded initial WZP-02 authorization, WZP-02.1 planning, and WZP-02.12's conditional approvals for AP-01/AP-03. Those two units are not activated; their appointments and readiness conditions remain outstanding. WZP-02.10 verified the isolated PHP/Composer environment, correcting the earlier “unavailable tools” risk. Historical WZP-01.6/WZP-01.7 evidence is preserved. WZP-02.1 remains a controlled child documentation work item, not an addition to the 32-package programme baseline. No status here freezes Baseline B or authorizes feature implementation, database work, beta, production, deployment, or release.

## Controlled work-package status vocabulary

Every package uses exactly one status from this list. The status line begins with the exact bold value; the text after it records evidence and blockers. Status records are dated and must not be inferred from schedule dates, old checkmarks, code presence, or an untracked working-tree entry.

| Status | Meaning and permitted use |
|---|---|
| **REPORTED COMPLETE** | An owner reports completion, but the required acceptance evidence has not been independently checked for the stated scope. Never present it as verified. |
| **EVIDENCE VERIFIED** | The listed deliverables and acceptance evidence were checked against the package criteria for a named scope. This does not mean production-verified or authorize release. |
| **IN PROGRESS** | Work is actively underway within a separately approved package, with an assigned accountable owner and recorded evidence. |
| **PLANNED** | Included in the programme baseline but not started. It is not implementation authority; package-specific approval remains required. |
| **BLOCKED** | A mandatory package dependency or safety prerequisite is unmet. Name the blocker and the evidence needed to clear it. |
| **AWAITING OWNER DECISION** | A business, product, security, privacy, legal, finance, operations, or project-owner decision is required. Name the decision and authority role. |
| **AWAITING TECHNICAL VALIDATION** | A bounded technical proof, independent audit, compatibility check, or source/environment evidence is required before dependent work can proceed. |
| **NOT AUTHORIZED** | A separate explicit approval is required before the package may start; elapsed time or another package's approval cannot clear it. |
| **CLOSED WITH CONDITIONS** | The Project Owner has closed the decision/documentation package while preserving listed blockers and later-gate conditions. It does not imply runtime, environment, test, database, operational, beta, or release readiness. |
| **READY FOR CONTROLLED ACTIVATION** | The Project Owner has authorized only an expressly bounded package start. The exact scope and exclusions govern; this does not appoint unassigned roles, establish package completion, or authorize activity outside that scope. |

**Transitions:** PLANNED may move to IN PROGRESS only after explicit package approval, required dependencies, and owner/reviewer assignments. IN PROGRESS moves first to REPORTED COMPLETE on owner attestation. Only a reviewer with independent evidence may move it to EVIDENCE VERIFIED. Failed verification returns to IN PROGRESS or BLOCKED. A BLOCKED or AWAITING status returns to PLANNED only after the named prerequisite/decision is evidenced. NOT AUTHORIZED changes only after separate explicit approval. CLOSED WITH CONDITIONS requires a recorded Project Owner decision and preserves its exact conditions. READY FOR CONTROLLED ACTIVATION requires a recorded, bounded Project Owner authorization and preserves its exact scope; it is not a claim that work is complete or independently verified. Neither new status authorizes scope expansion or release. EVIDENCE VERIFIED remains limited to the recorded scope/environment and never implies beta, production, or deployment approval.

## Ownership and approval semantics

In each per-package “Proposed owner / approval authority” field, the role(s) before the spaced slash are proposed owner/delivery roles; the role(s) after it are proposed approval authorities. Neither side denotes an appointment. As of this baseline, the appointed accountable owner, technical delivery assignee, and independent verifier assignee are **UNASSIGNED** for every package. WZP-01 includes an owner-appointment milestone: record those three assignments and the approval authority for each package selected for the first release scope. An implementer cannot independently verify their own security, privacy, safety, data-integrity, or release audit. Do not invent staff, executives, departments, or legal authority.

## Acceptance evidence rule

Each Deliverables/acceptance entry must resolve to observable artifacts or user/system outcomes; the adjacent Verification entry identifies the evidence source and method. For every unapproved numeric or threshold target, record the measure, evidence source, verification method, and decision-owner role in the acceptance-target register before the target can be used to pass a package. No numeric target, SLA, staffing capacity, legal policy, or business threshold is invented by this roadmap. Qualitative terms such as useful, safe, reliable, fair, stable, or accessible require a reviewed rubric and observable test cases, not an unstructured opinion.

## Programme and public product

### WZP-00 — Master programme baseline

- **Objective:** Establish a dependency-aware programme and governance baseline.
- **Scope / exclusions:** Create the ten roadmap documents and reconcile documentation pointers. Excludes application implementation and release activity.
- **Dependencies / inputs:** None; owner command, AGENTS.md, architecture authority, and current product, engineering, corporate, intelligence, and release documentation.
- **Deliverables / acceptance:** Ten canonical roadmap documents and docs/README.md are present; all 32 package IDs are unique; direct and conditional references resolve; the complete dependency graph is acyclic; scope, acceptance, audit, and status rules agree across documents.
- **Verification:** Link, ID, dependency, milestone, gate, acceptance, and diff checks.
- **Proposed owner / approval authority:** Programme documentation owner / project owner.
- **Status / evidence:** **EVIDENCE VERIFIED** — All ten canonical roadmap documents and the index are present; local link, ID, dependency, schedule, gate, and whitespace checks passed. Documentation scope only.
- **Risk / unresolved:** Baseline content awaits project-owner review; no implementation follows automatically.

### WZP-01 — Product scope and architecture decision closure

- **Objective:** Record and evidence closure against the Project Owner-approved-with-conditions D01 direction, WZP01-D01–D14, and WZP-01.6 PO-01–PO-07 dispositions, including the B journey boundaries, content-only reporting/package boundary, qualitative acceptance approach, approved technical policies, explicit appointment/resolution blockers, and remaining scope inventories.
- **Scope / exclusions:** Requirements, decisions, acceptance, policy/permission design, owner appointment, and technical discovery. Excludes feature coding and moderation runtime implementation.
- **Dependencies / inputs:** WZP-00; current source/document evidence and project-owner/domain-owner decisions.
- **Deliverables / acceptance:** The versioned decision register and separate A/B/C scope inventories record the 2026-11-09 decisions without reopening D01; WZP-01.6 accepts the exact candidate scope and acceptance contents for closure review, while B remains unfrozen. The content-only Writ reporting scope and CR-WZP-2026-11-09-01 dependency split are documented. WZP-01.8 records the separate documentary closure with conditions, E-08's documentary-only adoption, and preserved appointment/resolution blockers. Each decision records authority, evidence, status and date.
- **Verification:** Decision review; compare approved scope against package register and architecture authority.
- **Proposed owner / approval authority:** Product/programme lead / project owner and relevant security, legal/privacy, operations owners.
- **Status / evidence:** **CLOSED WITH CONDITIONS** — WZP-01.8 records the Project Owner decision dated 2026-11-09. E-08 is accepted only at the documentary closure boundary. Specialist appointments, D07 schema semantics, D08/D09 technical test safety, D10 environment verification, and downstream product/operations conditions remain blockers for the work that requires them. Baseline B is an **APPROVED CANDIDATE, NOT FROZEN**.
- **Risk / unresolved:** Closure is documentary only. No runtime, test, database, environment, operational, beta, production, or release readiness is implied.

### WZP-02 — Platform stabilization and source reconciliation

- **Objective:** Establish the actual source/test baseline and resolve approved stabilization issues before broad feature work.
- **Scope / exclusions:** Audit affected PHP flows, migration history, regression safety, authorization, bookmarks/notifications/registration as applicable; create an implementation sequence. Excludes production database access and unapproved migrations.
- **Dependencies / inputs:** WZP-01; approved release inventory and source baseline. Database-dependent validation also requires a separately approved disposable target and independent identity validation.
- **Deliverables / acceptance:** The authorized initial read-only phase produces a verification plan and initial report covering source, migrations, test safety, local environment, security, Baseline B traceability, and independent-review readiness. Full WZP-02 exit still requires the package evidence in the plan, required assignments, safe test isolation, and separate approval for any database-dependent activity or correction. Database checks require approved disposable-target evidence.
- **Verification:** Relevant PHP syntax, targeted regression/security checks, user-journey and diff review under AGENTS.md.
- **Proposed owner / approval authority:** PHP/platform lead / project owner for scope, security owner for boundary changes.
- **Status / evidence:** **READY FOR CONTROLLED ACTIVATION** — this status refers to WZP-01.8's bounded initial local, read-only verification, not package completion. WZP-02.10 records local PHP 8.4/Composer verification. WZP-02.12 records conditional Project Owner approval for AP-01 and AP-03, but neither unit is activated; required delivery and independent-review roles and readiness conditions remain unassigned or unmet. The package is not complete. Database operations/tests, migrations, beta, production, and deployment remain unauthorized.
- **Risk / unresolved:** Migration/snapshot differences, unsafe aggregate test behavior, unresolved B and schema decisions, and unassigned delivery/independent-review roles remain. Database-dependent verification is blocked pending separate approval and approved target evidence. PHP/Composer unavailability is no longer current; WZP-02.10 records the verified isolated local environment.

### WZP-02.1 — Engineering remediation planning, ownership and approval closure (controlled child work item)

- **Objective:** Convert WZP-02 source findings into a dependency-aware, evidence-based correction plan and separate approval units.
- **Scope / exclusions:** Documentation, source investigation, and safe documentation validation only. Excludes runtime fixes, tests with side effects, database connections/writes, SQL/migrations, environment changes, and release activity.
- **Dependencies / inputs:** WZP-02 initial verification, WZP-01.8 closure conditions, WZP-01.6 candidate decisions, current local source, and architecture authority.
- **Deliverables / acceptance:** Eleven WZP-02.1 engineering documents plus the Engineering index and three roadmap references; every finding has evidence or an explicit limitation; approval units identify roles, exact scope, tests, rollback, and acceptance; Baseline B and existing architecture remain unchanged.
- **Verification:** Safe Markdown/link/identifier/status/fence/whitespace checks and `git diff --check`; no PHP tests or database verification.
- **Proposed owner / approval authority:** WZP-02 engineering owner / Project Owner; security, QA, database, product, Trust/Safety, operations, and independent reviewer appointments remain unassigned.
- **Status / evidence:** **AWAITING OWNER DECISION** — the planning documents and proposed approval pack are prepared for review. WZP-02.12 conditionally approves AP-01 and AP-03 but does not activate them. No source correction, test execution, database access, migration, or independent WZP-02 exit is authorized by the WZP-02.1 documents.
- **Risk / unresolved:** The WZP-02 owner and independent verifier remain unassigned; AP-01/AP-03 delivery and reviewer appointments, safe test isolation, remaining AP selection, and D07 semantics require decisions/evidence. PHP/Composer access is verified under WZP-02.10. This child work item does not increase the programme’s 32-package count or alter release dependencies.

### WZP-03 — Continuous engineering assurance

- **Objective:** Apply Gate 1 quality, architecture, security, and regression controls to every implementation package.
- **Scope / exclusions:** Review, test evidence, dependency hygiene, performance/accessibility as relevant. Excludes pre-beta and final audits, which remain separate gates.
- **Dependencies / inputs:** WZP-01; architecture authority, approved quality procedure, and reviewer-role assignments.
- **Deliverables / acceptance:** Each active package has a Gate 1 checklist, named independent reviewer, reproducible check results, finding disposition, and diff/status record; package acceptance cannot rely on an aggregate pass alone.
- **Verification:** Package-specific syntax/tests/security/user journey, release gate when preparing a release, exact diff and status review.
- **Proposed owner / approval authority:** Engineering/QA lead / relevant domain owner; project owner for scope changes.
- **Status / evidence:** **PLANNED** — Programme assurance control; no runtime implementation work is active under this package.
- **Risk / unresolved:** Empty testing/roadmap placeholders and changing working-tree state require an explicit evidence ledger.

### WZP-PUB-01 — Knowledge creation and profiles

- **Objective:** Deliver the approved, reliable creation and profile journeys for the public Knowledge Network.
- **Scope / exclusions:** Publishing, rich-content requirements, drafts/editing, profiles, creator identity and lifecycle as approved. For baseline B, durable draft storage, save/resume, full draft management and permanent Writ deletion are excluded unless separately approved. Excludes engagement-only mechanics and unapproved monetization.
- **Dependencies / inputs:** WZP-01, WZP-02; approved content/profile journeys and content, validation, and media decisions.
- **Deliverables / acceptance:** Approved B account/profile, text Writ compose/publish/read/edit, and recovery boundaries pass defined positive, denied, invalid, empty, error, and recoverable-failure cases. Preserve submitted content where technically safe and prevent unintended duplicate Writs on retry. Durable draft-management or permanent-deletion behavior is not part of B without separate approval; content/media rules and responsive/accessibility evidence are recorded.
- **Verification:** Authoring user journeys, authorization/input/security regressions, desktop/mobile and accessibility review.
- **Proposed owner / approval authority:** Product/content owner and PHP lead / project owner.
- **Status / evidence:** **PLANNED** — Current feature state must be reconciled against approved journeys before completion is claimed.
- **Risk / unresolved:** Rich-content scope and privacy/media handling are undecided.

### WZP-PUB-02 — Discussions, Q&A, communities, and Social Graph

- **Objective:** Establish the approved public discussion, Q&A, community, and Social Graph user journeys.
- **Scope / exclusions:** Comments/discussions, Q&A, community membership/roles, following/social connections, and any reporting affordance/API contract for those interaction surfaces designed under WZP-01. Public Writ content-only reporting is assigned to the bounded WZP-TRUST-01 slice under CR-WZP-2026-11-09-01. Excludes moderation enforcement implementation and assuming community administrators have platform-wide authority.
- **Dependencies / inputs:** WZP-01 shared safety/permission/moderation-authority design, WZP-02, WZP-PUB-01, WZP-API-01. No WZP-TRUST-01 implementation dependency; a release enabling interactions requires its moderation path.
- **Deliverables / acceptance:** Approved discussion, Q&A, community, membership, and interaction-report cases pass a role/state test matrix; unsafe states are denied. Release remains blocked until the applicable interaction moderation/enforcement path is integrated and verified. This does not cover Writ-level reporting.
- **Verification:** Multi-role journey tests, abuse/authorization cases, notification and appeal checks.
- **Proposed owner / approval authority:** Community product owner and Trust/Safety owner / project owner and security reviewer.
- **Status / evidence:** **BLOCKED** — Requires the WZP-01 safety/permission foundation, WZP-PUB-01, and WZP-API-01; moderation implementation follows this package.
- **Risk / unresolved:** Abuse, privacy, and community-policy decisions can block launch.

### WZP-PUB-03 — Search, discovery, and Knowledge Graph foundations

- **Objective:** Establish non-AI knowledge structures and discovery that help users find and connect useful content.
- **Scope / exclusions:** Stable content identifiers, metadata, explicit relationships, provenance/source references, basic non-AI search, and curated discovery. Excludes automated relationship inference, semantic classification, and personalized recommendation.
- **Dependencies / inputs:** WZP-01, WZP-02, WZP-PUB-01, WZP-API-01; product-approved taxonomy and search/relevance acceptance. Neither WZP-INT-05 nor WZP-INT-06 is a prerequisite.
- **Deliverables / acceptance:** Content identifiers remain stable across edits; metadata, explicit relationships, and provenance have fixtures; non-AI search/discovery contracts and query cases pass. Any relevance threshold is owner-approved before scoring.
- **Verification:** Query/knowledge tasks, relevance review, regression and load tests against approved targets.
- **Proposed owner / approval authority:** Search/discovery product owner and engineering lead / project owner; security/privacy approval for data use.
- **Status / evidence:** **AWAITING OWNER DECISION** — WZP-01.6 approves Baseline B's public-user, public-Writ, and hashtag result groups, newest-first Writ ordering, and non-AI/no-personalization boundary. Broader taxonomy, curated knowledge structures, and any package-specific relevance measures remain subject to their own product decisions. The non-AI foundation is independent of WZP-INT-05 and WZP-INT-06.
- **Risk / unresolved:** No product-approved taxonomy, quality targets, or classification availability.

### WZP-PUB-04 — Multilingual, global UX, and accessibility

- **Objective:** Provide consistent, inclusive desktop/mobile knowledge experiences across approved locales.
- **Scope / exclusions:** Localization/internationalization, language-aware discovery, responsive layout, accessibility, time/date/content conventions. Excludes declaring global compliance or language support without evidence.
- **Dependencies / inputs:** WZP-01, WZP-API-01; approved locales, accessibility criteria, design system, and content rules.
- **Deliverables / acceptance:** Approved locale inventory and fallback rules have fixtures; supported browser/device and accessibility checks have recorded results; numeric coverage targets require product/accessibility-owner approval.
- **Verification:** Locale fixtures, supported browser/device matrix, accessibility review and user testing.
- **Proposed owner / approval authority:** Product/design and accessibility leads / project owner; qualified legal/privacy review by region.
- **Status / evidence:** **AWAITING OWNER DECISION** — WZP-01.6 places minimum Baseline B responsive/accessibility acceptance cross-cutting in WZP-PUB-01 and keeps full multilingual/global and native-platform scope outside B. The applicable B browser/viewport matrix must be documented; broader locale and package scope remain future decisions.
- **Risk / unresolved:** Target countries/languages and legal requirements are unknown.

## Trust, APIs, and intelligence

### WZP-TRUST-01 — Trust, Safety, moderation, appeals, and recognition

- **Objective:** Implement safe reporting, moderation, enforcement, appeals, and contribution-recognition controls after public participation journeys are defined.
- **Scope / exclusions:** Reporting, triage, moderation, enforcement, appeals, reputation/recognition rules, transparency, and integration/end-to-end validation. The bounded content-only public Writ reporting slice is independently scoped from WZP-PUB-02 as approved in WZP01-D03. Discussion/community moderation remains integrated with WZP-PUB-02 when those journeys are selected. Excludes opaque automated penalties or unapproved behavioral scoring.
- **Dependencies / inputs:** WZP-01 policy/authority foundation; WZP-PUB-01 for end-to-end integration of the public Writ report action; WZP-PUB-02 only for discussion/community interaction moderation. Approved safety, privacy, legal, and operational decisions remain required for the applicable scope.
- **Deliverables / acceptance:** The content-only slice covers an accessible public Writ report action; restricted receipt/access; categorization/triage; urgent safety/spam escalation; authorized review/action; case outcome/evidence; reporter communication; support handoff; and approved access, retention, and audit arrangements. It must be staffed, operational and evidenced before beta. Interaction transitions and permissions receive separate role/state tests and end-to-end evidence with WZP-PUB-02 when those features are selected.
- **Verification:** Adversarial, permissions, appeal/state-transition, privacy and bias reviews.
- **Proposed owner / approval authority:** Trust/Safety and community policy owners / project owner and security/privacy reviewers.
- **Status / evidence:** **AWAITING OWNER DECISION** — WZP01-D03 approves the bounded content-only scope with conditions, but Trust/Safety, security/privacy/legal and operational owners remain unassigned; the reporting process is not implemented or operational. Interaction policy and package dependencies remain conditional on WZP-PUB-02.
- **Risk / unresolved:** Enforcement standards, reviewer capacity, and appeals authority are unassigned.

### WZP-TRUST-02 — Administrative control centre and analytics

- **Objective:** Give approved operators safe, auditable controls and decision-quality platform reporting.
- **Scope / exclusions:** Administrative shell, scoped permissions, support/moderation workflows, privacy-preserving analytics. Excludes universal administrator access or unapproved employee surveillance.
- **Dependencies / inputs:** WZP-01, WZP-TRUST-01, WZP-API-01; approved identity/authorization decision. WZP-CORP-03 is conditional only when corporate operators are in scope.
- **Deliverables / acceptance:** Every approved admin action maps to a scoped role and audit event; negative authorization cases pass; analytics purpose, fields, consent, and retention are reviewed before use.
- **Verification:** Negative authorization, audit review, privacy review, support journey tests.
- **Proposed owner / approval authority:** Platform administration and analytics owners / project owner, security/privacy reviewers.
- **Status / evidence:** **BLOCKED** — Requires WZP-TRUST-01, WZP-API-01, and approved identity/authorization boundaries.
- **Risk / unresolved:** Admin scope and analytic measures are not approved.

### WZP-API-01 — API contracts, versioning, and security boundaries

- **Objective:** Establish stable, documented contracts for web/mobile/backend consumers.
- **Scope / exclusions:** Resource schemas, versioning, authentication/authorization, pagination, error model, idempotency, rate limits, observability, compatibility policy. Excludes treating the internal Python client as public API.
- **Dependencies / inputs:** WZP-01, WZP-02; approved consumers, current routes/models, and threat/privacy review.
- **Deliverables / acceptance:** Versioned contract covers each approved consumer, authentication/authorization, errors, pagination, limits, idempotency, and compatibility; contract review and negative security cases are recorded.
- **Verification:** Contract lint/review, negative security cases, consumer-driven compatibility tests.
- **Proposed owner / approval authority:** API/platform lead / project owner and security owner.
- **Status / evidence:** **AWAITING OWNER DECISION** — Public consumers, authentication, versioning, compatibility, and error policy need owner/security approval.
- **Risk / unresolved:** Authentication model and public/mobile scope are undecided.

### WZP-API-02 — Approved API implementation and compatibility

- **Objective:** Implement only contracts approved in WZP-API-01 and support web/mobile clients safely.
- **Scope / exclusions:** PHP API routes, services, versioning and observability for approved public capabilities. Excludes publicizing private intelligence endpoints.
- **Dependencies / inputs:** WZP-API-01, WZP-02; completed domain package outputs for each endpoint selected for implementation.
- **Deliverables / acceptance:** Each included endpoint conforms to its approved contract; positive, negative, auth, compatibility, and regression cases pass; no breaking behavior is released without a version decision.
- **Verification:** Isolated API integration tests, auth/security regressions, schema compatibility and client fixtures.
- **Proposed owner / approval authority:** PHP/API lead / project owner and security reviewer.
- **Status / evidence:** **BLOCKED** — Requires approved WZP-API-01 contracts, source stabilization, and the domain packages for endpoints selected for implementation.
- **Risk / unresolved:** Unversioned behavior could couple mobile and web releases.

### WZP-INT-00 — WP5 Python service foundation

- **Objective:** Maintain the approved isolated Python intelligence service foundation.
- **Scope / exclusions:** Existing health/authentication/request validation/classification boundary. No model/provider integration.
- **Dependencies / inputs:** No predecessor in this roadmap; owner-accepted WP5/WP5.1/WP5.2 evidence and protected per-environment configuration.
- **Deliverables / acceptance:** Source revision, dependency pins, isolated test command/results, loopback/auth/bounds, generic error behavior, and disabled/unavailable classification behavior are recorded for the accepted service scope.
- **Verification:** Isolated service regression suite and security review; no production claim from local tests.
- **Proposed owner / approval authority:** Intelligence engineering and security owner / project owner for scope expansion.
- **Status / evidence:** **REPORTED COMPLETE** — Owner reports WP5/WP5.1/WP5.2 accepted; tests were not rerun for this roadmap.
- **Risk / unresolved:** Service authentication and network properties still require approved environment verification before deployment.

### WZP-INT-01 — WP6A Python API security boundary

- **Objective:** Preserve the protected Python classification boundary and its hardening.
- **Scope / exclusions:** Authentication-before-body, raw-body limits, generic failures, safe logging, loopback binding. No PHP integration.
- **Dependencies / inputs:** WZP-INT-00; WP6A/WP6A.1 implementation and correction reports.
- **Deliverables / acceptance:** Authentication-before-body, bounded framing, generic errors, privacy-safe logging, and loopback cases have current isolated evidence; protocol limitations and independent review findings are recorded.
- **Verification:** Isolated ASGI security/boundary tests and independent review; framing limitations must be explicit.
- **Proposed owner / approval authority:** Python service/security owner / project owner.
- **Status / evidence:** **REPORTED COMPLETE** — Owner reports WP6A/WP6A.1 implementation complete; no fresh test or independent audit was run for this roadmap.
- **Risk / unresolved:** Real socket/proxy/network behavior is not established by ASGI tests alone.

### WZP-INT-02 — WP6B PHP client and destination hardening

- **Objective:** Keep PHP-to-Python transport opt-in, destination-bound, authenticated, bounded, and unavailable on failure.
- **Scope / exclusions:** Existing client, cURL transport, response validation, secret rotation documentation, isolated client tests. No routes/Lab/user-content integration.
- **Dependencies / inputs:** WZP-INT-01; current client source and independent WP6B.2 audit closure.
- **Deliverables / acceptance:** Independent WP6B.2 findings are individually closed with source/test evidence or an explicit blocker; no user content or live network behavior is claimed from fake transport tests.
- **Verification:** Isolated PHP regressions, transport boundary evidence, and reviewer-approved security audit; no real network test unless separately authorized.
- **Proposed owner / approval authority:** PHP/integration and security owners / project owner.
- **Status / evidence:** **AWAITING TECHNICAL VALIDATION** — WP6B.2 independent audit closure is not established by the inspected repository documentation.
- **Risk / unresolved:** Destination, TLS, DNS, real transport and error behavior require a verified boundary and approved test plan.

### WZP-INT-03 — WP6C Lab and consent integration

- **Objective:** Connect optional intelligence to the Lab only with explicit consent and safe user experience.
- **Scope / exclusions:** Lab consent UI, user-facing status, cancellation, privacy explanation, and service availability behavior. Excludes ordinary workflows and external AI providers unless separately approved.
- **Dependencies / inputs:** WZP-INT-02 technical validation; product/privacy-approved consent and data-flow design.
- **Deliverables / acceptance:** Approved consent and data-flow specification covers opt-in, denial, cancellation, unavailable, and deletion/retention states; synthetic-data integration tests show ordinary workflows continue.
- **Verification:** Consent/user-journey, privacy/security, and cross-client tests.
- **Proposed owner / approval authority:** Intelligence Lab product and privacy/security owners / project owner.
- **Status / evidence:** **NOT AUTHORIZED** — Prior project instructions require a separate approval for WP6C.
- **Risk / unresolved:** User content handling and consent scope remain undecided.

### WZP-INT-04 — WP6D cross-language integration verification

- **Objective:** Verify the approved PHP/Python request, authentication, response, failure, and compatibility contracts.
- **Scope / exclusions:** Isolated integration tests with synthetic data and approved disposable/local targets. Excludes production/staging and database access unless separately authorized.
- **Dependencies / inputs:** WZP-INT-02, WZP-INT-03; frozen request/response contract and approved isolated synthetic fixtures.
- **Deliverables / acceptance:** Synthetic isolated tests cover the frozen PHP/Python contract, rotation, validation, limits, timeout, retry, and unavailable behavior; fixture cleanup and no-production-target evidence are recorded.
- **Verification:** Controlled local fixtures, fixture cleanup proof, and independent review.
- **Proposed owner / approval authority:** QA/API and Python/PHP maintainers / project owner and security owner.
- **Status / evidence:** **NOT AUTHORIZED** — Prior project instructions require a separate approval for WP6D.
- **Risk / unresolved:** Contract drift or unsafe fixture setup can undermine evidence.

### WZP-INT-05 — WP7 classification development

- **Objective:** Develop a deterministic, evaluated semantic classifier only if separately approved.
- **Scope / exclusions:** Taxonomy, quality evaluation, confidence/abstention, provenance, multilingual limits, privacy, model/version governance. No model or provider is assumed.
- **Dependencies / inputs:** WZP-INT-04, WZP-PUB-03 stable content/knowledge interface; separately approved corpus, taxonomy, quality plan, and privacy decision.
- **Deliverables / acceptance:** Versioned corpus, taxonomy, metrics, and abstention criteria are pre-registered; evaluation reports the selected quality measures and errors. Thresholds require Intelligence Product and Security/Privacy approval; no production claim precedes that approval.
- **Verification:** Versioned offline evaluation and adversarial/privacy review; service tests.
- **Proposed owner / approval authority:** Intelligence product/engineering and privacy/security owners / project owner.
- **Status / evidence:** **NOT AUTHORIZED** — WP7 classifier development requires separate approval; the current service returns classification_unavailable.
- **Risk / unresolved:** No taxonomy/evaluation approval or model/provider decision exists.

### WZP-INT-06 — Recommendations and governed AI-assisted discovery

- **Objective:** Add explainable recommendations only when value, quality, consent, and governance are demonstrated.
- **Scope / exclusions:** Candidate ranking, relationship mapping, knowledge discovery, AI-assisted features. Excludes engagement optimization, opaque high-impact decisions, and production claims without evidence.
- **Dependencies / inputs:** WZP-PUB-03, WZP-API-01; WZP-INT-05 only when the approved recommendation consumes classifier output; separate product/privacy/quality approval.
- **Deliverables / acceptance:** Recommendation inputs, explanations, opt-out, and evaluation are documented; offline/user evaluation reports approved knowledge-outcome and safety measures; target owners approve thresholds and disable tests pass.
- **Verification:** Offline and user evaluation, regression, privacy/security review, monitoring and disable test.
- **Proposed owner / approval authority:** Product/intelligence and privacy/security owners / project owner.
- **Status / evidence:** **PLANNED** — Recommendations remain future capability; separate product, privacy, quality, and implementation approval is required.
- **Risk / unresolved:** Quality, bias, consent, taxonomy, and measurable value are unresolved.

## Corporate architecture and backend

### WZP-CORP-00 — WZ-CORP logical/physical design baseline

- **Objective:** Maintain the proposed organisation, IAM, governance, FK, migration-readiness, and validation design.
- **Scope / exclusions:** Documentation and decision closure only. No SQL, migrations, or runtime behavior.
- **Dependencies / inputs:** No predecessor; accepted WP1.5.2.1 documents and architecture authority.
- **Deliverables / acceptance:** Required design documents and cross-domain references are present; model consistency checks cover one-scope grants and ownership boundaries; output remains explicitly proposed, not SQL/runtime authority.
- **Verification:** Documentation link/FK/key consistency checks and owner review.
- **Proposed owner / approval authority:** Corporate architecture owner (not appointed) / project owner and domain authorities.
- **Status / evidence:** **REPORTED COMPLETE** — The project owner accepted the WP1.5.2.1 design baseline; the local documents are present. This is design completion only, not physical-schema or runtime approval.
- **Risk / unresolved:** Original CORP-01–23 register discrepancy and ownership decisions remain open.

### WZP-CORP-01 — Corporate decisions, schema reconciliation, and test readiness

- **Objective:** Resolve business/security ownership and prove a safe disposable MariaDB test target before implementation.
- **Scope / exclusions:** Decision recovery, exact migration/snapshot reconciliation, supported version proof, target allowlist and identity evidence. No production database use.
- **Dependencies / inputs:** WZP-CORP-00; appointed corporate domain owners and separately approved local test environment.
- **Deliverables / acceptance:** Original decision register discrepancy is resolved; source snapshot/migration differences are enumerated; version and disposable identity evidence are reproducible after approval; no live state is inferred.
- **Verification:** Static source diff plus isolated disposable-database verification only after separate authorization.
- **Proposed owner / approval authority:** Database/corporate/security owners / project owner and security approver.
- **Status / evidence:** **BLOCKED** — Corporate readiness records unresolved business/security decisions, source-to-migration differences, and unverified MariaDB behavior.
- **Risk / unresolved:** Database snapshot is historical; no live state or migration history was accessed.

### WZP-CORP-02 — WZ-ORG organisation registry

- **Objective:** Establish an approved effective-dated organisation and assignment projection.
- **Scope / exclusions:** Legal entity/jurisdiction references, units, positions, workforce references, assignments, reporting, regions, locations, cost-centre references. No assumed entities or HRMS.
- **Dependencies / inputs:** WZP-CORP-01; approved workforce, legal, finance, and organisation authorities.
- **Deliverables / acceptance:** Approved ORG vocabulary and lifecycle rules pass interval, hierarchy-cycle, assignment, and concurrency tests in an authorized disposable environment; each external source field has an owner.
- **Verification:** Isolated constraints, concurrency, lifecycle, and non-overlap tests.
- **Proposed owner / approval authority:** Organisation/workforce/finance owners / project owner and legal/security as applicable.
- **Status / evidence:** **AWAITING OWNER DECISION** — Workforce, assignment, legal-entity, and cost-centre authority decisions are not established.
- **Risk / unresolved:** Workforce and assignment source authorities are not identified.

### WZP-CORP-03 — WZ-IAM corporate identity and scoped authorization

- **Objective:** Provide separate corporate principals, issuer/subject identity resolution, default-deny RBAC, and one-scope grants.
- **Scope / exclusions:** Corporate human/service identities, role/permission catalogue, scoped grants, revocation, access reviews. No public-user merge, email matching, or universal admin.
- **Dependencies / inputs:** WZP-CORP-01, WZP-CORP-02; approved IdP, federation, MFA, and identity-lifecycle policy.
- **Deliverables / acceptance:** Issuer/subject uniqueness, public/corporate separation, default-deny, exactly-one-scope grants, expiry, revocation, and active-session cases pass isolated negative tests.
- **Verification:** Negative authorization, issuer/subject, scope, expiry, revocation, session and separation-of-duties tests.
- **Proposed owner / approval authority:** IAM/security owner / project owner and security authority.
- **Status / evidence:** **AWAITING OWNER DECISION** — Identity-provider, federation, MFA, workforce-link, and privileged-access policies are not approved.
- **Risk / unresolved:** IdP, MFA, identity proofing, role owners and workforce lifecycle are undecided.

### WZP-CORP-04 — WZ-GOV approvals, delegation, and audit evidence

- **Objective:** Establish policy-controlled approvals, delegated authority, SoD, review, exception, emergency access, and append-only evidence.
- **Scope / exclusions:** GOV domain only; approval does not itself create IAM access. No invented authority limits or retention periods.
- **Dependencies / inputs:** WZP-CORP-02, WZP-CORP-03; approved governance, legal/privacy, security, retention, and evidence-custody decisions.
- **Deliverables / acceptance:** Workflow transitions, non-self-approval, separation-of-duties, delegation expiry, idempotency, audit append-only behavior, and evidence verification pass approved isolated tests.
- **Verification:** Lifecycle, SoD, concurrency, tamper-evidence, restore, and audit review tests.
- **Proposed owner / approval authority:** Governance/audit/security owners / project owner and qualified legal/privacy reviewers.
- **Status / evidence:** **AWAITING OWNER DECISION** — Governance, approval, delegation, audit-retention, and evidence-custody policies are not approved.
- **Risk / unresolved:** Retention, external signer/archive, authority limits, and approval policy are not approved.

### WZP-CORP-05 — Corporate administrative capabilities

- **Objective:** Deliver approved internal administration and executive visibility on top of ORG/IAM/GOV foundations.
- **Scope / exclusions:** Admin shell, organisation/access management, approvals, support/moderation integrations, permitted analytics. No authority inferred from title or reporting line.
- **Dependencies / inputs:** WZP-CORP-02, WZP-CORP-03, WZP-CORP-04, WZP-API-01; approved administrative scope and operating model.
- **Deliverables / acceptance:** Every included admin journey has a role/scope matrix, negative-access results, audit evidence, and owner sign-off; analytics fields and retention are approved.
- **Verification:** Role/scope denial tests, audit review, support and incident exercises.
- **Proposed owner / approval authority:** Corporate operations and security owners / project owner.
- **Status / evidence:** **PLANNED** — Administrative capabilities are later scope and have no approved corporate operating model.
- **Risk / unresolved:** No corporate operating model or administrators have been approved.

## Mobile, operations, and release

### WZP-MOB-01 — Mobile technology and product assessment

- **Objective:** Recommend a sustainable approach and approved mobile scope.
- **Scope / exclusions:** Compare native, Flutter, React Native, and alternatives; define device, accessibility, offline, security, support, and store requirements. No implementation.
- **Dependencies / inputs:** WZP-01; approved mobile product scope, assessment criteria, and appointed mobile owner. May run in parallel with WZP-API-01.
- **Deliverables / acceptance:** Approved criteria are published before scoring; comparative ADR records evidence for each platform, skills, lifecycle, accessibility, security, offline, cost, support, and exit impact; project owner records the selection decision.
- **Verification:** Engineering/product/security review and documented owner decision.
- **Proposed owner / approval authority:** Mobile lead (not appointed) / project owner.
- **Status / evidence:** **AWAITING OWNER DECISION** — Mobile scope, assessment criteria, and accountable mobile owner are not appointed.
- **Risk / unresolved:** Team skills and operations capacity are unknown.

### WZP-MOB-02 — Android/iOS application capabilities

- **Objective:** Deliver approved mobile user journeys using stable backend contracts.
- **Scope / exclusions:** Authentication, reading, publishing, comments, communities, notifications, profiles, discovery, accessibility, privacy, offline/error handling. Excludes a private Python service connection.
- **Dependencies / inputs:** WZP-MOB-01, WZP-API-02, and only the public feature packages selected for mobile journeys; WZP-TRUST-01 is additionally required when a selected journey includes discussions or communities.
- **Deliverables / acceptance:** Approved mobile journeys pass contract, secure-storage, offline/error, accessibility, localization, and user-journey tests on every device/OS in the owner-approved matrix.
- **Verification:** Contract, device, secure storage, offline, accessibility, localization and user-journey testing.
- **Proposed owner / approval authority:** Mobile lead and product owner / project owner and security/privacy reviewers.
- **Status / evidence:** **BLOCKED** — Requires WZP-MOB-01 approval, stable WZP-API-02 contracts, and the public feature packages selected for mobile.
- **Risk / unresolved:** Large schedule risk if API and mobile decisions slip.

### WZP-MOB-03 — Mobile security, device matrix, and store readiness

- **Objective:** Verify mobile security/device coverage and prepare store materials after feature completion.
- **Scope / exclusions:** Device/OS coverage, accessibility, privacy disclosures, signing, store content, staged rollout, support and rollback.
- **Dependencies / inputs:** WZP-MOB-02; approved platform, device/OS, security, signing, and store-readiness criteria. No WZP-OPS-01 or WZP-AUD-01 prerequisite.
- **Deliverables / acceptance:** All devices/OS in the approved release matrix have recorded security/accessibility results; signing custody and store materials are reviewed; no store submission occurs without separate release approval.
- **Verification:** Independent mobile security/accessibility review and release rehearsal.
- **Proposed owner / approval authority:** Mobile/operations/security owners / project owner.
- **Status / evidence:** **PLANNED** — No platform, device matrix, signing owner, or store scope is approved.
- **Risk / unresolved:** Store accounts, signing ownership, supported devices and dates are unknown.

### WZP-OPS-01 — Infrastructure and operational readiness

- **Objective:** Make the approved beta scope supportable and recoverable.
- **Scope / exclusions:** Environment separation, deployment, monitoring, incident response, capacity, backup/restore, disaster recovery, access and secrets operations. No live infrastructure changes in this roadmap.
- **Dependencies / inputs:** WZP-01, WZP-02; the explicitly selected package inventory for the applicable beta or production scope, separately authorized environment access, and an appointed operations owner.
- **Deliverables / acceptance:** Each target environment is separately identified and verified; monitoring, incident, backup/restore, capacity, support, and rollback evidence is recorded. Recovery/performance thresholds are approved by Operations before pass.
- **Verification:** Readiness review and restore/deploy/incident exercises in an approved non-production target.
- **Proposed owner / approval authority:** Operations/security owner (not appointed) / project owner.
- **Status / evidence:** **AWAITING OWNER DECISION** — Operations owner, release inventory, environment evidence, support model, and recovery targets are not established.
- **Risk / unresolved:** Local, GitHub, Hostinger, staging, and production states may differ.

### WZP-AUD-01 — Comprehensive pre-beta audit

- **Objective:** Independently assess the full frozen beta scope before beta entry.
- **Scope / exclusions:** All Gate 2 domains in the audit framework; no out-of-scope component is silently presumed cleared.
- **Dependencies / inputs:** WZP-03, WZP-OPS-01, and every package and safety-critical dependency included in the frozen beta inventory.
- **Deliverables / acceptance:** Frozen beta inventory exactly matches the audited asset/dependency list; each included item has requirement and reproducible evidence; findings have severity, owner, deadline, and disposition.
- **Verification:** Independent cross-functional audit with reproducible test and environment evidence.
- **Proposed owner / approval authority:** QA/security/privacy and operations reviewers / project owner.
- **Status / evidence:** **PLANNED** — Cannot begin until the beta inventory is frozen and all included components and operations evidence are ready.
- **Risk / unresolved:** Audit duration is unknown until beta scope and team availability are set.

### WZP-BETA-01 — Controlled public beta

- **Objective:** Validate the approved product with a controlled cohort and safe support model.
- **Scope / exclusions:** Only the explicitly approved beta features, regions, users, and period. No broad public launch.
- **Dependencies / inputs:** WZP-AUD-01 pass, WZP-OPS-01 readiness, frozen beta inventory, privacy/support decisions, and explicit project-owner approval.
- **Deliverables / acceptance:** Only the approved cohort, regions, period, and feature inventory are enabled; issue/incident and agreed product measures are reviewed; entry, pause, and exit decisions are recorded.
- **Verification:** Monitor agreed product/quality measures and run planned user journeys; maintain pause and rollback readiness.
- **Proposed owner / approval authority:** Beta product/operations owners / project owner.
- **Status / evidence:** **NOT AUTHORIZED** — A controlled beta needs separate explicit project-owner approval after Gate 2.
- **Risk / unresolved:** Cohort, duration, communications, data use, support capacity and thresholds are not approved.

### WZP-AUD-02 — Final production audit and launch decision

- **Objective:** Confirm beta findings are closed and the exact production release is safe and supportable.
- **Scope / exclusions:** Gate 3 review of fixes, regression, operations, security/privacy, backup/recovery, rollback, release identity, and approved launch scope.
- **Dependencies / inputs:** WZP-BETA-01 exit, WZP-03, WZP-OPS-01, every package in the initial production inventory, beta-finding dispositions, and exact release identity.
- **Deliverables / acceptance:** Production inventory, audited commit, release-gate result, beta findings, recovery evidence, and residual exceptions are reconciled; project owner makes a separate written launch decision.
- **Verification:** Independent audit and successful release-gate rehearsal tied to the exact Git commit.
- **Proposed owner / approval authority:** QA/security/privacy/operations leads / project owner.
- **Status / evidence:** **NOT AUTHORIZED** — A production audit/launch decision is a later separately approved release activity.
- **Risk / unresolved:** Beta findings and production conditions are unknown.
