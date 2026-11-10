# WZP-01.4 Decision and Blocker Dashboard

**Snapshot date:** 2026-11-09

**Current decision basis:** WZP-01.6 Project Owner decision dated 2026-11-09; original WZP01-D01–D14 preserved
**Overall:** WZP-01 OPEN / AWAITING OWNER DECISION; WZP-02 BLOCKED

This dashboard summarizes the controlling owner decision, current evidence, and remaining blockers. The [decision register](WZP-01-DECISION-REGISTER.md), [roadmap package register](../Roadmap/02-WORK-PACKAGE-REGISTER.md), and [dependency graph](../Roadmap/03-DEPENDENCIES-AND-SEQUENCING.md) remain the detailed authorities for their respective records.

## Executive status

| Area | Decision status | Documentation status | Implementation status | Independent verification | Operational readiness |
|---|---|---|---|---|---|
| D01-D14 and PO-01–PO-07 | D01-D14 preserved; WZP-01.6 approves all seven WZP-01.5 proposals with stated conditions | WZP-01.6 decision and reconciliation records exist | No implementation authority granted | No runtime or journey verification performed | Not applicable to decision recording |
| Baseline B | Boundaries approved for candidate closure review; NOT FROZEN | Exact WZP-01.4 candidate artifacts accepted and content identities recorded; WZP-01 closure remains separate | NOT AUTHORISED | No user journeys tested | Not ready for beta |
| Content-only reporting CR | Scope/dependency split recorded with conditions | CR specification prepared; operating values open | NOT AUTHORISED; no report workflow found | No operational/security audit | Not operational |
| WZP-01 | OPEN / AWAITING OWNER DECISION | PO-01–PO-07 resolved; candidate identity recorded; exit evidence still requires final reconciliation and a separate closure decision | Not authorized | No final closure verification | Not applicable |
| WZP-02 | BLOCKED | Entry/exit criteria documented | Not activated | No WZP-02 evidence | Not applicable |
| Beta | NOT AUTHORISED | Gate 2 requirements documented | No invitations/activation | No Gate 2 audit | Not ready |
| Production | NOT AUTHORISED | Gate 3 requirements documented | No deployment/launch | No Gate 3 audit | Not ready |

## Approved decisions and explicit blockers

| Decision | Current disposition | Consequence |
|---|---|---|
| D01 | APPROVED WITH CONDITIONS | A direction and B candidate retained; B not frozen and C is a later separate inventory. |
| D02 / PO-02–PO-07 | APPROVED WITH DEFINED BOUNDARIES; clarifications approved | Public profile/Writ visibility, recovery exclusion, 1,000-code-point Writ limit with technical check, three keyword result groups/newest-first, FeedInspector exclusion, and PUB-01 accessibility/browser boundary are recorded in WZP-01.6. |
| D03 | APPROVED WITH CONDITIONS | Content-only reporting lives in TRUST-01 independently of PUB-02; staffed process/evidence required before beta. |
| D04 | APPROVED | Qualitative task rubric; behavioral analytics excluded, optional telemetry disabled unless separately approved. |
| D05 | APPROVED WITH EXPLICIT APPOINTMENT BLOCKERS | Project Owner retains authority; no specialist person has been appointed. |
| D06 | APPROVED | `database/schema/live-schema.sql` is the documented reference; migration chain is executable history. No live DB parity asserted. |
| D07 | EXPLICIT BLOCKER RECORDED | Schema differences and database/data resolver remain unassigned; no semantic chosen. |
| D08/D09 | APPROVED | Fail-closed target and safe-isolation policies approved; implementation/verification still required before DB-backed tests. |
| D10 | EXPLICIT BLOCKER RECORDED | PHP/Composer environment verification owner unassigned; no provisioning allowed by the decision. |
| D11-D13 | PENDING / DEFERRED | Notifications/bookmarks excluded from B; resolve only for separate scope. |
| D14 | APPROVED BASELINE | Scope, implementation, release, beta, and production approvals remain separate. |

## Unassigned roles

Product decision authority, programme/documentation owner, technical architecture lead, PHP delivery owner, database/migration owner, QA lead, independent verifier, security reviewer, privacy/legal reviewer, Trust/Safety authority, support owner, operations/environment owner, and release operator are unassigned. The Project Owner remains the authority; no unassigned role is inferred from contributors or repository access.

## Pending evidence and package-change state

- CR-WZP-2026-11-09-01 is recorded in Roadmap document 09 and reflected in the existing package register/graph. This WZP-01.4 package does not implement or reapply that change.
- Package/dependency reconciliation confirms TRUST-01 content reporting independent of PUB-02; PUB-01 integration and PUB-02 interaction moderation remain conditional as documented.
- WZP-PUB-03 has a mandatory WZP-API-01 dependency in the current graph. This is not API-02 endpoint implementation.
- Existing source confirms relevant routes/views, but server validation, storage compatibility, duplicate-safe publish, responsive/accessibility journeys, and reporting operations are not accepted evidence. Search groups/order and the listed visibility boundaries are owner-approved; runtime acceptance remains unverified.
- The PHP aggregate suite has known DB/shared-storage side effects. No tests were run. WZP-02 must establish safe test isolation and independent target validation under separate approval.

## Release conditions

1. Preserve the WZP-01.6 identity record for the exact candidate scope inventory and acceptance specification reviewed by the Project Owner.
2. Complete and reconcile applicable WZP-01 exit evidence, including remaining technical and specialist blockers; obtain a separate written WZP-01 closure decision.
3. WZP-02 remains blocked until formal WZP-01 closure, separate WZP-02 approval, exact scope, delivery owner, independent reviewer, and verified safe test boundaries are recorded.
4. Before Gate 2: WZP-02 exit evidence, frozen B, operational content-only reporting if publishing is included, WZP-OPS-01 readiness, and WZP-AUD-01 pass.
5. Beta invitations require separate written Project Owner authorization. Production needs separate C freeze, Gate 3, and launch authorization.

No implementation, database operation, software provisioning, test execution with side effects, beta invitation, commit, push, merge, deployment, or production operation is authorized by this dashboard.
