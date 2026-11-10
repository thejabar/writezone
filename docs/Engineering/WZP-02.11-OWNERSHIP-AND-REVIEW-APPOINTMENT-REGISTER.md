# WZP-02.11 Ownership and Review Appointment Register

**Programme:** WZ-MASTER-ROADMAP-2027.2
**Date:** 2026-11-09
**Status:** Required roles identified; no new appointments documented
**Authority:** Project Owner

## 1. Role register

| Role ID | Required responsibility | Appointment status | Independence and conflict requirements | Decision or evidence still required |
|---|---|---|---|---|
| PO-ACCEPT | Confirm or revise AP approvals; appoint accountable roles; accept WZP-02 closure. | Project Owner authority is documented in WZP-01.8; individual name is not recorded. | Owner approval is distinct from delivery, Security review, independent verification, and release approval. | Record each decision and appointment explicitly; silence is not approval. |
| WZP02-OWNER | Own the WZP-02 evidence ledger, dependencies, package completion report, and handoff. | UNASSIGNED in the existing register; no appointment in this package. | Must not self-verify security, privacy, safety, or data-integrity claims. | Project Owner appointment with defined authority and scope. |
| AP01-DELIVERY | Lead the bounded Writ/comment authorization subject-contract correction and its regression evidence. | UNASSIGNED. | May implement only after AP-01 approval and prerequisites; cannot be its sole independent reviewer. | Project Owner appointment; confirm exact AP-01 scope and conditions. |
| AP01-SECURITY | Independently assess authorization behavior, default-deny handling, and relevant security regression evidence. | UNASSIGNED. | Must be separate from AP-01 implementation for independent Security acceptance. | Project Owner appointment and recorded review scope. |
| AP03-QA | Lead test classification, isolated storage/session design, runner changes, and reproducible test evidence. | UNASSIGNED. | May deliver the harness but cannot be its sole independent verifier. | Project Owner appointment; confirm AP-03 approval and exact scope. |
| AP03-SECURITY | Review isolation boundaries, cleanup containment, side effects, and the test invocation policy. | UNASSIGNED. | Must independently review AP-03 security/data-protection controls; do not assume the AP-01 reviewer can also fill this role. | Project Owner appointment and explicit conflict/capacity decision if combining roles is proposed. |
| WZP02-VERIFY | Independently repeat the agreed source, test-safety, regression, and package-exit evidence. | UNASSIGNED. | Must be independent of the implementation being verified. Codex cannot independently verify its own changes. | Project Owner appointment with access to exact diffs, commands, environment identity, and acceptance criteria. |

No names, organizations, departments, or delegations are inferred. The existing WZP-02.1 register remains controlling for other specialist roles, including database/migration, product/data, privacy/legal, Trust/Safety, and operations owners.

## 2. Responsibility and approval separation

| Activity | Delivery responsibility | Required independent review | Accountable decision |
|---|---|---|---|
| AP-01 implementation | Appointed AP01-DELIVERY | Appointed AP01-SECURITY; WZP02-VERIFY for package evidence | Project Owner confirms AP-01 scope/activation and accepts the result. |
| AP-03 implementation | Appointed AP03-QA | Appointed AP03-SECURITY; WZP02-VERIFY for package evidence | Project Owner confirms AP-03 scope/activation and accepts the result. |
| WZP-02 closure | Appointed WZP02-OWNER | Appointed WZP02-VERIFY | Project Owner alone records package closure. |

One person may hold multiple roles only if the Project Owner records the proposed combination, independence analysis, conflict-of-interest review, and mitigation. No such combination is approved or assumed here. Delivery, Security review, independent verification, and final acceptance must not collapse into self-review.

## 3. Appointment record fields

Before a required work package starts, record for every appointment:

- Named appointee and appointment date/source.
- Exact package, responsibility, authority, and deliverables.
- Independence and conflict-of-interest assessment.
- Any approved role combination and mitigation.
- Reporting and escalation path.
- Confirmation that the person has the time and access required to perform the role.

Until those fields are completed by the Project Owner, the corresponding role remains **UNASSIGNED** and work requiring it remains blocked.
