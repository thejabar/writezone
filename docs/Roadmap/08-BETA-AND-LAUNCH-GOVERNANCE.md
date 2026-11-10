# Beta and Launch Governance

**Authority:** WZ-MASTER-ROADMAP-2027.2
**Status:** Proposed governance; no beta, store submission, or production launch is authorized

## Programme, beta, and production scope

- **Complete programme scope (A):** all approved product and operational capabilities retained in the long-range baseline. It is not a release inventory.
- **Public beta scope (B):** a versioned, frozen inventory of only the components, journeys, data, regions, languages, and users approved for controlled beta.
- **Initial production scope (C):** a separate versioned inventory approved for the first full public release. C may differ from B and may include capabilities not exposed in beta.

Programme completion is not a condition for beta unless A is explicitly selected as B. Gate 2 audits all of B and its safety-critical dependencies; an excluded component is not a beta dependency or cleared by exclusion. Gate 3 audits all of C, including C-only components, and beta findings. Mobile, corporate, and intelligence capabilities are dependencies only when selected in B/C or required to operate an included capability safely.

## Beta entry requirements

The Project Owner must freeze B, including objectives, user cohort, supported regions/languages, feature list, known limitations, privacy notices, support model, data handling, success measures, and beta period. The 2026-11-09 decision approves the B candidate journeys and qualitative task-based acceptance approach; it does not freeze B. Exact beta geography, eligibility, cohort size, duration, notices, and support limits may remain open through WZP-01 closure but must be decided and assigned before Gate 2/WZP-BETA-01. Before inviting users:

1. Gate 2 has passed for the exact B inventory and release candidate. A conditional pass is not authorization; separate written project-owner approval is still required.
2. The release corresponds to a known Git commit and all applicable release checks pass.
3. No unresolved critical/high security, privacy, safety, data-integrity, or authorization issue remains.
4. For any B that includes public Writ publishing, the WZP-TRUST-01 content-only report/response process is staffed, operational and rehearsed, whether or not WZP-PUB-02 is included. If public interactions are included, their WZP-PUB-02 reporting and moderation path is also complete. Support escalation, incident response, monitoring, backup/restore, and rollback/disable controls have been rehearsed.
5. Terms, notices, consent, telemetry, retention, deletion, and research/feedback collection are reviewed by the responsible privacy/legal owners.
6. Beta users receive clear status, limitations, feedback channels, known-issue handling, and data-use information.
7. Optional intelligence remains disabled unless its separate approval, quality evidence, consent design, and safe failure path are complete. Corporate and mobile capabilities are excluded unless specifically selected in B and audited.

## Beta scope and exclusions

The B inventory is a Project Owner decision, not implied by the roadmap. The approved candidate is website-first and content-first: public account/authentication and basic profiles; text Writ create/publish/read/edit; basic non-AI keyword discovery; and responsive desktop/mobile-browser use. Durable draft/save-resume management and permanent Writ deletion are excluded unless separately approved. Other exclusions include bookmarks, comments/replies/votes/follows, Q&A/communities, notifications, public API implementation except an explicitly approved dependency, native Android/iOS, AI/semantic inference/recommendations, corporate administration, and behavioural analytics. The final versioned inventory must name included packages, journeys, assets/dependencies, data classes, regions/languages, eligibility, support hours, and stop criteria. The approved content-only Writ report path is a safety dependency for public publishing; interaction safety is required if interaction capabilities are separately included. Change the inventory only through change control and scope-impact audit.

## Feedback and issue management

Use an owned issue register with unique reference, affected journey, reproduction steps, environment/release identity, severity, privacy/security flag, user impact, owner role, target resolution, and verification evidence. Redact personal content and secrets from reports. Restrict sensitive reports; define retention and access. Acknowledge feedback, communicate known issues, and do not promise release dates without approved capacity.

## Monitoring, incidents, and rollback

Before beta, name on-call/incident roles, escalation channels, service health and error indicators, and conditions to pause invitations or disable a feature. Behavioural analytics are excluded from B; optional telemetry remains disabled unless separately approved with purpose, privacy, access and retention controls. Rehearse the content-only report/response process, support handoff, backup restoration and application rollback. Database recovery is separately planned; do not assume that reverting application code reverses schema changes. Prefer tested forward repair and restore strategy for schema changes. If safety, privacy, security, integrity, or availability thresholds are crossed, pause/disable the affected scope and open incident response.

## Beta exit and initial production

Beta exit requires: approved observation period completed or explicitly ended; all feedback triaged; Critical/High findings closed and regression-tested; only eligible Medium/Low exceptions remaining under 07; success measures reviewed without overclaiming; privacy/data-retention actions complete; and B operations/runbooks updated. The project owner then separately freezes C. Gate 3 must audit every C component, including any capability not present in B, and verify beta-finding closure. If mobile or corporate scope is absent from B but selected in C, its package and full release evidence must be complete before Gate 3. The project owner separately approves production launch, regions, devices, communications, exact release commit, and rollback decision. A target date, programme completion, or audit pass does not itself authorize launch.
