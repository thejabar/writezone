# Audit and Release-Gate Framework

**Authority:** WZ-MASTER-ROADMAP-2027.2
**Status:** Required programme controls; no release is authorized here
**Gate owners:** Engineering, Security/Privacy, QA, Operations, and project owner according to decision scope

## Gate 1 — Architecture and engineering assurance

Gate 1 is continuous and applies to every implementation package. It reviews architecture conformance, code quality, PHP/Python syntax as applicable, relevant regression/security tests, database integrity and migrations, API compatibility, user journeys, dependency changes, secrets/logging, accessibility, and the reviewed diff. The reviewer confirms that work stays within the approved package and that failure behavior is understood.

**Pass:** required checks for the package pass; design and implementation agree; no unresolved release-blocking finding remains; evidence and rollback/forward-repair notes are attached.

**Conditional pass:** only bounded, non-blocking Medium or Low findings remain. The independent review lead may recommend it; the accountable domain risk owner and relevant Security, Privacy/Legal, Safety, Data, or Operations authority must approve the exact exception. Record a named remediation owner, dated deadline, mitigation, monitoring, retest evidence, and exact expiry in the governance/exception register. At Gate 1 this permits only continued work within the approved package; it does not establish package acceptance or release clearance. Expiry without verified closure makes the package fail its next gate.

**Fail:** scope is exceeded; a Critical or High finding remains; required evidence is missing; release checks fail; a mandatory decision is absent; or a conditional exception is expired, unowned, or untested.

Gate 1 is not a substitute for Gate 2 or Gate 3.

## Gate 2 — Comprehensive pre-beta audit

The audit begins only after inventory B is frozen and versioned. Audit every included component and safety-critical dependency in B across:

- Website, backend, sessions, authorization, database schema/migrations, APIs, and integrations.
- Intelligence services and model/provider behavior only if included; verify feature disablement and unavailable behavior. Exclusion is not evidence of clearance.
- Corporate capabilities only if included in B; verify WZ-ORG/IAM/GOV approvals and scoped access. Corporate work is not a beta prerequisite when excluded.
- Android/iOS applications only if included in B; review device coverage, API compatibility, secure storage, accessibility, localization, privacy, and store readiness. Mobile release readiness is not a beta prerequisite when mobile is excluded.
- Infrastructure, environment separation, monitoring, incident response, capacity, backups, disaster recovery, restore evidence, and rollback/forward repair.
- Security, privacy, abuse/reporting, moderation, accessibility, performance, data deletion/retention decisions, and end-to-end user journeys.
- If B includes public Writ publishing, audit the WZP-TRUST-01 content-only Writ report and response path, its restricted access, escalation, evidence, support handoff, and operational rehearsal even when WZP-PUB-02 is excluded. If B includes comments/discussions, Q&A, or communities, also audit the separately scoped interaction-reporting and moderation path that depends on WZP-PUB-02.

**Pass:** the frozen B inventory exactly matches the audited asset/dependency inventory; every included item has requirement, test, and security/privacy evidence; no Critical or High finding or mandatory legal/privacy/security/safety decision remains; backup/restore and rollback are rehearsed; owners and support are assigned; exact release-candidate identity is recorded.

**Conditional pass:** only bounded, non-blocking Medium or Low findings qualify under the Gate 1 exception record, with dated remediation, monitoring, retesting, and expiry. The independent audit lead recommends; the accountable risk owner and applicable domain authorities approve. The exception is entered in the current governance register (docs/Roadmap/09) and later mirrored to WZ-GOV if implemented. Conditional pass never authorizes beta; the project owner must separately approve beta in writing.

**Fail:** any Critical or High finding remains; any Critical Security, Privacy, Data Integrity, Legal, or Safety failure is present; audit coverage is incomplete; release identity is unknown; evidence cannot be reproduced; an applicable required decision is absent; or recovery is not viable. These failures cannot be waived by conditional pass.

## Gate 3 — Final production audit

Gate 3 assesses the separately frozen inventory C. It verifies beta finding closure and retests, and fully audits any C-only component that was not in B. Review regression/security results, API/mobile compatibility when included, production configuration ownership, incident response, monitoring/alerting, backup/restore, rollback, privacy/data handling, support readiness, exact commit identity, and the governed release gate.

**Pass:** all Critical/High beta findings are fixed and retested; each C item has complete test and audit evidence; remaining risks are accepted by their domain owners; operational rehearsals pass; deployment and rollback plans reference the exact release; and the project owner separately approves launch.

**Conditional pass:** only bounded, non-blocking Medium or Low findings qualify under the same written exception record, with a remediation owner/deadline, mitigation, monitoring, retest evidence, and exact expiry. The independent audit lead recommends; the accountable risk owner and applicable Security, Privacy/Legal, Safety, Data, or Operations authority approve. Conditional pass never authorizes production or launch; separate written project-owner approval remains mandatory.

**Fail:** any Critical or High finding remains; any Critical Security, Privacy, Data Integrity, Legal, or Safety failure is present; a C-only component lacks full audit; the release artifact differs from the audited commit; a required check or recovery test fails; operational ownership is absent; an exception expired; or the project owner withholds approval. No conditional pass may override the prohibited finding classes.

## Severity and evidence rules

Classify findings by practical impact: critical means likely severe compromise, unauthorized cross-user/corporate access, unrecoverable data loss, or platform-wide safety failure; high means material security/privacy/data-integrity loss or a major journey unavailable; medium and low are bounded issues with workable mitigation. Security/Privacy/QA owners confirm severity; do not lower severity to meet a date.

Every gate record includes: exact B or C inventory/version, audited commit, environment/test identity, checks and results, findings with severity and owner, residual-risk exception ID, mitigation, remediation deadline, retest evidence, expiry date, approving authorities, and decision date. The current record belongs in the programme governance register; future WZ-GOV evidence storage is not implied to exist. Repository or production state not independently accessed is marked unverified. Any expired exception or missing approval changes the gate outcome to Fail until closure is verified.
