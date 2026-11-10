# WriteZone Implementation Delivery Schedule 2027

**Planning baseline:** 2026-11-09
**Planning horizon:** 2026-11-09 to 2027-12-31; dependent work may extend beyond it
**Status:** Conditional, evidence-based planning forecast; not a commitment or implementation approval
**Authority:** Project Owner for product scope and package activation; specialist owners for their evidence

## 1. Executive answer

The immediate work is to appoint delivery and independent review owners, complete WZP-02 safely, resolve the remaining Baseline B acceptance decisions, and then build and verify only the website-first, content-first B candidate. The application already contains partial account, profile, Writ, and keyword-search paths, but source presence is not acceptance: important validation, authorization, scope, safety, test, and operational evidence is missing.

**Best realistic forecast, if required owners are appointed by early 2027 and a focused team is available:** complete the B website implementation around **Q1–Q2 2028**, reach Gate 2/beta readiness around **Q2–Q3 2028**, begin a controlled public beta around **Q3–Q4 2028**, and consider the first production launch around **Q1–Q3 2029**, after beta exit, a separately frozen C inventory, Gate 3, and a separate written Project Owner decision. The beta period shown in the scenario model is an 8–12 week planning allowance only; no beta duration is approved.

An accelerated, fully staffed scenario could begin a controlled beta in **Q4 2027–Q1 2028** and consider production launch in **Q2–Q3 2028**. That requires prompt appointments, fast decisions, enough parallel capacity, no major rework, and successful independent review. Under constrained capacity, beta could move to **Q2–Q4 2029** and first production launch to **Q2–Q4 2030**.

**December 2027 is not a credible commitment for a complete Knowledge Network or guaranteed public launch.** It is only a stretch date for the start of B beta in the accelerated scenario. The full A vision has no defensible completion date from current evidence; it requires many separately approved packages and will extend beyond the first website launch.

## 2. Evidence base and limits

This forecast uses the local WZ-MASTER-ROADMAP-2027.2, WZP-01 decisions and B candidate, WZP-02 findings, the engineering readiness and risk registers, and the proposed corporate architecture. Local evidence indicates:

- WZP-01.8 closes the documentary decision package with conditions. B is an approved-in-principle candidate, not a frozen release inventory. A separate scope inventory still carries stale WZP-01 OPEN / AWAITING OWNER DECISION and WZP-02 BLOCKED headers; WZP-01.8 and the controlled roadmap status govern. That stale-header correction remains a documentation follow-up and is not silently made here.
- WZP-02.10 verified isolated PHP 8.4/Composer tooling, Composer metadata, and syntax for 31 listed PHP files. It did not run application regressions, connect to a database, or verify Hostinger, staging, production, deployment, or actual migration state.
- WZP-02.12 records AP-01 and AP-03 as conditionally approved, not activated. The AP-01/AP-03 delivery owners, independent Security reviewers, WZP-02 owner, and independent verifier remain unassigned. AP-03 does not include the database-target allowlist in AP-04.
- Current source has account/profile, Writ, and keyword-search code, but WZP-01 acceptance records important gaps: incomplete registration validation and distinct login failure messages; Writ-policy subject-contract mismatch; unapproved profile/visibility/content-limit/retry details; excluded controls still present in source; and no Writ report/response path found.
- Baseline B requires a working content-only Writ reporting and response process before beta if public publishing remains in the frozen B inventory. The required operator ownership and operational process are not verified.
- The roadmap makes WZP-API-01 a prerequisite of WZP-PUB-03 search/discovery. Public API implementation WZP-API-02 is not automatically in B. Notifications and bookmarks are excluded from B and their corrections must not be added as beta blockers unless scope changes.
- Corporate records are a proposed design only. No HRMS, ERP, IdP, legal-entity catalogue, corporate runtime, or approved physical schema is assumed. Native Android/iOS clients are not a B requirement.

The estimated durations below are planning judgments, not measured team velocity. No named delivery team, allocation, cost estimate, historical throughput, deployment inventory, or approved B freeze date is recorded. All estimates are low confidence until owners size actual tasks and confirm capacity.

## 3. Release boundaries

| Inventory | Meaning | Schedule treatment |
|---|---|---|
| **A — complete programme** | Long-term Knowledge Network and Knowledge Hub direction, delivered through individually approved capabilities. | Not a single release and not dateable as one finished system on current evidence. Includes future social/knowledge graphs, communities, trust/recognition, creator capabilities, intelligence, analytics, global support, APIs, mobile, and corporate domains. |
| **B — controlled beta candidate** | Website-first and content-first candidate: public account/session/basic profile; text Writ publish/read/edit; basic non-AI discovery; responsive desktop/mobile browser; minimum security, privacy, safety, recovery, and operations. | Not frozen. Detailed product decisions and release evidence remain. A beta requires Gate 2 and a separate written Project Owner approval. |
| **C — first production inventory** | Separate, named and versioned inventory derived from capabilities that successfully passed the controlled beta and its finding closure. Any C-only capability requires its own approved package and evidence path. | Not selected. No automatic promotion from B. Gate 3 and a separate launch decision are required. |

Baseline B exclusions include bookmarks, comments/replies/votes/follows, Q&A, communities, notifications, public API implementation, native apps, AI recommendations, corporate administration, and behavioral analytics. Their source-code presence does not make them approved B capability. Content-only Writ reporting is included as a pre-beta safety dependency for public publishing, not as a broad interaction/moderation feature.

## 4. Executive milestone timeline

Ranges assume the relevant owner decisions are made, required specialists are appointed, and the dependencies below pass. They are not guaranteed dates.

| Milestone | Accelerated | Realistic planned | Constrained capacity |
|---|---|---|---|
| First AP-01/AP-03 implementation activation, after owner and readiness gates | Dec 2026–Jan 2027 | Q1 2027 | Q2–Q3 2027 or later |
| WZP-02 exit after correction evidence and review | Jan–Feb 2027 | Q1–Q2 2027 | Q3–Q4 2027 or later |
| B website implementation complete | Q3–Q4 2027 | Q1–Q2 2028 | Q4 2028–Q2 2029 |
| Gate 2 / beta readiness | Q4 2027 | Q2–Q3 2028 | Q2–Q4 2029 |
| First controlled public beta start | Q4 2027–Q1 2028 | Q3–Q4 2028 | Q2–Q4 2029 |
| Beta exit and C inventory decision | Q1–Q2 2028 | Q4 2028–Q1 2029 | Q4 2029–Q2 2030 |
| First production launch consideration | Q2–Q3 2028 | Q1–Q3 2029 | Q2–Q4 2030 |
| Substantial wider Knowledge Network expansion | 2029–2030 | 2030–2032 | 2032 or later |
| Complete A programme | No defensible finish date | No defensible finish date | No defensible finish date |

“Production launch consideration” means all evidence and gates may be ready for an owner decision. It does not mean deployment or launch has been approved. No production action is authorized by this schedule.

## 5. Critical path — realistic planning case

Project Owner appointments and package activation → WZP-02 test safety and source/schema exit → freeze B journeys and acceptance → WZP-PUB-01 account/profile/Writ and WZP-API-01 contracts → WZP-PUB-03 non-AI search/discovery → integrate WZP-TRUST-01 Writ reporting and response → integrated B verification and WZP-OPS-01 → WZP-AUD-01 Gate 2 → separate beta approval and controlled beta → beta finding closure and freeze C → production operations readiness and WZP-AUD-02 Gate 3 → separate Project Owner launch decision.

There is no defensible duration-based critical path until the release inventory, owners, capacity, and estimates are approved. The dependency path is nevertheless clear. WZP-TRUST-01 can proceed in parallel with website work only if its own policy and operational owners are staffed; it must be integrated before beta when public Writ publishing is in B.

## 6. Baseline B workstream schedule

The date ranges below describe the **realistic planned** case. Accelerated and constrained alternatives are summarized in section 8.

| Workstream | Earliest start, duration, plausible completion | Owner and approval | Dependencies and principal risks | Verification evidence | B beta / production effect |
|---|---|---|---|---|---|
| 1. Governance, appointments, WZP-02 | Documentation/appointments can start 2026-11-09. Allow 1–3 months for appointment and decision closure. AP implementation can start only when its unit-specific readiness gates pass; WZP-02 exit follows 2–4 months of corrections, source reconciliation, and review, targeting Q1–Q2 2027. | Project Owner appoints WZP-02 owner/verifier, AP-01 application owner/Security reviewer, AP-03 QA/Security owners, and required Product, Trust/Safety, Privacy, and Operations authorities. AP-01/AP-03 conditional approval is recorded in WZP-02.12; implementation activation remains separate. | No delivery capacity exists in the records. AP-03 must make approved tests safe; AP-01 requires a Layer 1 test capability. Test safety, migration/snapshot differences, registration policy, and independent review remain incomplete. | Appointment and conflict records; scoped plans; isolated test-root evidence; authorization regressions; source-only schema difference ledger; independent Security and verifier reports. No DB tests without separate approval. | Direct blocker to controlled B implementation and Gate 2. WZP-02 closure is required before dependent package work. |
| 2. Core corrections and schema/source reconciliation | Start after owners and package activation; establish the AP-03 safe layer before executing tests whose side effects it must isolate. Allow 2–5 months within WZP-02; target Q1–Q2 2027 in the realistic case. | AP-01 and AP-03 owners/reviewers; separate Project Owner approval for any registration/sign-in correction and any schema or DB activity. | AP-01 corrects only Writ/Comment policy subject contracts. Registration validation and generic login errors are separately scoped (AP-06 remains unapproved). Source differences include users.handle, notifications.actor_id, and notification actor index naming; semantic decisions are unresolved. | AP-01 owner/non-owner/malformed-subject and no-mutation-on-denial tests; AP-03 sentinel/concurrency/containment/session/log/cache tests; registration negative tests only after separate approval; source diff and migration-history review. | AP-01 and registration/security issues block accepted account/Writ journeys. Notification and bookmark issues do not block B while excluded. Live schema and database behavior remain unverified. |
| 3. Baseline B product acceptance and freeze | Can proceed in parallel with WZP-02 decisions; start Nov 2026, allow 2–4 months, target Q1–Q2 2027. Freeze only after open outcomes and package dependencies are accepted. | Project Owner and appointed Product/content owner; Security, Accessibility, Trust/Safety, Privacy/Legal and Operations owners for their controls. | Resolve profile fields and visibility; password recovery/support boundary; registration rules and login error wording; Writ visibility, content length/Unicode rule, retry behavior; search fields/relevance cases; browser/viewport and accessibility matrix; FeedInspector inclusion; reporting action policy; beta boundary. | Versioned B inventory; requirement-to-source/test/evidence map; fixed qualitative query cases; approved browser matrix; safety/recovery decisions. | Direct blocker: no B Gate 2 audit until B is frozen. Do not start unapproved feature scope from a provisional date. |
| 4. Core B website — WZP-PUB-01 | Earliest after WZP-02 exit and journey decisions; allow 6–9 months; realistic completion Q1–Q2 2028. Existing source may reduce coding but not verification effort. | Appointed PHP delivery owner, Product/content owner, Security reviewer, accessibility reviewer; separate Project Owner approval for the package. | Authentication and profile; registration validation; Writ publish/read/edit/recovery; public visibility and attribution; remove/disable B-excluded interaction and delete controls. Current code is partial and not accepted. | Isolated positive/negative journey regressions; input/auth/session/CSRF checks; save/error/retry evidence; responsive browser matrix; accessibility review; source/diff review. | Direct blocker for B beta and production if selected. |
| 5. API contract and non-AI discovery — WZP-API-01 then WZP-PUB-03 | Start after WZP-02; API-01 design/review 6–10 weeks, then search/discovery implementation and verification 2–4 months; realistic completion Q4 2027–Q1 2028. | API/platform and search/discovery owners, Product owner, Security/Privacy reviewers; separate package approval. | Current roadmap makes API-01 a mandatory dependency of PUB-03. Approve consumer, contract, query fields/limits, empty/error states, qualitative search cases, and taxonomy. API-02 implementation is not automatically required for server-rendered B. | Contract review and negative security cases; approved query fixtures and qualitative review; non-AI search regressions and load evidence against an approved target. | Direct B dependency if basic search remains in B. No AI, semantic classifier, personalized recommendations, or Knowledge Graph completion claim. |
| 6. Content-only reporting — WZP-TRUST-01 | Policy/owner work may start in Q1 2027; allow 4–8 months including operating rehearsal; target Q4 2027–Q1 2028. Can run in parallel with website work only with its own staffed owners. | Appointed Trust/Safety and Operations owners, with Security/Privacy/Legal and Product decisions; Project Owner approves scope; no such implementation activation is implied. | B includes public Writ publishing, so reporting intake, restricted access, triage, urgent escalation, authorized action, outcomes/evidence, reporter communication, support handoff, and retention/audit must be operational. No route/workflow was found. | Synthetic case rehearsal; role/access denial; escalation and support handoff; evidence/audit review; approved operating policy, coverage, retention and privacy. | Hard B beta blocker while public Writ publishing remains in B. It is not the broader interaction moderation scope. |
| 7. Integrated quality and operations — WZP-03 and WZP-OPS-01 | Continuous from first approved implementation; B-specific operations work can start Q3 2027; allow 2–4 months and finish by Q2 2028. | Appointed QA, Security/Privacy and Operations owners; separate Project Owner acceptance; environment access requires separate authorization. | Safe tests, incident/support owner, monitoring, capacity, backup/restore, recovery, rollback/forward repair, environment identity and deployment procedure are not verified. Do not assume Hostinger state. | Independent journey/security review; isolated regression results; backup/restore and incident rehearsal in authorized non-production; operations runbook and exact release candidate identity. | Direct Gate 2 and Gate 3 blocker. Beta/production require separately verified operations outputs. |
| 8. Gate 2 and beta readiness — WZP-AUD-01 | After B freeze and all included evidence; allow 6–10 weeks; realistic Q2–Q3 2028. | Independent cross-functional audit lead and appointed QA/Security/Privacy/Trust/Safety/Operations authorities; Project Owner separately authorizes beta. | Any critical/high issue, missing owner, incomplete scope inventory, unoperational reporting path, unresolved mandatory decision, or failed recovery evidence prevents pass. | Frozen B inventory matches audited assets; reproducible requirement/test/security/privacy/accessibility/operations evidence; findings and residual exceptions recorded. | Direct beta gate. No beta invitation or rollout before pass and separate written authorization. |
| 9. Controlled public beta — WZP-BETA-01 | Earliest after Gate 2 and separate owner decision; realistic Q3–Q4 2028. Use 8–12 weeks only as an unapproved planning allowance for cohort observation and issue handling. | Beta Product and Operations owners, support and Trust/Safety coverage; Project Owner approval of cohort, regions, duration, notices, and support limits. | Cohort, geography, eligibility, duration, support capacity, privacy notices, telemetry/measurements and stop conditions remain open. | Limited feature/region cohort; operational monitoring and issue triage; user journeys; incident and pause/rollback readiness; dated entry/exit decision. | This is the first controlled public beta, not a general launch. |
| 10. Beta exit, C freeze, Gate 3 and first production launch | After beta and finding closure; allow 3–6 months; realistic Q4 2028–Q3 2029. | Product/Engineering owners, Operations, independent Gate 3 auditors, Security/Privacy; Project Owner separately freezes C and approves launch. | Beta issue volume is unknown; C is not selected; production configuration, backup/restore, monitoring, support, exact release identity, and deployment/recovery evidence are unverified. | Closed/retested beta findings; C inventory and full Gate 3 evidence; rehearsed recovery/rollback; exact Git release identity; separate written owner launch decision. | Direct blocker to the first production launch. A beta pass alone does not authorize C or production. |

## 7. Post-beta and separate programme workstreams

These are long-term A capabilities, not B prerequisites unless later selected in a frozen release inventory. Planning and decision work may run earlier only with separate approval and without displacing B critical-path work. The implementation windows below assume a post-beta start, separate package approval, and completed prerequisites; durations are rough planning ranges, not committed estimates.

| Capability/workstream | Earliest plausible window and duration | Dependencies, owners, and approval | Release effect and evidence |
|---|---|---|---|
| Social Graph, discussions, Q&A, communities — WZP-PUB-02 | Earliest 2028 after B foundations; 6–12+ months. | Separate Product/community approval; WZP-02, PUB-01, API-01; appointed Trust/Safety owners and interaction moderation path. | Not a B dependency. Require multi-role/state tests, abuse/report/appeal paths, and operational moderation evidence. |
| Content Graph/Knowledge Graph beyond B search — WZP-PUB-03 expansion | 2028–2029 onward; 6–18+ months in slices. | Stable content IDs/metadata/provenance, approved taxonomy and relevance measures; Product/search owner. | B includes only basic non-AI discovery candidate. Broader graph relationships require own package acceptance and data provenance tests. |
| Trust, reputation, recognition and creator capabilities — WZP-TRUST-01/02 and approved public packages | 2028 onward; 6–18+ months per approved slice. | Policy, appeals, privilege/scope, privacy and operating owners; do not infer automated reputation or creator monetization approval. | Post-beta unless selected. Require transparent criteria, abuse/appeal/security review, and audit evidence. |
| Public API implementation and integrations — WZP-API-02 | Contract design may start 2027; implementation 3–6+ months after selected consumer approval and stable domain behavior. | WZP-API-01 first; named consumers, auth/version/error/rate/idempotency contracts; API/security owners. | API implementation is not automatic B scope. Stable contracts are required before mobile implementation. |
| Intelligence Layer / AI classification — WZP-INT-03/04/05 and later INT-06 | No start until separate approvals; earliest plausible 2028+; multi-stage 9–24+ months depending model, evaluation, privacy, and integrations. | WP6B remains awaiting technical validation in the roadmap; WP6C/Lab, WP6D, and WP7 are not authorized; classifier currently returns unavailable. Requires consent/privacy, taxonomy, corpus, quality and security decisions. | Does not block B. No AI, semantic classification, recommendations, or user-content integration may be claimed before approved implementation and evaluation. |
| Analytics and measurement | After separate purpose/Privacy approval; 2028+; 3–9+ months for an approved first capability. | Define allowed measures and data minimization; behavioral analytics are excluded from B and optional telemetry is disabled. | Not a B prerequisite. Requires purpose, access, retention and privacy controls plus quality-of-measurement review. |
| Multilingual and global support — WZP-PUB-04 | Locale assessment may run after owners are appointed; implementation likely 2028–2030, 6–18+ months per approved locale set. | Approved language/region inventory, fallback, accessibility, legal/privacy, content and support owners. | Only responsive desktop/mobile-browser baseline is in B. Global support cannot be claimed from translation utilities alone. |
| WZ-ORG, WZ-IAM, WZ-GOV, WZ-EXEC — WZP-CORP-01–05 | Separate stream; earliest design/decision work may proceed when owners are appointed; runtime foundation plausibly 2028–2030+, at least 12–24+ months after decisions. | Corporate docs are proposed only. Resolve original decision register discrepancy, workforce/source authority, IdP/MFA, legal/privacy, database semantics/version, disposable test readiness, and domain owners. ORG precedes IAM; IAM and ORG underpin GOV; executive visibility consumes approved domain capabilities. | Not a public beta prerequisite unless explicitly included. No corporate systems are assumed to exist. |
| Android and iOS — WZP-MOB-01–03 | Assessment could run in parallel in 2027 if approved; implementation earliest 2028, with 6–12+ months build plus 2–4 months security/device/store readiness. | Appointed mobile owner; approved platform and scope; stable WZP-API-02 and selected feature contracts; device, signing, support, privacy and store decisions. | Responsive mobile browser is in B; native applications are not. Must receive full security/accessibility/device review when selected. |

“Substantial wider Knowledge Network” means a meaningful set of separately approved capabilities beyond B, not completion of A. The realistic planning range is 2030–2032; the entire long-term programme cannot be credibly given a single date without approved packages, team capacity, and scope boundaries.

## 8. Scenario assumptions

| Scenario | Capacity and decision assumptions | Schedule consequence |
|---|---|---|
| Constrained capacity | Appointments take 3–6+ months; one or fewer effective delivery streams; reviews are sequential; business/privacy/safety questions require rework; database-dependent evidence needs additional authorization. | WZP-02 exit may slip into Q3–Q4 2027 or later; beta likely Q2–Q4 2029; production launch Q2–Q4 2030 or later. |
| Realistic planned delivery | Required roles are appointed by early 2027; one focused PHP team plus part-time QA/Security/Product/Trust/Safety/Operations capacity; work follows dependencies; no major scope expansion. | WZP-02 exit Q1–Q2 2027; B code Q1–Q2 2028; beta Q3–Q4 2028; first production launch Q1–Q3 2029. |
| Accelerated delivery | Appointments and policy decisions close by Dec 2026; dedicated application, QA, Security and Product owners; Trust/Safety and Operations run in parallel; no major defect or source/schema rework; reviews are available immediately. | B implementation can finish Q3–Q4 2027 and beta may begin Q4 2027–Q1 2028; production launch still follows beta and Gate 3, around Q2–Q3 2028. This is a stretch case, not the current forecast. |

No scenario assumes unlimited developers, automatic role combinations, omitted reviews, unsafe testing, live database access, or automatic approval at a target date.

## 9. Decisions and dependencies

### Project Owner actions that block near-term implementation

1. Appoint the AP-01 application delivery owner, AP-01 Security reviewer, AP-03 QA/test-isolation owner, AP-03 Security reviewer, WZP-02 package owner, and independent verifier. Record authority and independence conflicts; no appointments are currently documented. Conditional approval is not implementation activation.
2. Activate AP-01 and AP-03 only after those roles and each unit's readiness/acceptance evidence are recorded. Conditional approval in WZP-02.12 is not implementation activation.
3. Appoint the Product/content, Trust/Safety, Privacy/Legal as required, and Operations authorities needed to freeze the selected B journeys and operate public content reporting.
4. Decide the unresolved B journeys before their implementation/freeze: public profile fields and visibility; password recovery/support boundary; registration rules and login error wording; Writ visibility, limit/Unicode rule and retry behavior; FeedInspector disposition; search fields/query rules/relevance cases; browser/accessibility matrix; reported-content hold/action policy; beta cohort, geography, duration, notices, support and stop conditions.
5. Resolve the stale WZP-01 status header in the scope inventory through a controlled documentation correction. WZP-01.8 is the current closure decision; this schedule does not reopen it.
6. Decide whether any database-dependent test or schema change is necessary. AP-03 excludes DB target controls; AP-04/AP-07 remain separately unapproved. Do not connect or migrate without separate exact authorization.

### Decisions that can wait if excluded from B

Community/Q&A/following, notifications, bookmarks, interaction moderation, API endpoints, native app platform/build, AI providers/classification/recommendations, behavioral analytics, advanced multilingual support, corporate HR/ERP/IdP choices, corporate admin, and monetization can remain open without blocking the first B beta. They become blockers only if selected into B/C or required for safe operation of an included capability.

### Explicit package approval boundaries

- AP-01 and AP-03 are conditionally approved but not activated under [WZP-02.12](WZP-02.12-PROJECT-OWNER-DECISION-AND-AP-APPROVAL-RECORD.md).
- AP-04 database target allowlisting, AP-06 registration/sign-in hardening, AP-07 schema changes, and all other AP units are not approved by WZP-02.12.
- WZP-PUB, TRUST, API, OPS, audit, beta, corporate, intelligence, and mobile packages still require their listed package-specific scope, owner, review, and Project Owner authorization.
- A schedule milestone never authorizes code, database activity, deployment, beta invitation, or release.

## 10. Critical-path delays and principal risks

1. **Unappointed owners and reviewers:** AP-01/AP-03 cannot activate; WZP-02 cannot exit; the entire B path shifts.
2. **Unsafe test runner and unapproved database path:** AP-03 only isolates Layer 1/2 filesystem/session work; AP-04 is separate. If approved acceptance requires DB integration evidence, target authorization and fail-closed identity policy add schedule time.
3. **Unfrozen B acceptance and stale documentation:** profile, content, search, recovery, browser, and FeedInspector decisions can cause rework. WZP-01.8 controls closure while the scope inventory's old status header remains inconsistent.
4. **Public content safety and operations not staffed:** the Writ report/triage/response path is absent; it is a hard beta prerequisite when public Writ publishing is in B.
5. **Audit, recovery, and release environment unknown:** no Hostinger/staging/production, backup/restore, operational capacity, or release identity was verified. Gate 2 and Gate 3 cannot be compressed by code completion alone.

Other schedule risks include baseline-to-migration differences, registration and login security work requiring separate approval, the WZP-API-01 dependency for PUB-03, possible product-scope changes, and unknown beta findings. Notification and bookmark findings are not on the B critical path while those capabilities remain excluded.

## 11. Replanning and acceptance rules

At each package start, confirm owner, approval, dependencies, target environment, exact files, acceptance, and independent review. At each milestone, record the current Git commit, evidence, risks, dates, and schedule variance. Reforecast when an appointment, decision, test-safety gate, package, or audit slips; do not preserve a date by removing an approved feature's acceptance or safety control. Scope changes require Project Owner approval and dependency review.

The 2027 roadmap window remains a provisional programme target. In the current realistic case it supports substantial B implementation work and possibly early readiness, but **not a guaranteed December 2027 public launch and not full A completion**. Continue with the realistic scenario as the planning forecast and retain accelerated/constrained cases as sensitivities until staffing, package estimates, B freeze, and release decisions are documented.

This document is a planning artifact only. It authorizes no application changes, test execution, database operation, deployment, beta, public launch, or scope expansion.
