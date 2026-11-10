# WZ-MASTER-ROADMAP-2027.2

**Status:** Governing programme planning baseline; dependency and release-governance corrections applied; dates and release scope remain provisional
**Planning window:** November 2026–December 2027
**Implementation authority:** None granted by this document
**Release authority:** GitHub, under the repository's release governance

This is a controlled correction of WZ-MASTER-ROADMAP-2027.1. The existing 32 work-package IDs are retained. The status vocabulary in the work-package register is authoritative; timeline confidence and capability maturity are separate concepts.

## Purpose and authority

This is the canonical delivery-programme index for WriteZone. It translates the project owner's requested release sequence into workstreams, work packages, dependencies, a provisional schedule, acceptance criteria, audit gates, and beta/launch governance. It does not approve implementation scope, public beta, production release, or deployment.

Follow the evidence rules in AGENTS.md and docs/ARCHITECTURE_AUTHORITY.md. A roadmap label, prior report, filename, checkmark, test count, or design document is not by itself proof of implementation. Separate source evidence, test evidence, deployment verification, and owner-reported status.

## Governing release sequence

1. Close product and architecture planning for the approved release scope.
2. Develop the approved public website scope.
3. Develop approved corporate backend capabilities in parallel only where owners and dependencies permit.
4. Begin Android and iOS implementation only after the core backend and shared API contracts are stable. Technology assessment may happen earlier.
5. Complete the comprehensive pre-beta audit for the declared beta scope.
6. Run a controlled public beta only after audit clearance and explicit project-owner approval.
7. Close beta findings and complete the final production audit.
8. Launch the website and mobile applications only after the final audit passes and the project owner explicitly approves release.

No beta, launch, production access, or deployment is authorized by this programme baseline.

## Three distinct release scopes

| Scope | Definition | Completion and audit rule |
|---|---|---|
| **A. Complete programme scope** | The complete set of approved website, mobile, corporate, intelligence, infrastructure, and operational capabilities required by the master product direction. It is the long-range programme objective, not a beta inventory. | Programme completion requires acceptance evidence for every capability retained in the approved baseline, with approved exclusions recorded through change control. Programme completion alone does not authorize a release. |
| **B. Public beta scope** | The Project Owner has approved the website-first, content-first candidate in principle. The candidate inventory is documented locally, but the final named, versioned beta inventory is not frozen; release geography, users, and operating boundaries remain subject to their Gate 2 decisions. | Gate 2 covers every included component and its safety-critical dependencies. Excluded capabilities are not beta dependencies and are not thereby audited or accepted. Beta requires a separate written project-owner approval. |
| **C. Initial production release scope** | A separately named, versioned inventory approved for the first full public release. It may be a subset of the complete programme and may differ from beta. It is currently unselected. | Gate 3 covers the exact production inventory, its dependencies, beta findings, and operational controls. Launch requires a separate written project-owner approval. |

Programme completion, beta readiness, and production readiness are separate decisions. Mobile or corporate packages become release dependencies only when included in that release inventory or when required to operate its included scope safely. A selected component cannot be omitted from the corresponding audit.

## Workstreams and document set

| Deliverable | Purpose |
|---|---|
| [Workstream architecture](01-WORKSTREAM-ARCHITECTURE.md) | Boundaries, outcomes, owners, and parallel work. |
| [Work-package register](02-WORK-PACKAGE-REGISTER.md) | Unique packages, scope, acceptance, verification, owners, status, and risks. |
| [Dependencies and sequencing](03-DEPENDENCIES-AND-SEQUENCING.md) | Package prerequisites, critical path, and safe parallelism. |
| [Provisional schedule](04-PROVISIONAL-SCHEDULE.md) | Monthly target windows, decision deadlines, and confidence limits. |
| [Product acceptance framework](05-PRODUCT-ACCEPTANCE.md) | Product outcomes and cross-cutting acceptance criteria. |
| [Mobile application plan](06-MOBILE-APPLICATION-PLAN.md) | Technology evaluation, API dependencies, development, and store readiness. |
| [Audit and release gates](07-AUDIT-AND-RELEASE-GATES.md) | Continuous assurance, pre-beta audit, and final production audit. |
| [Beta and launch governance](08-BETA-AND-LAUNCH-GOVERNANCE.md) | Entry, conduct, exit, incident, rollback, and approval rules. |
| [Risks, decisions, and change control](09-RISKS-DECISIONS-AND-CHANGE-CONTROL.md) | RAID register, unresolved authorities, and baseline-change process. |

## Baseline status and evidence limits

- The current architecture handbook classifies feed and search as partially implemented, recommendation as planned, events as scaffolded, and authentication as implemented with important security gaps. Use its R0.6 reconciliation as the current architecture-status source; do not infer broader completion from older roadmap checkmarks.
- Corporate documents under docs/Corporate are a proposed design baseline, not an approved physical schema or runtime implementation. The corporate readiness document records unresolved business, legal, security, database, and operational decisions.
- Work-package status is controlled by the exact values defined in the register. Owner-reported completion is not evidence-verified completion. The architecture maturity labels in docs/ARCHITECTURE_AUTHORITY.md remain separate.
- The Python service foundation and PHP client have source and isolated tests in the repository. Owner reports state WP5, WP5.1, and WP5.2 were accepted; the roadmap did not rerun tests. WP6A/WP6A.1 completion is reported. WP6B.2 remains awaiting technical validation because its independent audit closure is not established in the inspected repository documentation. The Python classification endpoint returns unavailable; WP7 remains not authorized.
- The PHP intelligence client documentation says the client is opt-in and not registered with routes or ordinary workflows. No PHP/Python user-content integration is implied by this baseline.
- The root docs/ROADMAP.md is an empty placeholder. The Book-VI product roadmap and board CTO roadmap are strategic narratives and now carry notices pointing here; history milestones remain historical evidence only. None of these establishes current implementation status.
- The existing M0–M15 files in docs/Roadmap were empty placeholders when inspected. They remain untouched and are not completion records.
- Hostinger, production, staging, live databases, GitHub remote state, staffing, and operational capacity were not verified. Do not treat them as known or identical to local state.

## Planning assumptions and next action

November 2026–December 2027 is a provisional target window requested by the project owner, not a forecast. No staffing, budget, feature scope, beta population, target countries, or delivery capacity has been confirmed. The first programme decision package is WZP-01; it includes owner appointment and approved-scope closure. Implementation packages remain individually gated and require explicit scope approval.

For a current, risk-adjusted estimate that incorporates WZP-02.10 environment evidence and WZP-02.12 approval/appointment gates, see the [WriteZone Implementation Delivery Schedule 2027](../Engineering/WZ-IMPLEMENTATION-DELIVERY-SCHEDULE-2027.md). It supplements the provisional programme schedule and does not change approved scope, package status, or implementation authority.

## WZ-MASTER-ROADMAP-2027.2 correction record

| Finding | Governing resolution |
|---|---|
| Trust/reporting split | Under Project Owner decision 2026-11-09 and CR-WZP-2026-11-09-01, WZP-TRUST-01 includes content-only public Writ reporting independent of WZP-PUB-02; interaction moderation remains conditional on PUB-02. No package ID or package status changes. |
| Knowledge/AI ambiguity | WZP-PUB-03 is a stable, non-AI content/search/provenance foundation; WZP-INT-05 and WZP-INT-06 may consume its contracts. |
| Release coupling | Scope A is the complete programme; B and C are separately frozen inventories. Mobile, corporate, and intelligence work gates only an inventory that includes it or needs it for safe operation. |
| Status and ownership | Document 02 defines one package status vocabulary, evidence transitions, role assignments, and owner-appointment prerequisite. |
| Conditional audit clearance | Document 07 limits exceptions to approved non-blocking Medium/Low findings; expiry, ownership, mitigation, retest, and separate release approval are mandatory. |
| Acceptance quality | Document 05 records the approved qualitative baseline B rubric and maps evidence/methods; numeric targets remain optional and require separate approval before use. |
| Dependency integrity | Document 03 is the single 32-package dependency register and graph; the complete union of conditional edges must be acyclic. |
