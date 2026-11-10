# Provisional Schedule and Milestones

**Authority:** WZ-MASTER-ROADMAP-2027.2
**Planning target:** November 2026–December 2027
**Confidence:** Low until scope, capacity, owners, dependencies, and target environments are confirmed
**Status:** Planning target only; not a delivery commitment

Work-package states use the controlled vocabulary in 02 and do not change because a target date arrives. This schedule does not authorize implementation. A, complete programme scope, may extend beyond this window. B, public beta, and C, initial production, have separate frozen inventories; the schedule below is only a provisional path for whichever inventory the project owner approves.

## Schedule baseline

| Window | Target milestone | Dependencies and exit evidence | Confidence |
|---|---|---|---|
| Nov–Dec 2026 | Record the 2026-11-09 owner dispositions, complete WZP-01 closure evidence, version the A/B/C scope inventories, preserve explicit appointment/resolution blockers, and prepare the separately gated WZP-02 source/test baseline. | Decision and owner records; CR-WZP-2026-11-09-01 package/dependency reconciliation; versioned inventories consistent with D01; WZP-02 evidence plan. WZP-00 is documentation-only and no runtime work is authorized. Exact beta operating values are due before Gate 2/WZP-BETA-01, not WZP-01 closure. | Low; scope inventories, blockers, owners and capacity remain open. |
| Jan–Mar 2027 | Stabilize the selected baseline; define versioned public API contracts only for consumers in an approved scope; progress technical and corporate readiness decisions only for separately selected work. | Relevant PHP syntax/security/regression evidence; reviewed API contract when required by an approved consumer; source/snapshot/migration differences recorded; isolated database checks only after separate approval. | Low; reported defects and schema readiness require current evidence. |
| Apr–Jun 2027 | Build approved website capabilities and endpoint slices; continue separately approved Trust/Safety and corporate work after prerequisites. Complete mobile platform assessment in parallel. | Accepted stories and journeys; versioned contracts; module-level regression evidence; moderation policy foundation; mobile ADR and owner decision. | Low; feature scope, owners, resourcing and corporate authorities are unknown. |
| Jul–Sep 2027 | Stabilize selected backend/API capabilities; start mobile implementation only after WZP-API-02 stability and platform approval; continue approved discovery, Trust/Safety, and corporate packages. | Contract compatibility tests; approved platform; selected-feature tests; device/security evidence where mobile is in a later release. | Low; mobile scope and dependencies can extend the path. |
| Oct 2027 | Freeze inventory B only if beta is targeted; complete its component, operations, security/privacy/accessibility and recovery evidence; conduct Gate 2. | B inventory matches every audited asset and dependency; no release-blocking finding; conditional findings governed under 07. C-only components are excluded from Gate 2 unless selected in B. | Low; scope and audit duration are unknown. |
| Nov 2027 | Earliest provisional controlled-beta window for B. | Gate 2 pass or eligible conditional pass plus separate written project-owner authorization; B-specific operations readiness. If a condition or prerequisite slips, beta moves. | Not a commitment; no beta is authorized now. |
| Dec 2027 | Possible beta-finding review and Gate 3 for frozen C; launch decision only if C is fully ready. | C inventory audit includes components not in B; beta findings are retested; operations evidence and exact release identity exist; separate owner launch approval. Otherwise Gate 3/launch moves beyond this window. | Not a commitment; beta duration, C scope and defect volume are unknown. |

## Decision deadlines on the target path

- By December 2026: complete WZP-01 closure evidence and version the A/B/C inventories; map the approved baseline B journeys and qualitative rubric. Record explicit unassigned-role blockers. The Project Owner approved deferring exact beta geography, eligibility, cohort, duration, notices and support limits until before Gate 2/WZP-BETA-01. Responsive mobile-browser support is part of B; native applications remain excluded unless a separate scope decision changes that direction.
- Before activating any package: record the proposed owner role, appointed accountable owner, technical delivery assignment, independent verifier, approval authority, and required package approval. WZP-01 must close the appointments for the first selected release scope.
- Before each backend/API package: approve contract consumers, authentication boundaries, versioning, error behavior, and compatibility policy.
- Before corporate implementation: appoint domain authorities and approve the corporate policy, supported database version, baseline reconciliation, and isolated test evidence requirements.
- Before mobile implementation: approve a documented platform evaluation and the stable backend/API contract.
- Before Gate 2: decide and assign the deferred beta geography, eligibility, cohort, duration, notices and support limits; freeze the beta scope and feature list so the audit can establish a complete inventory.
- Before Gate 3: freeze C separately; if mobile or corporate capabilities are selected for C but were absent from B, complete their full package and audit evidence before Gate 3.
- Before beta and launch: project-owner approval is required separately at each gate.

## Critical path and parallel work

The logical path is WZP-01 remaining-condition/owner closure → WZP-02 stabilization → selected website/domain packages and any API contracts required by their approved consumers → selected safety-critical integrations → the matching B or C operations-readiness output → the corresponding audit → beta (B only) → findings closure → Gate 3 for C → separate owner launch approval. Actual duration-based critical path cannot be estimated without a frozen inventory, owners, capacity, and package estimates.

Corporate implementation may proceed in parallel only after its own business, security, database, and migration gates. It does not block beta or production when excluded from that inventory. Mobile technology assessment can run during backend/API planning; mobile implementation waits for stable contracts. Mobile security/store readiness is a Gate 2 or Gate 3 dependency only when mobile is in B or C. Intelligence capabilities are not assumed in either scope; WP7 remains not authorized and classification is unavailable.

## Schedule realism and replanning rule

The November 2026–December 2027 window is a requested target, not an earned forecast. The repository does not establish staffing, team throughput, approved release scope, mobile platform, target beta size, support coverage, or external legal/privacy decisions. No numeric estimates are credible until owners size the approved packages. Replan when a decision deadline, dependency, audit blocker, or capacity assumption is missed; record the impact and obtain owner approval before changing scope or dates.
