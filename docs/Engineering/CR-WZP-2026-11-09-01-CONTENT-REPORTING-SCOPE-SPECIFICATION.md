# CR-WZP-2026-11-09-01 Content Reporting Scope Specification

**Change request:** CR-WZP-2026-11-09-01

**Decision authority:** Project Owner, 2026-11-09 WZP01-D03

**Status:** Scope/dependency direction recorded as approved with conditions; operating design and implementation NOT APPROVED

**Package:** Existing WZP-TRUST-01; no new package ID

This document elaborates the approved change-control boundary for review. The recorded Project Owner decision authorizes a bounded content-only reporting capability within WZP-TRUST-01, independent of WZP-PUB-02. The approved decision does not authorize implementation, create a staffed operation, approve exact policies or service levels, or change a package status. The canonical change record is in [Roadmap document 09](../Roadmap/09-RISKS-DECISIONS-AND-CHANGE-CONTROL.md); the canonical package graph is [Roadmap document 03](../Roadmap/03-DEPENDENCIES-AND-SEQUENCING.md).

## 1. Approved scope and boundaries

### Approved under WZP01-D03

- A user-accessible way to report a public Writ.
- Restricted receipt and access to reports.
- Categorization and triage, including urgent safety and spam escalation.
- Defined review/action authority.
- Case outcome and evidence recording.
- Appropriate communication with the reporting user.
- Support handoff and operational responsibility.
- Defined access, retention, and audit arrangements.
- The process must be staffed, operational, and evidenced before beta invitations.

### Separate or excluded capabilities

| Capability | Boundary |
|---|---|
| Public Writ reporting | In scope in WZP-TRUST-01. This is the sole D03 content-only path described here. |
| Comment/discussion moderation | Outside B. Requires WZP-PUB-02 interaction scope and the applicable WZP-TRUST-01 path if selected later. |
| Community moderation | Outside B. Community membership/roles and moderation require separately approved scope and authority. |
| Appeals and wider trust capability | Not defined by this content-only approval. Any appeal model or broader enforcement/recognition capability requires a separate policy decision and package-specific acceptance. |
| Automated scoring/penalties | Not authorized by this change request. |

## 2. Proposed operating flow — NOT YET APPROVED

The following is a design proposal to make the approved boundary reviewable. It is not an operating procedure until the required owners approve it and evidence it.

1. A reader opens the report action from a public Writ and receives a clear notice about what information will be submitted and how it may be handled.
2. The system binds the report to the Writ's stable public identifier. Reporter account identity, if any, is derived from the authenticated context; secrets and unnecessary profile data are not copied into the case.
3. Intake validates required fields and creates one uniquely identified case. A repeated submission must not silently create duplicate cases; the idempotency/duplicate policy requires technical design.
4. A restricted triage queue separates urgent safety and spam reports for prompt human review. No response-time target is set in this document.
5. An authorized reviewer records the decision, rationale category, action authority, and evidence reference. Reporter identity and case details remain limited to roles that need them.
6. The case receives an outcome state and, where approved, an appropriate reporter communication and support handoff.
7. The case is closed or escalated under an approved workflow. Audit history records actor, action, timestamp, reason code, and correlation/case ID without copying the full Writ body unnecessarily.

## 3. Reporting entry point and information minimization

The Project Owner prefers an in-product report action on public Writs with a restricted operator workflow. The following fields are a proposal for review, not an approved form contract:

| Candidate field | Purpose | Decision/guardrail |
|---|---|---|
| Writ public ID | Identify the reported object | Required; avoid exposing internal database identifiers in user-facing evidence. |
| Category | Route to an authorized triage path | A final taxonomy requires Trust/Safety approval. Urgent safety and spam escalation are explicitly required. |
| Optional short explanation | Clarify context | Length, optionality, abuse controls, and retention require owner approval. Do not request unrelated personal information. |
| Reporter account reference | Enable response/support where appropriate | Whether authentication is required, anonymous reporting is allowed, and what identifier is stored are UNRESOLVED. Do not copy password, session token, email, or full profile by default. |
| Attachment/evidence upload | Not included in this proposal | Do not add without separate privacy, malware, storage, access, retention, and operational approval. |

The system should retain a Writ reference and minimal case data, not a second copy of the content unless an appointed reviewer approves a defined evidence need. Public notice text and the reporter's communication preference are owner decisions.

## 4. Triage categories and escalation

The approved decision names urgent safety and spam escalation but does not define a full taxonomy. Proposed minimum handling for approval:

- **Urgent safety:** visible priority route and immediate notification to the appointed on-call authority; exact qualifying conditions, channel, coverage, and response target are UNRESOLVED.
- **Spam or manipulation:** distinct queue/tag for review; exact definitions, aggregation, and action rules are UNRESOLVED.
- **Other/unsure:** a fallback route to human triage is a recommendation, not an approved category.

Do not invent legal categories, response deadlines, severity thresholds, staffing capacity, or 24/7 coverage. The Trust/Safety authority must approve category definitions, urgency triggers, escalation contacts, handoff, and monitoring.

## 5. Review authority and permitted actions

WZP01-D03 requires defined authority to review and take action. It does not approve a specific content action catalogue. Before implementation, Trust/Safety, Product, Security, and Privacy/Legal as applicable must define:

- which roles may view, triage, assign, escalate, resolve, or reopen a case;
- whether any temporary restriction/hide state is available during review;
- the approved action set and appeal/communication boundary;
- decision reasons, evidence references, dual review or separation-of-duties needs;
- the rules for reinstatement/correction and reporter notification.

No universal administrator role, community-manager platform authority, automatic penalty, or permanent Writ deletion is approved by this record. Permanent deletion is excluded from baseline B under D02. The current source has no report/operator route in the inspected scope, so no operation can be presumed available.

## 6. Case state and audit proposal

Suggested states for owner review: `received`, `triage`, `urgent_escalation`, `assigned`, `action_pending`, `resolved`, `dismissed`, `escalated`, `reopened`, and `closed`. State names and transition rules remain proposed. The final workflow must define invalid transitions, duplicate submissions, cancellation, reassignment, unresolved-at-shift-handoff, and reopening.

For each decision, retain a minimal audit event with case ID, actor identity/role, action, timestamp, approved reason code, prior/new state, correlation ID, and evidence reference. Restrict audit access. Tamper-evidence, export, and retention design need Security/Operations/Privacy decisions. Never log authentication secrets or unrestricted user content as convenience diagnostics.

## 7. Privacy, retention, and access decisions

The following values are open and must be set by appointed authorities before the case flow is operational:

- Reporter eligibility and whether anonymous reports are accepted.
- Exact stored fields, content snapshots, access roles, and escalation destinations.
- Retention period, deletion/archival, legal hold, and correction process.
- Reporter notice and communication channel/content.
- Jurisdiction, privacy/legal review, and any regional data handling constraints.
- Support handoff, working coverage, and on-call schedule.
- Audit evidence custody, access review, and incident reporting.

No period, jurisdiction, legal obligation, response-time commitment, or staffed capacity is invented here. Apply data minimization and restricted access by default while waiting for approved policies; this principle alone does not establish operational readiness.

## 8. Dependencies and exact roadmap amendments

The 2026-11-09 Project Owner decision and CR record already authorize the content-only package boundary. This WZP-01.4 task does **not** re-implement the change or edit the canonical dependency graph.

The approved/current graph amendment is:

1. WZP-TRUST-01 retains WZP-01 as a mandatory prerequisite.
2. WZP-PUB-01 is a conditional integration prerequisite for the public Writ report action.
3. WZP-PUB-02 is conditional only for discussion/community interaction moderation.
4. WZP-TRUST-01 content-only reporting and operational evidence are mandatory before beta whenever public Writ publishing is in frozen B, even when WZP-PUB-02 is excluded.
5. Interaction moderation is required only when interaction capability is separately selected.
6. WZP-OPS-01 and WZP-AUD-01 assess the selected B path; WZP-BETA-01 remains a separate, NOT AUTHORIZED approval.

These amendments are recorded in the existing [package register](../Roadmap/02-WORK-PACKAGE-REGISTER.md), [dependency graph](../Roadmap/03-DEPENDENCIES-AND-SEQUENCING.md), [Gate 2 criteria](../Roadmap/07-AUDIT-AND-RELEASE-GATES.md), and [beta governance](../Roadmap/08-BETA-AND-LAUNCH-GOVERNANCE.md). Existing package IDs and recorded statuses are unchanged. Current WZP-TRUST-01 status remains AWAITING OWNER DECISION because owners and operational process are absent.

## 9. Pre-beta acceptance evidence

Before any beta invitation that includes public Writ publishing, the exact frozen build/inventory must demonstrate:

- report access from each public Writ and safe behavior for signed-in/anonymous states as approved;
- validated intake and a unique report/case reference;
- restricted reviewer and support access, with denied-access evidence;
- category/triage, urgent safety and spam escalation rehearsed with assigned staff;
- approved action authority, case outcomes, reporter communication, and handoff;
- audit evidence for receipt, access, transitions, and decisions;
- approved minimization, retention, and privacy notice;
- operational coverage, pause/disable route, incident handling, and support ownership;
- independent review of security, privacy, and user journeys;
- successful Gate 2 audit and separate Project Owner beta authorization.

No operational procedure, staff appointment, runtime endpoint, test result, or live deployment was inspected or established by this specification.
