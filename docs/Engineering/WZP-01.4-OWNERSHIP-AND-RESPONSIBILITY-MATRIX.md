# WZP-01.4 Ownership and Responsibility Matrix

**Authority:** Project Owner retains programme decision authority

**Assignment state:** Specialist roles are unassigned unless an appointment is recorded in the canonical ownership register. This document makes no appointments.

Use this matrix alongside [WZP-01 Ownership and Approval Matrix](WZP-01-OWNERSHIP-AND-APPROVAL-MATRIX.md), which defines approval separation. A role name is not an appointment. A recorded appointment blocker does not authorize dependent work or prove that a service is operational.

## Role responsibilities and current assignment

| Role | Purpose and responsibilities | Decision authority / independence | Required before | Current assignment | Blocking consequence |
|---|---|---|---|---|---|
| Project Owner | Approve product scope, package boundaries, WZP-01 closure, implementation packages, and separate beta/launch decisions. | Final owner decision authority. This does not appoint delivery or review roles. | WZP-01 closure, each package start, beta, and production launch. | Authority confirmed; individual identity not recorded in the supplied decision. | No final closure/package/release action without an explicit decision. |
| Product decision authority | Define user journeys, remaining registration/error details, acceptance outcomes, and user notices for the selected B surface; carry the approved WZP-01.6 boundaries into package acceptance. | Can recommend product outcomes; Project Owner approves programme scope. | Acceptance specification sign-off and selected feature packages. | UNASSIGNED. | WZP-01.6 resolves PO-01–PO-07, but finer acceptance details and package evidence still require an accountable product authority. |
| Programme/documentation owner | Maintain decision traceability, scope versions, package references, and document indexes. | No authority to change owner decisions or package status. | WZP-01 evidence consolidation and later change control. | UNASSIGNED. | Records require designated review/acceptance; no status inferred from authorship. |
| Technical architecture lead | Check fit with PHP MVC modular monolith, interfaces, backward compatibility, and architecture decisions. | Technical acceptance within approved scope; independent from Project Owner scope approval. | WZP-02 and each implementation package. | UNASSIGNED. | No accountable architecture sign-off. |
| PHP/application delivery owner | Own source analysis, implementation plan, compatibility, syntax, regression and journey evidence for selected PHP work. | Delivery role; cannot independently verify own high-risk changes. | WZP-02 activation and WZP-PUB packages. | UNASSIGNED. | WZP-02 cannot activate without delivery owner and reviewer. |
| Database/migration owner | Reconcile source/schema/migration artifacts, resolve D07 semantics with data owner, and maintain safe migration/test plan. | Does not gain authority to access/write production from role assignment. | Schema decisions or database-backed WZP-02 work. | UNASSIGNED; D07 explicit blocker. | No schema adoption/change or DB-backed test without approved evidence and target. |
| Test and quality lead | Classify tests/side effects; isolate storage; define relevant regression, security and user-journey evidence. | Quality planning; independent verifier remains a separate role for selected work. | WZP-02 and package Gate 1. | UNASSIGNED. | Aggregate runner remains unsafe; evidence matrix has no appointed owner. |
| Independent verifier | Review implementation evidence and diff independently; identify gaps and retest findings. | Must not verify their own implementation or high-risk security/data work. | WZP-02 activation, security-sensitive packages, Gate 2. | UNASSIGNED. | WZP-02 remains blocked; no independent verification claim. |
| Security reviewer | Review auth/session/CSRF, authorization, abuse, secrets, logging, and threat cases. | Independent review of security controls; not interchangeable with product approval. | Auth/content/reporting implementation and beta gate. | UNASSIGNED. | Security acceptance and beta safety evidence remain open. |
| Privacy/legal reviewer | Review personal data, user notices, jurisdiction, privacy purpose, retention and rights as applicable. | Qualified domain review; no legal obligation or retention period inferred here. | Profile/content/report data collection, optional telemetry, beta regions. | UNASSIGNED. | No privacy/legal sign-off or region-specific claim. |
| Trust/Safety authority | Define report taxonomy, triage, escalation, review/action authority, case outcomes, appeals boundary, and user communication. | Owns safety policy; must not inherit platform-wide authority from community roles. | WZP-TRUST-01 implementation and any public beta with Writ publishing. | UNASSIGNED; WZP01-D05 blocker. | Reporting direction is approved but no operational process or authority exists. |
| Support owner | Define report/issue intake, reporter handoff, user support, and escalation channels. | Owns support operating procedure within approved policy. | Before beta operations rehearsal. | UNASSIGNED. | No staffed support claim or beta coverage. |
| Operations/environment owner | Verify local/test and later release environments, monitoring, incident, recovery, backup/restore, and pause/disable capability. | Environment access requires separate authorization; local and hosted state are not assumed equal. | WZP-OPS-01 and beta/production gates. | UNASSIGNED; D10 environment-verification owner blocker remains. | No environment, operational, recovery, or deployment readiness claim. |
| Release authority/operator | Preserve GitHub as release authority and follow approved exact-release process. | GitHub is the documented authority; operator identity/permission is not appointed here. | Any commit, push, merge, or release action. | Authority documented; human operator UNASSIGNED. | No remote action or release is authorized. |
| Beta approver | Decide whether to invite/activate the exact frozen beta cohort after Gate 2 and support/privacy readiness. | Separate written Project Owner approval required. | WZP-BETA-01 only after Gate 2. | Project Owner authority; no beta approval or operator appointment. | Beta is NOT AUTHORIZED. |
| Production launch approver | Approve the exact C inventory after beta exit, Gate 3, and operations evidence. | Separate written Project Owner approval required. | WZP-AUD-02 / launch. | No launch approval or operator appointment. | Production launch remains NOT AUTHORIZED. |

## Assignment and independence record

For each selected package, record a named accountable owner, delivery assignee, independent verifier, approval authority, relevant security/privacy/data/Trust/operations reviewers, conflicts and mitigation, effective date, scope version, and source of appointment. Until then use **UNASSIGNED — BLOCKER**. Do not infer a name or appointment from repository access, prior contribution, job title, or the person requesting work.

### Work-package assignment state

| Package group | Required role assignments | Current state |
|---|---|---|
| WZP-01 closure | Project Owner decision authority; scope/product reviewer; technical and domain reviewers as applicable | Project Owner authority is established; specialist identities remain unassigned. WZP-01 may record permitted blockers but is not closed. |
| WZP-02 | Delivery owner, independent reviewer, QA/security reviewers, DB owner for DB-dependent work | Delivery owner and independent reviewer unassigned; package BLOCKED. |
| WZP-PUB-01 / WZP-PUB-03 | Product/content/search owner, PHP delivery owner, security/privacy/accessibility reviewers, independent verifier | Unassigned; packages are not activated. |
| WZP-TRUST-01 | Trust/Safety authority, support/operations, security/privacy/legal, delivery owner and verifier | Unassigned; no reporting workflow is operational. |
| WZP-OPS-01 / WZP-AUD-01 | Operations owner, QA/security/privacy and independent audit roles | Unassigned; beta gate evidence is not available. |
| WZP-BETA-01 / WZP-AUD-02 | Beta/operations owners, audit reviewers, Project Owner approval | Not authorized; exact scope and operators remain open. |

## Approval separation

1. A Project Owner product decision records scope; it does not implement the feature.
2. A package implementation approval must state exact files, behavior, tests, and risk boundaries.
3. The delivery owner produces evidence; an independent verifier checks it.
4. Domain authorities accept their own security, privacy/legal, Trust/Safety, accessibility, database, and operations controls.
5. GitHub release authority, beta authorization, and production launch authorization remain separate approvals.

Current staffing does not demonstrate operational coverage. A reporting form, draft policy, or named role without an appointed individual and rehearsed procedure is not operational readiness.
