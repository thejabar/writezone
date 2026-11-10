# Product Completion and Acceptance Framework

**Authority:** WZ-MASTER-ROADMAP-2027.2
**Product direction:** Global Knowledge Network and Knowledge Hub
**Governing product and architecture sources:** AGENTS.md, project Constitution, WriteZone Test, and current architecture authority
**Status:** WZP01-D04 qualitative task-based approach approved 2026-11-09 for baseline B; detailed package evidence remains future work and numeric targets are optional, not required initially

## Product acceptance principle

WriteZone is designed to help people create, connect, discover, evaluate, and preserve knowledge. Acceptance should measure whether a capability makes the user's time more valuable, supports useful discovery, earns trust, and works accessibly across supported devices and languages. Do not optimize for superficial engagement, time-on-site, or endless scrolling as standalone outcomes.

## Cross-cutting acceptance criteria

Every beta-scope capability must satisfy the applicable criteria below before it is accepted:

1. **Purpose and scope:** A reviewed requirement names the user problem, intended knowledge outcome, in-scope roles, exclusions, and owner. Unapproved roadmap ideas remain planned.
2. **User journey:** The complete desktop and mobile-responsive path has been verified, including empty, invalid, denied, failure, recovery, and success states.
3. **Content and discovery:** Reading and creation remain clear and content-first. Search, feeds, notifications, recommendations, and recognition must explain their purpose and avoid manipulative engagement patterns.
4. **Identity and authorization:** Public WriteZone identity remains separate from corporate identity. Access is checked server-side for each protected resource and defaults to deny when authority is absent.
5. **Privacy and safety:** Minimize collected data; document consent and retention where relevant; provide reporting, moderation, appeals, and user safety controls for the features that require them. Legal and privacy obligations must be determined by qualified owners.
6. **Accessibility and global support:** Verify responsive layouts, keyboard and assistive-technology use, readable content, language selection and fallback, localization behavior, and right-to-left needs where included. Supported languages and conformance targets require approval.
7. **Reliability and security:** Validate input, error behavior, session and authorization boundaries, sensitive logging, rate/abuse controls, availability behavior, and recovery paths appropriate to the feature.
8. **Evidence:** Store the requirement, implementation reference, review, relevant regression/security/accessibility/performance evidence, and unresolved risks. Documentation alone does not prove implementation.
9. **Operations:** Define support ownership, monitoring, incident escalation, backup/recovery impact, and rollback or forward-repair approach for the accepted scope.

## Acceptance evidence and target approval

Every work package must turn qualitative language into an observable artifact, test case, or reviewed rubric. Terms such as useful, constructive, fair, stable, safe, reliable, and accessible are not pass criteria by themselves. The Project Owner approved a qualitative task-based baseline B rubric under WZP01-D04; it covers account/profile tasks, Writ creation/editing/reading, basic discovery usefulness, reporting access/response, feedback/error clarity, accessibility/responsive usability, and safe unsuccessful operations. Numeric targets are not required for initial B acceptance. Any optional later target must identify its measure, evidence source, verification method and approving owner before use.

| Package family | Measure to define (no threshold is approved here) | Evidence source | Verification method | Role that must approve the target |
|---|---|---|---|---|
| WZP-00, WZP-01, WZP-02, WZP-03 | Document/decision coverage, source reconciliation coverage, and required check completion. | Versioned decision/requirement register, source references, check outputs, review records. | Compare required inventory with recorded evidence; verify every unresolved item has an owner and disposition. | Project owner for programme scope; Engineering/QA for check criteria. |
| WZP-PUB-01, WZP-PUB-02 | Baseline B account/profile/Writ journeys, recoverable failures, and responsive/accessibility outcomes; interaction safety only if selected. | Isolated journey tests, role/state fixtures, support/report records and reviewed accessibility evidence. | Test each approved outcome. B excludes durable drafts/save-resume and permanent Writ deletion; do not infer social interaction acceptance from their existing routes. | Project Owner approved WZP01-D02 boundaries; product/engineering owners for implementation; Trust/Safety and Security for any interaction scope. |
| WZP-PUB-03, WZP-INT-05, WZP-INT-06 | Search relevance, classification quality/calibration/abstention, and recommendation usefulness. | Versioned query sets, labeled evaluation corpus, provenance fixtures, and consented evaluation notes. | Pre-register metric definitions and test set; report distributions and errors; use blinded or otherwise controlled human review where appropriate. | Search/Product and Intelligence owners; Security/Privacy approval for data use. |
| WZP-INT-00, WZP-INT-01, WZP-INT-02, WZP-INT-03, WZP-INT-04 | Authentication/boundary rejection, payload and response limits, contract conformance, failure behavior, and integration coverage. | Current isolated Python/PHP test outputs, independent audit findings, synthetic fixtures, and approved test-environment records. | Run only the approved isolated suite; map each security/contract case to a result; distinguish test harness evidence from live network behavior. | Intelligence engineering and independent Security/QA reviewers; project owner for scope expansion. |
| WZP-PUB-04, WZP-MOB-01, WZP-MOB-02, WZP-MOB-03 | Locale/device matrix coverage, accessibility findings, supported-platform behavior, and crash/error outcomes. | Approved locale/device matrix, accessibility review, automated/manual test results, and privacy-reviewed diagnostics. | Test every approved matrix row and journey; record unresolved findings and rerun evidence. | Product, Accessibility, and Mobile owners; project owner approves platform/scope. |
| WZP-TRUST-01, WZP-TRUST-02 | Content-only public Writ report intake/triage/response coverage for baseline B; interaction reporting/appeals when those features are separately selected; permission denials and audit evidence. | Synthetic report cases, restricted-access checks, audit samples, support/response records and approved policy rubric. | Exercise intake, categorization, urgent escalation, authorized action, outcome/evidence, reporter communication and support handoff; independently review access and audit behavior. Operational evidence is required before beta. | Project Owner approved WZP01-D03 scope; appointed Trust/Safety, Security/Privacy and Operations owners for their domains. |
| WZP-API-01, WZP-API-02 | Contract conformance, compatibility, error behavior, and performance/resource measures. | Versioned contract, consumer fixtures, isolated test and load outputs. | Run positive/negative contract tests and approved representative load profile; compare only with owner-approved bounds. | API/Engineering owner; Operations and Security approve their respective bounds. |
| WZP-CORP-00, WZP-CORP-01, WZP-CORP-02, WZP-CORP-03, WZP-CORP-04, WZP-CORP-05 | Identity/scope decision coverage, constraint integrity, migration compatibility, revocation and audit outcomes. | Approved design decisions and isolated disposable-database/authorization test evidence. | Verify each model invariant and migration path on the independently approved target; no production inference. | Corporate domain, Database, Security, and qualified Legal/Privacy owners. |
| WZP-OPS-01 | Restore outcome, alert/incident handling, capacity, and recovery measures. | Environment inventory, rehearsal logs, monitoring samples, backup/restore evidence. | Exercise each approved recovery and incident scenario in an authorized non-production target. | Operations and Security owners; project owner approves support scope. |
| WZP-AUD-01, WZP-BETA-01, WZP-AUD-02 | Inventory/evidence completeness, beta cohort/duration, issue trends, stop conditions, and release readiness. | Frozen B/C inventories, audit/finding register, approved telemetry, support and incident records. | Reconcile every selected component with test/audit evidence; review cohort and outcome measures against pre-approved targets. | Project owner, Product, QA/Security/Privacy, and Operations owners according to decision. |

No numeric performance, relevance, quality, accessibility, service-level, cohort, retention, support-capacity, RTO, or RPO target was approved by the 2026-11-09 decision. Numeric targets are not required for initial B acceptance because the qualitative rubric is approved. Behavioural analytics and engagement-driven tracking are excluded from B. Optional telemetry remains disabled unless a separate purpose, privacy assessment, access policy and retention basis are approved.

## Required knowledge journeys

| Journey | Acceptance outcome | Verification evidence |
|---|---|---|
| Create and publish | An author can compose and validate a text Writ, publish it, and edit it with clear visibility; recoverable failures preserve submitted content where technically safe, and retries do not unintentionally duplicate Writs. Durable drafts/save-resume and permanent deletion are excluded from B. | Positive/negative journey tests, server-side validation and ownership regressions, duplicate-safe retry evidence, accessibility/responsive review. |
| Read and discover | A reader can read public Writs and use basic non-AI keyword discovery with defined empty, unavailable, invalid and error states. No semantic/AI or Knowledge Graph claim is implied. | Representative query and reading journeys, empty/error states, qualitative discovery usefulness review and accessibility checks. |
| Discuss and learn | Comments, Q&A and community interaction are excluded from baseline B. | If separately included later, require role/abuse cases, moderation/reporting path, notification behavior and community-owner review. |
| Report and resolve harm | A user can report a public Writ through the approved content-only path; restricted operators can triage, escalate, take authorized action, record the outcome and evidence, communicate appropriately and hand off to support. | WZP-TRUST-01 synthetic report cases, restricted-access/permission tests, audit evidence and operational rehearsal. Required before beta; a proposal or unstaffed queue is insufficient. |
| Use across languages and devices | Supported users can read and navigate correctly on agreed devices and in approved locales. | Device/browser matrix, language fixtures, localization and accessibility review. |
| Corporate administration | If included in beta scope, corporate operators use distinct corporate identity, approved roles/scopes, and auditable workflows. | WZ-ORG/IAM/GOV approval, isolation and negative authorization tests, audit review. |
| Intelligence assistance | If separately approved, the feature is opt-in, bounded, explainable, consent-aware, and fails safely. | Approved model/service contract, privacy/security review, quality evaluation, and explicit integration tests. The current classifier is unavailable. |

## Metrics and acceptance targets

For initial baseline B, use the Project Owner-approved qualitative task rubric; numeric targets are optional and not a WZP-01 closure prerequisite. Behavioural analytics and engagement tracking are excluded. Optional telemetry is disabled unless a later decision documents its purpose, privacy assessment, access policy, minimization and retention basis. Do not treat time-on-site, interaction counts or content volume alone as knowledge value.

## Completion status model

Capability maturity uses the status model in docs/ARCHITECTURE_AUTHORITY.md: PLANNED, DOCUMENTED, SCAFFOLDED, PARTIALLY IMPLEMENTED, IMPLEMENTED, TESTED, PRODUCTION-VERIFIED. Work-package progress uses only the controlled vocabulary in 02. Keep the two vocabularies distinct. A passed local regression is not production verification; a historical roadmap checkmark is not a status.

## Acceptance authority

The product owner accepts product outcomes and user journeys. Engineering accepts architecture and maintainability. Security, privacy/legal, accessibility, Trust and Safety, and Operations owners accept their control domains. The project owner accepts beta scope and launch. No single approval substitutes for another required domain approval.
