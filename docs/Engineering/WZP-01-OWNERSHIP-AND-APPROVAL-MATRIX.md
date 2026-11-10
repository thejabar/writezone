# WZP-01 Ownership and Approval Matrix

**Date:** 2026-11-09
**Status:** Project Owner decisions recorded; specialist appointments remain unassigned and are explicit blockers
**Authority:** WZ-MASTER-ROADMAP-2027.2 and docs/ARCHITECTURE_AUTHORITY.md

## Ownership principles

- Assign responsibilities to roles first; appoint people only through explicit project-owner decision.
- An unassigned role remains a blocker for any package requiring that authority.
- The person implementing a change must not be its sole independent security, privacy, data-integrity or release verifier.
- Approval of a design, implementation package, GitHub release, beta and launch are separate actions.
- GitHub is the release authority. This does not identify an operator, authorize remote Git operations, or replace the project owner's separate beta/launch decisions.
- WZP01-D05 records outstanding specialist appointments as blockers; it appoints no individual. Package activation remains blocked where its required owner or reviewer is unassigned, except for the exact initial local, read-only WZP-02 verification expressly authorized by WZP-01.8. That exception appoints neither an owner nor an independent reviewer and does not clear WZP-02 exit or later work.

## Responsibility and approval matrix

| Responsibility | Required accountability | Current appointment | Decision / approval rights | WZP-01 / WZP-02 consequence |
|---|---|---|---|---|
| Project owner | Own product scope, approve package boundaries and resolve escalations | Authority confirmed by decisions dated 2026-11-08 and 2026-11-09; individual identity not recorded | Approves A/B/C inventories, WZP-01 closure, package approvals and separate beta/launch decisions | WZP-01 is closed with conditions under WZP-01.8; WZP-02 has only the initial read-only authorization; specialist appointments and downstream blockers remain |
| Product decision authority | Define knowledge journeys, content behavior and acceptance | **UNASSIGNED**; the Project Owner approved baseline B boundaries and qualitative approach | Recommends subsequent product detail; Project Owner approves scope and unresolved policy | D02/D04 direction is recorded; detailed package acceptance and journey evidence remain future work |
| Programme/decision register owner | Maintain versioned decisions, dependencies and status evidence | **UNASSIGNED** | Records decisions, does not grant product/security authority | WZP-01 documents must be reviewed against roadmap |
| Technical architecture responsibility | Keep PHP MVC boundaries and existing architecture; identify compatibility impacts | **UNASSIGNED** | Recommends technical options; project owner approves architecture exceptions | Required for source/schema reconciliation and first implementation plan |
| Application engineering lead | Review route/controller/model behavior and deliver bounded PHP work | **UNASSIGNED** | Proposes implementation and test design; cannot self-approve security-critical changes | First correction needs assigned delivery owner |
| Database/migration responsibility | Reconcile snapshot, baseline and migration history; define migration/test safety | **UNASSIGNED**; D07 blocker explicitly recorded | Proposes schema semantics and data-preserving path; cannot declare live state without evidence | D06 artifact distinction is approved; D07 semantics and all database activity remain blocked |
| Security responsibility | Review authentication, CSRF, authorization, test-target policy and secrets | **UNASSIGNED** | Reviews/accepts controls within delegated authority; no appointment or privilege is inferred | D08 target policy and D09 test policy are approved; implementation and independent review remain required before DB testing |
| Test/quality responsibility | Classify test side effects, isolate execution and record reproducible evidence | **UNASSIGNED** | Defines evidence against approved acceptance; cannot waive product decisions | D09 invocation policy is approved; aggregate runner remains unsafe until containment is evidenced and independently verified |
| Independent verifier | Review actual diff and repeat the applicable checks independently | **UNASSIGNED** | Provides independent verification, not product or release approval | Must be distinct from implementer for security/data-integrity claims |
| Trust/Safety authority | Set report, triage, moderation, enforcement and appeal decision rights | **UNASSIGNED**; D03 content-only scope approved with conditions | Owns policy and operational decisions within delegated authority | CR-WZP-2026-11-09-01 package alignment is recorded; the content-report process must be assigned, staffed and evidenced before beta |
| Privacy/legal reviewer | Assess consent, user data, telemetry and jurisdiction-specific requirements | **UNASSIGNED** | Qualified review for applicable legal/privacy decisions | Behavioural analytics are excluded from B; optional telemetry stays disabled without separate review |
| Operations/environment owner | Establish accessible local test environment and later operational evidence | **UNASSIGNED**; D10 environment verification blocker recorded | Approves operational procedures within delegated authority; no live access implied | Read-only PHP/Composer verification requires an assigned owner; beta operations still need named operational coverage |
| Release authority | Maintain authoritative release state in GitHub under approved process | GitHub is the documented authority; human operator **UNASSIGNED** | Release action follows separate explicit authorization and repository process | No commit/push/merge/deploy action is part of WZP-01/02 |

## Decision-to-authority mapping

| Decision area | Proposed decision owner | Required approver(s) | Current state |
|---|---|---|---|
| Programme and release scope | Product/programme lead | Project Owner authority confirmed; affected domain owners still required | Product/programme lead unassigned |
| Product journeys and acceptance | Project Owner approved WZP01-D02/D04 boundaries; ongoing product authority | Project owner; security/privacy/accessibility/operations as applicable | Baseline B direction approved; product authority role remains unassigned for subsequent package work |
| Content-only reporting and safety | Project Owner approved WZP01-D03 with conditions | Project owner; appointed Trust/Safety, security and applicable privacy/legal/operations authorities | Scope and CR/package alignment recorded; no operator or reviewer appointed; pre-beta operational evidence remains |
| Package appointments | Project owner | Project Owner authority confirmed; individual role appointments remain outstanding | WZP01-D05 records explicit blockers; no individual is appointed |
| Schema and migration authority | Project Owner approved WZP01-D06 artifact distinction; database/migration lead for reconciliation | Project owner; database/data authority for semantic decisions | D06 approved; D07 resolver/data-owner blocker remains |
| Test-target identity | Security and database leads | Project Owner approved WZP01-D08 policy; security reviewer for implementation | Policy approved; target identity, implementation and independent verification are not established |
| Test isolation | Test/quality lead | Project Owner approved WZP01-D09 policy; QA/security reviewer for implementation | Policy approved; isolation and safe invocation evidence remain outstanding |
| Notification behavior | Public product owner | Project owner and security reviewer | Unassigned |
| Bookmark identifier behavior | Application lead | Product owner and security reviewer | Unassigned |
| PHP environment | Engineering/platform lead | Project Owner approved read-only verification boundary; provisioning separately approved if needed | WZP01-D10 verification owner is an explicit blocker; no environment changes are authorized |

## Appointment record required for selected packages

For every package selected into the first approved release scope, record:

1. Accountable owner.
2. Technical delivery assignee.
3. Independent verifier.
4. Approval authority and decision boundary.
5. Security/privacy/data/operations reviewers required for the package.
6. Conflicts of interest or role combinations and their mitigation.
7. Date, scope version and source of appointment.

If any role is unassigned, state the exact blocker. Do not fill a gap with a presumed department, a previous contributor, or the person requesting the work.

## Separation of approvals

1. **Decision approval:** approves a product, technical, security or ownership choice.
2. **Package implementation approval:** authorizes a named work package and exact scope.
3. **Independent verification:** establishes evidence for that package; it does not authorize broader work.
4. **GitHub release authority:** identifies the authoritative source/release process; it does not imply permission to push or deploy.
5. **Beta or launch approval:** project-owner decision after applicable audit gates; separate from all items above.

This matrix appoints no people. The Project Owner decisions dated 2026-11-08 and 2026-11-09 confirm authority but do not identify the individual or appoint specialists. WZP01-D05 records unassigned roles as blockers. WZP-01 is **CLOSED WITH CONDITIONS** under WZP-01.8. That decision expressly authorizes the initial local, read-only WZP-02 inspection without appointing its delivery owner or independent reviewer. Independent verification, WZP-02 exit, unsafe or database-dependent tests, and all subsequent implementation still require the appropriate assignments, evidence, and separate approval.
