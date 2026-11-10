# Risks, Assumptions, Decisions, and Change Control

**Baseline:** WZ-MASTER-ROADMAP-2027.2
**Status:** Live programme register; owners and dates remain to be assigned

## Decision-state vocabulary

These values describe decision records only. Work-package status uses the separate controlled vocabulary in document 02.

- **APPROVED BASELINE** — explicitly accepted governing principle.
- **APPROVED WITH CONDITIONS** — the decision authority approved the stated direction subject to recorded conditions; this status does not satisfy dependent package exits or authorize implementation, beta, or launch.
- **RECOMMENDED — AWAITING APPROVAL** — a proposed decision with a clear authority.
- **UNRESOLVED — BUSINESS / LEGAL / SECURITY / TECHNICAL** — evidence or a decision is required before dependent work.
- **DEFERRED** — intentionally outside the current target scope.
- **NOT AUTHORIZED** — an implementation request requires separate approval.

## Decisions requiring closure

| ID | Decision | Status | Authority / evidence needed | Schedule effect |
|---|---|---|---|---|
| D-01 | Complete programme scope A, public beta inventory B, initial production inventory C, and their exclusions. | APPROVED WITH CONDITIONS | Project Owner decision dated 2026-11-08 is retained by the consolidated decision dated 2026-11-09. B remains an unfrozen website-first/content-first candidate. WZP01-D02–D10 dispositions are recorded; the content-only reporting package/dependency adjustment is recorded under CR-WZP-2026-11-09-01. WZP-01.8 records documentary closure with conditions and a separate bounded initial WZP-02 authorization. Exact beta geography/eligibility/cohort/duration/notices/support values may remain open until before Gate 2/WZP-BETA-01. | WZP-01 is CLOSED WITH CONDITIONS; B remains APPROVED CANDIDATE, NOT FROZEN; WZP-02 is READY FOR CONTROLLED ACTIVATION only for initial local read-only verification. WZP-02 exit, WZP-OPS and audit evidence remain pre-beta requirements; no implementation, invitation or launch is authorized. |
| D-02 | Programme-wide product success measures, numeric target values, and acceptable feedback/telemetry. | UNRESOLVED — BUSINESS / PRIVACY | WZP01-D04 separately approves a qualitative task-based baseline B rubric; numeric targets are not required for initial B, behavioral analytics are excluded, and optional telemetry stays disabled unless separately approved. This does not create a numbering crosswalk or decide programme-wide future measures/collection. | Baseline B can use its approved qualitative approach. Programme-wide targets or telemetry remain pending and require purpose, minimization, consent, retention, and privacy review before collection. |
| D-03 | Accountable owners, delivery assignments, independent verifiers, and approval authorities for selected packages. | UNRESOLVED — BUSINESS | WZP01-D05 records unappointed specialist roles as explicit blockers; no individual is appointed. This is a separate Roadmap decision ID, not a crosswalk. WZP-01.8 permits only the initial local, read-only WZP-02 verification without appointments; it does not appoint any role or clear blockers for independent verification, package exit, or other work. | Required role assignments remain blockers for work that requires those authorities and for operations readiness. The bounded WZP-01.8 initial review is the only scoped exception. |
| D-04 | Corporate workforce and assignment authority; original CORP decision register recovery. | UNRESOLVED — BUSINESS | Workforce/HR and executive decision owners; recover CORP-01–23 source and resolve the 22/23 discrepancy. | Blocks corporate implementation. |
| D-05 | Corporate IdP, federation protocol, MFA, privileged access, and revocation policy. | UNRESOLVED — SECURITY | Security/IAM owner and technical proof. | Blocks corporate login and IAM. |
| D-06 | Legal entities, jurisdictions, employment rules, privacy, and retention obligations. | UNRESOLVED — LEGAL / REGULATORY | Qualified legal/privacy review; no entities or obligations are assumed. | Blocks relevant data collection and corporate deployment. |
| D-07 | MariaDB version, baseline authority, live migration history, and test database policy. | UNRESOLVED — TECHNICAL / SECURITY | Approved compatibility evidence and separately authorized disposable test identity. | Blocks any corporate schema implementation. |
| D-08 | Public API contracts, versioning, authentication, error model, and compatibility support. | RECOMMENDED — AWAITING APPROVAL | Product/API/security owners; versioned contract and compatibility policy. | Blocks API implementation and mobile coding. |
| D-09 | Mobile platform recommendation and supported device/OS matrix. | UNRESOLVED — TECHNICAL / BUSINESS | Comparative ADR with team, lifecycle, accessibility, and operational evidence. | Blocks mobile implementation. |
| D-10 | WP6B.2 independent security-verification outcome and any required correction package. | UNRESOLVED — TECHNICAL / SECURITY | Recover the audit result and close findings before PHP integration. | Blocks enabling PHP-to-Python communication. |
| D-11 | WP6C, WP6D, and WP7 scope and approvals. | NOT AUTHORIZED | Separate project-owner package approvals after dependencies and risk are reviewed. | Blocks Lab integration, cross-language integration tests, and classifier work. |
| D-12 | Separate B/C mobile and corporate inclusion, beta cohort/duration, support coverage, and stop criteria. | UNRESOLVED — BUSINESS / OPERATIONS | D01 excludes native apps and corporate administration from baseline B. The Project Owner decision dated 2026-11-09 allows exact B beta geography, eligibility, cohort size, duration, notices and support limits to remain open until before Gate 2/WZP-BETA-01. C inclusion and remaining operating boundaries are still separately open. | Values and owners are required by Gate 2/WZP-BETA-01; this timing deferral does not authorize beta or resolve C scope. |
| D-13 | Hosting, staging, production, backup, and deployment state for any future release. | UNRESOLVED — TECHNICAL / OPERATIONS | Independent environment evidence and separate access approval. | Blocks deployment readiness; no state is inferred here. |

## Conditional release exception record

This is a documentation template, not an approved waiver or an implemented WZ-GOV system. A conditional audit pass may include only bounded, non-blocking Medium/Low findings under document 07. Record one row per finding with: exception ID; Gate 1/2/3 and package; B/C inventory version and audited commit; finding ID/severity; accountable risk owner; required domain approvers; mitigation and monitoring; dated remediation deadline; retest evidence; exact expiry date; approval date; and closure state. The independent audit lead may recommend an exception; the risk owner and applicable Security, Privacy/Legal, Safety, Data, and Operations authorities must approve. Project-owner beta/production authorization is separate. Expiry, missing ownership, missing approval, or failed retest changes the gate to Fail until closure is verified. No Critical/High or Critical Security, Privacy, Data Integrity, Legal, or Safety issue can be waived.

## Risks and mitigations

| ID | Risk and impact | Likelihood | Mitigation / trigger | Owner role |
|---|---|---|---|---|
| R-01 | No confirmed staffing/capacity makes the 14-month target unreliable. | Unknown | Size approved work packages with responsible teams; replan at missed decision or dependency dates. | Project owner / programme lead |
| R-02 | Old roadmap checkmarks or milestone narratives are mistaken for completed capability. | High | Use architecture status model and evidence links; review each package status before release decisions. | Engineering lead |
| R-03 | Legacy schema snapshot, baseline migration, and actual environment differ, risking data loss or failed rollout. | High | Complete source reconciliation and approved isolated tests before any schema work; separate live verification approval. | Database/operations owner |
| R-04 | API instability causes duplicated work or mobile breakage. | Medium | Version contracts, freeze compatibility expectations, and require cross-client regression tests. | API lead |
| R-05 | Unresolved mobile technology decision delays app delivery or creates high maintenance cost. | Medium | Complete comparative ADR and lifecycle analysis before app implementation. | Mobile lead |
| R-06 | Intelligence client or classifier is treated as production-ready without verification; current service returns unavailable. | High | Keep feature disabled; close WP6B.2 audit; no WP7 or production claim without separate approval and quality gates. | Intelligence/security leads |
| R-07 | Corporate model assumes nonexistent HRMS, ERP, IdP, roles, or legal entities. | High | Maintain unknown source authorities and require owner decisions; keep corporate work separate from public release scope. | Corporate architecture owner |
| R-08 | Privacy, legal, or safety requirements are discovered late. | Medium | Involve qualified reviewers during requirements and product acceptance, before collecting beta data. | Privacy/legal and Trust owners |
| R-09 | Local, GitHub, Hostinger, staging, and production states diverge. | High | Verify each target independently under separate authorization; bind release to exact Git commit. | Release/operations owner |
| R-10 | Audit windows are too short or incomplete, allowing high-impact defects into beta. | Medium | Freeze beta inventory early, preserve test/audit window, and apply objective fail criteria. | QA/security leads |
| R-11 | Engagement-driven metrics undermine the knowledge-first product promise. | Medium | Require product acceptance based on usefulness, trust, accessibility, and time well spent; review metric side effects. | Product owner |
| R-12 | Uncommitted work is lost or accidentally bundled into a release. | Medium | Preserve working tree, review exact diff, do not clean/reset/stash, and release only a separately approved exact commit. | Engineering/release owner |
| R-13 | Writ/Comment PDO row objects do not meet the concrete model types required by authorization policies, likely blocking owner actions before policy logic. | High functional impact; runtime outcome not reproduced | Align subject contract without weakening ownership/default-deny; add PDO-shaped positive/negative regression coverage and independent review. | Application engineering / Security |
| R-14 | Aggregate/release test execution can write/delete shared storage, session, logs, and an environment-selected database. | High | Keep both runners disabled for verification until Layers 1–4 are separated, paths are isolated, and database identity is fail-closed and independently verified. | QA / Security / Database owner |
| R-15 | No Windows PHP/Composer executable was found and current WSL enumeration returns E_ACCESSDENIED, preventing local PHP evidence. | High schedule/verification impact | Appoint an environment owner; verify existing WSL read-only; request separate approval for any portable fallback. | Environment/platform owner |
| R-16 | Schema snapshot and baseline migration differ on handle/actor nullability and actor index name; actual target state is unknown. | High data-integrity risk | Preserve all definitions; resolve D07 with source/write-path evidence and a separately approved disposable target before schema action. | Database/migration and application data owners |
| R-17 | Registration lacks explicit server-side validation, collision handling, and generic login failure wording. | Medium account-integrity/security risk | Approve field and canonicalization rules; enforce server-side validation and unique-key handling; test rejection and enumeration behavior. | Product / Application / Security |
| R-18 | GET `/notifications` changes state; bookmark route accepts numeric IDs while caller supplies generated public IDs. | Medium behavior/integrity risk; both capabilities excluded from B | Keep excluded; require separate scope approval, then use safe write semantics and public-ID-consistent routing. | Product / Application / Security |
| R-19 | Acceptance specification retains unresolved wording that predates approved PO-02/PO-04/PO-05 outcomes; PO-07 evidence remains open. | Medium baseline-freeze risk | Reconcile the document to WZP-01.6 without reopening decisions; obtain fixed qualitative cases and browser/viewport matrix before B freeze. | Product / Accessibility / Project Owner |
| R-20 | Content-only Writ reporting is approved in scope but has no verified route or staffed operating process. | High safety risk if public publishing enters B | Appoint Trust/Safety and Operations owners; approve, staff, and rehearse the reporting/response process before beta. | Trust/Safety / Operations / Security/Privacy |

### WZP-02.1 planning update (2026-11-09)

WZP-02.1 records these risks as evidence-based proposals, not accepted fixes. Its documents do not change the 32-package roadmap baseline, resolve D07, appoint an owner or independent verifier, authorize source corrections, or freeze Baseline B. The implementation approval pack splits the proposed follow-on work into independently approvable units. Until the Project Owner selects units and required roles are appointed, implementation and unsafe test execution remain blocked; notifications/bookmarks remain outside B.

## Assumptions and constraints

| ID | Statement | Treatment |
|---|---|---|
| A-01 | Existing product architecture remains a custom PHP MVC modular monolith. | Approved architecture constraint; extend incrementally, do not replace or split into microservices without an ADR. |
| A-02 | Public and corporate identities remain separate. | Approved security constraint. |
| A-03 | WZ-CORP documents describe proposed logical domains and physical tables. | Use for planning only; no schema or corporate runtime is approved. |
| A-04 | Python intelligence service is private, loopback-bound locally, opt-in, and unavailable for classification until WP7. | Follow its boundary; do not expose or integrate without package approval. |
| A-05 | November 2026–December 2027 is the requested target window. | Treat as provisional, not a forecast or guarantee. |
| A-06 | Existing roadmap and history files contain historical/strategic language. | Do not treat checkmarks, empty M0–M15 placeholders, or narrative completion language as current implementation evidence. |

## Change control

Every baseline change receives a unique change request and records: reason and evidence; affected package IDs; user and knowledge outcome; architecture/security/privacy/database impact; dependencies; deliverable and acceptance changes; estimate/capacity impact; audit/beta scope impact; rollback or forward-repair impact; proposed owner; and required approvers. Update the roadmap only after approval from the authorities affected by the change. A scope/date adjustment does not waive a technical, security, legal, audit, beta, or release gate. Preserve prior decisions and record superseded entries rather than deleting them.

## Recorded change requests

### CR-WZP-2026-11-09-01 — Content-only public Writ reporting within WZP-TRUST-01

- **Status:** Scope and dependency adjustment approved with conditions by the Project Owner on 2026-11-09 through WZP01-D03; the roadmap and package records below now reflect the approved boundary. This record does not authorize implementation, staffing, beta, or launch.
- **Reason and evidence:** Baseline B may include public Writ publishing while excluding WZP-PUB-02 interactions. The Project Owner approved a bounded public Writ reporting and response capability within WZP-TRUST-01, independently of WZP-PUB-02, so public knowledge publishing has an accountable safety path.
- **Affected package IDs:** WZP-01, WZP-PUB-01, WZP-PUB-02, WZP-TRUST-01, WZP-OPS-01, WZP-AUD-01, and WZP-BETA-01. This scope-change record predates WZP-01.8's current-status recording; the scope and dependency approval remain unchanged. See WZP-01.8 and Roadmap 02 for current package status.
- **Approved boundary and dependency change:** WZP-TRUST-01 owns the bounded content-only report/triage/response slice. WZP-PUB-01 is a dependency for integration with public Writ publishing. WZP-PUB-02 is a dependency only for discussion, community, or other interaction moderation. Public Writ reporting and its operational evidence are required before beta whenever public Writ publishing is in the frozen B inventory; interaction moderation is additionally required if interactions are selected.
- **Deliverables and acceptance:** The content-only path must cover a user-accessible report action, restricted receipt and access, categorization and triage, urgent safety/spam escalation, authorized review/action, case outcome and evidence, suitable reporter communication, support handoff, and defined access/retention/audit arrangements. It must be staffed, operational, and evidenced before beta invitations. A proposed interface or unstaffed queue does not pass.
- **Knowledge, architecture, security, privacy, and database impact:** The change supports safe contribution to the Knowledge Network and preserves the existing PHP modular-monolith direction. Reports and case evidence require restricted access, minimization, audit, and an approved retention arrangement. No database design, schema change, data collection implementation, or runtime integration is approved here.
- **Schedule, capacity, and owner:** This adds a bounded reporting slice to WZP-TRUST-01; estimate, capacity, and schedule impact are not verified and must be assessed by appointed owners. The Trust/Safety operational owner remains unassigned. The Project Owner is the decision authority for this scope record; no delivery or operational appointment is inferred.
- **Required approvals:** Project Owner scope approval is recorded. Trust/Safety, Security/Privacy/Legal as applicable, Product acceptance, and Operations authorities must be appointed and provide their relevant policy, access, retention, response, and operational approvals before implementation or beta. No individual is appointed by this record.
- **Audit, beta, and recovery:** Gate 2 must verify the content-only path and its restricted access, escalation, evidence, support handoff, and operational rehearsal whenever public Writ publishing is selected. Any interaction path remains conditional on WZP-PUB-02. This is a documentation-only baseline adjustment; any future supersession must use a new approved change request and preserve this record. No runtime rollback or database operation is involved.
