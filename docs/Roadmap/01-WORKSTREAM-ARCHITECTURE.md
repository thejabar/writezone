# Programme Workstream Architecture

**Authority:** WZ-MASTER-ROADMAP-2027.2
**Status:** Proposed coordination model; no implementation authorization

## Workstreams

| ID | Workstream | Scope and intended outcome | Boundaries and dependencies | Proposed accountable role / approver |
|---|---|---|---|---|
| A | Public Knowledge Network | Publishing and rich content; profiles; discussions and Q&A; communities and social connections; search, discovery, knowledge graph foundations; multilingual support; content-first desktop/mobile experience. | Build only the product-owner-approved website scope. Keep public identity separate from corporate IAM. Prefer incremental extension of the existing PHP MVC application. | Product owner / project owner for scope; engineering lead for technical acceptance. |
| B | Trust, Safety and Administration | Reporting, moderation, appeals, reputation, recognition, administrative control centre, audit trails, and platform analytics. | Define policy, privacy, appeals, and operator authorities before enabling actions. Trust and recognition are not substitutes for governance approval. | Trust and Safety owner, security lead, project owner. |
| C | Intelligence and Recommendation | Semantic classification, relationship mapping, knowledge discovery, recommendation, and governed AI-assisted features. | Preserve the Python service boundary and approved WP5→WP6→WP7 sequence. The service currently returns unavailable for classification. No model/provider or production recommendation claim is authorized by this roadmap. | Intelligence product owner and security lead; project owner approves new work packages. |
| D | Corporate Architecture and Backend | WZ-ORG registry, WZ-IAM corporate identities and scoped access, WZ-GOV workflows/approval/audit/evidence, and later approved corporate operations. | docs/Corporate is a proposed design baseline. Keep public identity separate; do not assume an HRMS, ERP, IdP, workforce source, legal entity, or department exists. Corporate work is not automatically a public beta dependency. | Corporate architecture owner (not yet appointed), domain owners, project owner. |
| E | API and Integration Architecture | Versioned contracts, authentication boundaries, service integration, generic errors, observability, compatibility, and shared mobile-backend interfaces. | Stabilize public API contracts before full mobile integration. The internal intelligence client is not a public API. Do not describe in-process events as a durable integration bus. | API/platform lead, security lead, project owner. |
| F | Android and iOS Applications | Technology evaluation, authentication, publishing, reading, comments, communities, notifications, profiles, discovery, accessibility, privacy, offline/error handling, device verification, and store-release preparation. | Evaluate native, Flutter, React Native, and other viable approaches before recommending one. Full app implementation depends on stable backend contracts. No technology is selected. | Mobile lead (not appointed), product owner, security lead, project owner. |
| G | Infrastructure and Operations | Environment architecture, hosting, deployment controls, backups, disaster recovery, monitoring, incident response, capacity planning, and operational readiness. | Current deployment documentation is repository evidence only; do not assume local, GitHub, Hostinger, staging, or production states match. Live infrastructure access is outside this package. | Operations owner (not appointed), security lead, project owner. |
| H | Quality, Security and Audit | Continuous engineering assurance, test strategy, security/privacy review, comprehensive pre-beta audit, and final production audit. | Quality gates apply throughout development. Audit scope must name included website, API, intelligence, corporate, mobile, and infrastructure components. Audit clearance and release approval are distinct. | QA lead, security/privacy reviewers, project owner. |

## Coordination rules

- A workstream may progress only within its approved package scope. The work-package register records exclusions and authority.
- Product and architecture decisions precede dependent implementation. Cross-stream API contracts are agreed before consumers integrate.
- Public product work may run alongside corporate architecture or backend work when dependencies are independent. Corporate systems not included in beta scope do not block public beta.
- Mobile technology assessment, product discovery, and API planning may run early. Mobile feature implementation waits for the agreed core backend and stable contracts.
- Intelligence evaluation and governance must precede classification or recommendation claims. A successful PHP transport foundation does not make classification available.
- Gate 1 engineering assurance is continuous. Gates 2 and 3 are formal, evidence-based release decisions.
- WZP-01 defines the shared authority and safety boundaries. Under the Project Owner’s 2026-11-09 decision and CR-WZP-2026-11-09-01, WZP-TRUST-01 contains a bounded public Writ reporting slice independent of WZP-PUB-02; it is required and operational before beta when public Writ publishing is included. Discussion/community reporting and moderation remain conditional on WZP-PUB-02 journeys and the corresponding WZP-TRUST-01 path. No new package ID or implementation approval is created.
- WZP-PUB-03 delivers non-AI knowledge foundations: stable content identifiers and metadata, explicit relationships, provenance, and basic search/discovery. WZP-INT-05 and WZP-INT-06 may consume those stable interfaces; the knowledge foundation does not depend on either intelligence package.

## Workstream entry and exit conditions

| Workstream | Entry condition | Exit evidence for an approved package |
|---|---|---|
| A. Public Knowledge Network | Approved user scope, source baseline, product acceptance, and named accountable owner. | Each selected journey has implementation, security, accessibility, responsive-layout, and regression evidence accepted by its product and engineering owners. |
| B. Trust, Safety and Administration | WZP-01 policy/permission foundation and assigned Trust/Safety authority. | Reporting, moderation, enforcement, appeal, and operator journeys in scope pass permission/state-transition tests and produce reviewable audit evidence. |
| C. Intelligence and Recommendation | Separate package approval, cleared service boundary, stable documented input contracts, consent/privacy decision, and evaluation plan. | Approved quality and privacy evidence, bounded failure/disable behavior, and independent review; no inference from service availability alone. |
| D. Corporate Architecture and Backend | Corporate owners appointed; WZP-CORP-01 business, schema, database, and disposable-test prerequisites closed. | ORG/IAM/GOV package tests and approval evidence pass. Corporate completion is not required for a release that excludes it. |
| E. API and Integration Architecture | Approved consumers, authentication model, versioning policy, security review, and source-baseline evidence. | Contract review and compatibility tests pass for each supported consumer/version. |
| F. Android and iOS Applications | Approved platform assessment and stable backend/API contracts; mobile scope and owners assigned. | Approved device/OS, security, privacy, accessibility, localization, compatibility, and store-readiness evidence for the release inventory. |
| G. Infrastructure and Operations | Release inventory approved and environment access separately authorized. | Environment-specific monitoring, incident, backup/restore, capacity, support, and rollback evidence verified for that inventory. |
| H. Quality, Security and Audit | Package scope, requirements, owners, test identity, and independent reviewers recorded. | Gate 1 evidence exists for each package; Gates 2/3 pass only for their frozen release inventories under the rules in 07. |

~~~mermaid
flowchart LR
    A[Public Knowledge Network] --> E[API and Integration]
    C[Intelligence and Recommendation] --> E
    D[Corporate Architecture and Backend] -. independent unless in beta scope .-> E
    E --> F[Android and iOS]
    A --> H[Quality, Security and Audit]
    B[Trust, Safety and Administration] --> H
    C --> H
    D --> H
    E --> H
    F --> H
    G[Infrastructure and Operations] --> H
~~~
