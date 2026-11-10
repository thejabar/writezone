# WZP-02.1 Ownership and Independent-Verification Register

**Status:** Role register; no appointments made
**Authority:** Project Owner appoints individuals and approves scope; technical evidence remains distinct from owner approval

## Required roles

| Role | Why required / controls | Current assignment | Can combine? / conflict restrictions | Work blocked while unassigned |
|---|---|---|---|---|
| Project Owner | Approves scope, package activation, decisions, risk acceptance, beta/launch separately. | Authority confirmed in WZP-01.8; individual name not recorded. | Cannot be inferred as delivery owner or independent verifier. Product, implementation, release and beta approvals stay distinct. | Decisions that require owner approval and any scope expansion. |
| WZP-02 engineering work-package owner | Accountable for evidence ledger, dependency closure, exact implementation scope and completion report. | UNASSIGNED. | May be an engineering lead if appointed; cannot self-verify own security/data-integrity work. | WZP-02 exit and follow-on implementation packages. |
| Application/PHP engineering lead | Owns PHP MVC implementation proposal, route/controller/model compatibility, regression coverage. | UNASSIGNED. | May also own a bounded implementation; must not be sole independent verifier of own code. | Authorization and registration corrections. |
| PHP/runtime environment owner | Verifies current PHP/Composer/extension versions and repository access; manages any approved environment action. | UNASSIGNED; Windows PHP/Composer not found and WSL access returned E_ACCESSDENIED. | May be the PHP lead if Project Owner accepts operational separation; cannot provision beyond approval. | Syntax/test evidence and runtime implementation verification. |
| Database/migration owner | Reconciles snapshot, baseline and migration history; establishes safe target and data-preserving migration plan. | UNASSIGNED; D07 remains unresolved. | A source analyst may prepare evidence; an implementer cannot alone approve destructive/data-integrity decisions. | Schema decisions, DB integration tests and migrations. |
| Application data/product owner | Decides user-handle, actor/null, identity and notification semantics within approved product scope. | UNASSIGNED. | Can advise the database owner; Project Owner retains baseline/scope authority. | D07 semantic closure and product-data choices. |
| Security reviewer | Reviews authorization, authentication, test-target allowlist, privacy boundaries and failure behavior. | UNASSIGNED. | Must be separate from implementer for security-critical acceptance; may advise on code design but not self-certify. | Security approval and independent validation of security controls. |
| QA/test-isolation lead | Classifies tests, designs layered invocation/cleanup, records reproducible results. | UNASSIGNED. | May implement test harness; independent verifier must separately review it. | Safe regression/aggregate execution. |
| Privacy/legal reviewer | Determines applicable data minimization, consent, retention, identity and support-handling requirements. | UNASSIGNED. | Qualified review where legal/privacy questions arise; cannot be substituted by technical inference. | Account-data, reporting and beta privacy decisions. |
| Trust/Safety authority | Owns content-only reporting categories, triage, escalation, action authority and case outcomes. | UNASSIGNED. | Operational delivery may be separate; decision rights and staff coverage must be recorded. | Operational reporting implementation and pre-beta safety readiness. |
| Support/Operations owner | Defines assisted recovery, support handoff, capacity, incident response and operational rehearsal. | UNASSIGNED. | May combine only with explicit authority/capacity; cannot replace Trust/Safety decisions or independent audit. | Recovery and beta operational readiness. |
| Independent verifier | Repeats applicable source/test/security/database evidence against exact diff/environment; records findings. | UNASSIGNED. | Must be distinct from implementer for security/privacy/safety/data-integrity/release claims. Do not assume Codex is independent. | Independent WZP-02 exit and implementation acceptance. |

No person, organization, department, or delegation is invented by this register. E-08 acceptance under WZP-01.8 is documentary only and does not waive appointment-time conflict review.

## Responsibility and handoff matrix

| Activity | Responsible role | Accountable approval | Independent evidence |
|---|---|---|---|
| Authorization correction | PHP/application engineer | Project Owner for exact package; Security reviewer for control design | Separate reviewer reproduces owner/non-owner and no-write-on-denial cases. |
| PHP/Composer verification | Environment owner | Project Owner for environment scope; administrator approval only if separately required | Independent repeat of version/path/extension checks. |
| Test isolation and runner safety | QA/test lead | Project Owner; Security and database authority for target controls | Reviewer inspects containment and call order; reruns approved safe layers. |
| Registration rules | Product owner proposes field policy; PHP engineer implements | Project Owner; Security/privacy as applicable | Reviewer checks validation, enumeration, duplicate races, session, and privacy evidence. |
| Schema semantics/migration | Database/migration owner with application data owner | Project Owner; separate DB authorization for connection/write; Security review for test target | Independent target identity and migration rehearsal evidence. |
| Notifications/bookmarks | Product owner plus application engineer | Separate Project Owner scope and implementation decisions; Security reviewer | Independent tests; remain excluded from B until separately changed. |
| Content-only reporting | Trust/Safety and Operations | Project Owner scope already recorded; domain authorities approve operating policy | Independent rehearsal and access/retention review before beta. |
| WZP-02 exit | WZP-02 owner | Project Owner accepts package result | Independently appointed reviewer provides evidence; no completion claim from this plan. |

## Appointment record required before implementation

For every approved unit, record the individual's appointment source/date, exact authority, delivery owner, security/data/privacy/operations reviewers, independent verifier, role combinations, conflict check and mitigation, and escalation path. A package must not begin if a required approver/reviewer is unassigned. An implementer cannot be their own sole independent reviewer.
