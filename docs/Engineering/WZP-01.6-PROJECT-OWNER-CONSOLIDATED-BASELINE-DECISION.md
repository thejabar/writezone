# WZP-01.6 — Project Owner Consolidated Baseline Decision

**Programme:** WZ-MASTER-ROADMAP-2027.2

**Decision date:** 9 November 2026

**Authority:** Project Owner

**Decision status:** APPROVED WITH CONDITIONS

**Baseline status:** BOUNDARIES APPROVED; BASELINE B NOT FROZEN

## 1. Decision

The Project Owner approves all seven proposed WZP-01.5 decisions, subject to the conditions recorded below.

The WZP-01.4 scope inventories and Baseline B Acceptance Specification are accepted as the candidate documentation for WZP-01 closure review. This acceptance does not freeze Baseline B, approve implementation, close WZP-01, activate WZP-02, or authorise beta or production release.

## 2. Individual decisions

### PO-01 — Candidate documentation

**APPROVED WITH CONDITIONS**

Accept the A/B/C scope inventories and Baseline B Acceptance Specification as the documented candidate for WZP-01 closure review.

The exact reviewed document contents and revision identity must be recorded. Baseline B remains unfrozen until its separate release-gate freeze.

### PO-02 — Profile and Writ visibility

**APPROVED**

- Public profiles expose the public handle and approved display name.
- Public Writ attribution identifies the author.
- Writs are publicly visible when published.
- Private and unlisted visibility modes are excluded from Baseline B.
- Follow controls and follower counts are excluded.

### PO-03 — Account recovery

**APPROVED WITH CONDITIONS**

- Self-service password reset is excluded from Baseline B.
- The platform must not promise an unavailable recovery facility.
- An appointed support owner must define and approve any support-assisted recovery process before beta.

### PO-04 — Writ text limit

**APPROVED WITH TECHNICAL CONDITION**

Adopt a maximum of 1,000 UTF-8 Unicode code points after trimming whitespace.

Before implementation, verify compatibility with the actual storage model, validation path and database constraints. Do not treat the existing browser-side character limit as server-side enforcement.

### PO-05 — Keyword discovery

**APPROVED**

Retain these three discovery result groups:

- Public users.
- Public Writs.
- Hashtags.

Writ results are ordered newest-first. Discovery remains non-AI keyword search without personalisation, semantic search or recommendation claims.

The mandatory WZP-PUB-03 to WZP-API-01 dependency remains unchanged. This does not authorise public API endpoint implementation.

### PO-06 — FeedInspector

**APPROVED FOR EXCLUSION**

Exclude the user-visible FeedInspector/Intelligence panel from Baseline B.

Do not present local ranking scores, confidence indicators or ranking signals as evidence of an operational AI capability.

### PO-07 — Accessibility and package boundary

**APPROVED WITH CONDITIONS**

- Minimum observable accessibility and responsive-browser acceptance are cross-cutting requirements of WZP-PUB-01.
- The applicable browser and viewport matrix must be documented.
- Accessibility checks must cover the approved B journeys.
- Full multilingual/global and native-platform requirements remain outside Baseline B and within their separately governed future scope.
- Do not add or remove package dependency edges without formal change control.

## 3. Existing decisions preserved

This decision does not reopen or replace WZP01-D01 through D14.

In particular:

- D01: long-term Knowledge Network direction and website-first candidate remain approved.
- D02: broad Baseline B journeys remain approved within the newly clarified boundaries.
- D03: content-only Writ reporting remains approved under CR-WZP-2026-11-09-01.
- D04: qualitative acceptance remains approved; behavioural analytics remain excluded.
- D05: specialist appointment blockers remain.
- D06: documented schema reference and executable migration history remain distinct.
- D07: schema differences remain unresolved blockers.
- D08 and D09: approved database-target and test-isolation policies remain in force.
- D10: PHP/Composer environment verification remains blocked pending an appointed owner.
- D11-D13: notifications and bookmarks remain excluded and deferred.
- D14: decision approval, implementation approval, GitHub release, beta and production approval remain separate.

## 4. Problem-solving authority

The Project Owner authorises Codex to make its best reasonable effort to complete the WZP-01.6 documentation and reconciliation task, including investigating obstacles arising from earlier documentation, repository inconsistencies, incomplete source material or existing codebase conditions.

Codex should not abandon an authorised task merely because a previous artefact is incomplete or a source discrepancy exists.

It should:

- Inspect relevant source and documentation.
- Trace inconsistencies to their actual cause.
- Reconcile documents against controlling decisions.
- Correct factual documentation errors within the authorised scope.
- Use safe, non-destructive workarounds where appropriate.
- Record unresolved technical issues accurately.
- Explain any action that cannot safely be completed within the current authority.

This authority does not permit deletion or loss of existing user work, destructive database operations, credential exposure, production changes, deployment, or implementation outside the approved package.

Any high-risk or irreversible action requiring separate approval must be identified before execution.

## 5. Closure and implementation boundary

WZP-01 remains open until its formal exit evidence has been reconciled and a separate Project Owner closure decision has been recorded.

WZP-02 remains blocked until WZP-01 is formally closed and WZP-02 receives separate approval, an exact authorised scope, an appointed delivery owner and independent reviewer, and verified safe test boundaries.

Content-only Writ reporting must be implemented, staffed, operationally tested and independently verified before beta.

No beta, production or public launch authority is granted by this decision.

**Final disposition:** All seven WZP-01.6 decisions approved with their stated conditions. Baseline B boundaries approved but not frozen. WZP-01 open. WZP-02 blocked.

## 6. Reviewed document identity

The SHA-256 values below identify the exact local document bytes accepted as the WZP-01.4 candidate under PO-01. The files were uncommitted working-tree documents, so no Git commit identity is claimed. These fingerprints identify the reviewed content and are not a release or implementation approval.

| Accepted candidate document | Size in bytes | SHA-256 |
|---|---:|---|
| `docs/Engineering/WZP-01-BASELINE-SCOPE-INVENTORIES.md` | 14,180 | `D32377640D2837C2665574EE0F03DEC213B66DF4936DCE0158088702FB3A788C` |
| `docs/Engineering/WZP-01-BASELINE-B-ACCEPTANCE-SPECIFICATION.md` | 16,528 | `5F363927DCC0EADF66F11CB35B128738CA71BC16FC79E5AD2BD6A3B4AC97F799` |

The WZP-01.6 disposition of the options proposed in WZP-01.5, remaining evidence, and unchanged work-package status are summarized in [WZP-01.6 Decision Reconciliation and Closure Status](WZP-01.6-DECISION-RECONCILIATION-AND-CLOSURE-STATUS.md).
