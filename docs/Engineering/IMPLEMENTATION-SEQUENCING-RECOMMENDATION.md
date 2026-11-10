# Implementation Sequencing Recommendation

**Assessment date:** 2026-11-08
**Authority:** WZ-MASTER-ROADMAP-2027.2. This document recommends sequence only; it authorizes no implementation.

## Recommended sequence

### Gate 0 — Close product and ownership decisions

D01's long-term Knowledge Network / Knowledge Hub direction and website-first, content-first baseline B candidate are **APPROVED WITH CONDITIONS**. Complete the remaining WZP-01 conditions and decisions, including content-only reporting coverage, detailed journey and acceptance outcomes, applicable safety/permission authority, specialist appointments, and a versioned B inventory. B is not frozen. API consumers need decision closure only if a selected capability requires them; public API implementation, native apps, AI and corporate systems are not automatic B prerequisites. Do not let a provisional schedule substitute for these decisions.

### Gate 1 — Make verification safe and reconcile source/schema evidence

WZP-02 is BLOCKED. Before dependent implementation, separate pure tests from DB and shared-storage tests; establish independent fail-closed test-target identity; reconcile the snapshot, baseline migration and migration history; and define safe cleanup/rollback evidence. No production database or migration access is included.

### Gate 2 — Continuous package assurance

Apply WZP-03 to each package with scope-specific syntax, tests, security, user journeys, reviewed diff and independent reviewer. Avoid reliance on aggregate “green” status.

### Possible future runtime corrections outside baseline B

The notification GET mutation and bookmark public-ID route mismatch remain technical findings. Notifications and bookmarks are excluded from the conditionally approved baseline B, so these corrections are not current beta prerequisites. They may be considered only under separate implementation approval or if a later approved release inventory includes the capabilities. D11–D13 remain pending and apply to the relevant separately approved scope.

If authorized after Gates 0–1, a bounded correction package could restore safe HTTP semantics and align bookmark routing with public identifiers without changing architecture:

1. Make GET /notifications read-only; add a dedicated authenticated, rate-limited and CSRF-protected POST action to mark notifications read (or define a narrower item-level operation if product acceptance requires it).
2. Align bookmark route constraints with Writ.public_id and controller lookup.
3. Add isolated route/controller tests for GET non-mutation, unauthorized/CSRF-denied mutations and valid public ID lookup.

This is a possible future package, not an authorized task or baseline B requirement. WZP-01, safe isolated verification, feature-specific decisions, and package approval must be evidenced first. Registration/login validation is a separate possible slice after the owner-approved validation/error policy.

### Parallel work opportunities

- WZP-03 assurance procedure can be prepared alongside decision closure; package implementation still needs approval.
- WZP-API-01 contracts can proceed as design work only if an approved capability needs an API consumer. WZP-MOB-01 assessment is separate programme work; neither public API implementation nor native apps are automatic baseline B prerequisites.
- WZ-CORP documentation review may continue independently when owners are assigned, but corporate runtime is not a public beta prerequisite unless selected in the frozen release inventory.
- Operations evidence planning can be prepared without accessing live infrastructure; environment verification needs separate approval.

Do not artificially parallelize code changes that share session, route, schema or moderation assumptions.

## Canonical roadmap traceability

Statuses below are transcribed from docs/Roadmap/02-WORK-PACKAGE-REGISTER.md at inspection. They are controlled roadmap records, not proof of implementation or deployment. Evidence summaries refer to local source and documentation inspected in this assessment.

| Package | Status | Local evidence and gap | Dependency / acceptance evidence |
|---|---|---|---|
| WZP-00 | EVIDENCE VERIFIED | Ten roadmap docs/index exist; local programme baseline is documentation | Scope limited to roadmap docs and recorded local checks |
| WZP-01 | AWAITING OWNER DECISION | D01 direction is approved with conditions; the B candidate is not frozen as a final implementation inventory. Remaining D01 conditions, other decisions, and specialist appointments are open. | Close applicable conditions; approve journeys/acceptance and safety authority; appoint owners and reviewers; version the candidate inventories |
| WZP-02 | BLOCKED | Source defects and migration/test hazards documented here | WZP-01; safe target identity; isolated regressions and reviewed diff |
| WZP-03 | PLANNED | Standalone tests exist; unsafe tests and no configured CI/tool suite | WZP-01, quality procedure and named independent reviewer |
| WZP-PUB-01 | PLANNED | Writ/profile journeys exist but drafts, media, recovery and acceptance are incomplete | WZP-01/02; approved creation/profile journey evidence |
| WZP-PUB-02 | BLOCKED | Comments/follows exist; Q&A/community and safety integration missing | WZP-01, PUB-01, API-01; multi-role and moderation journey evidence |
| WZP-PUB-03 | AWAITING OWNER DECISION | Basic keyword search/hashtags; no curated graph/provenance contract | Approve taxonomy/relevance; stable metadata and search acceptance |
| WZP-PUB-04 | AWAITING OWNER DECISION | Partial language utilities; no approved locale/device/accessibility matrix | Approve locales/fallback/accessibility; locale and device evidence |
| WZP-TRUST-01 | AWAITING OWNER DECISION | Reporting, enforcement, appeals and authority not established | Approve policy/owners; adversarial and appeal flow tests |
| WZP-TRUST-02 | BLOCKED | No admin shell; trust foundation not implemented | WZP-01, TRUST-01, API-01; scoped operator and audit evidence |
| WZP-API-01 | AWAITING OWNER DECISION | api.php empty; no version/auth/error contract | Approve consumers and auth/version/privacy decisions |
| WZP-API-02 | BLOCKED | No public API implementation | API-01, WZP-02; contract and backward-compatibility tests |
| WZP-INT-00 | REPORTED COMPLETE | Python foundation source exists; classification remains unavailable | Owner report only; status not independently re-certified here |
| WZP-INT-01 | REPORTED COMPLETE | API boundary source exists; no tests re-run in this environment | Owner report only; runtime tests unavailable |
| WZP-INT-02 | AWAITING TECHNICAL VALIDATION | PHP client exists; roadmap keeps validation open | Independent audit closure and reproducible isolated PHP tests |
| WZP-INT-03 | NOT AUTHORIZED | Lab/consent integration not wired to client | Separate explicit authorization; consent and privacy design |
| WZP-INT-04 | NOT AUTHORIZED | Cross-language tests not run here | Separate authorization and isolated local service |
| WZP-INT-05 | NOT AUTHORIZED | No classifier/model; endpoint returns unavailable | Separate authorization, taxonomy/evaluation/privacy gates |
| WZP-INT-06 | PLANNED | No production recommendation | Approved content graph, discovery measures and model governance |
| WZP-CORP-00 | REPORTED COMPLETE | Corporate design docs exist; no runtime schema/module | Design report status only; source remains unimplemented |
| WZP-CORP-01 | BLOCKED | No corporate DB/runtime; schema and test prerequisites unresolved | Owner decisions, schema reconciliation and disposable target |
| WZP-CORP-02 | AWAITING OWNER DECISION | ORG logical/physical proposal only | Workforce/org ownership and assignment authority |
| WZP-CORP-03 | AWAITING OWNER DECISION | IAM proposal only; public auth is separate | IdP, principal lifecycle, scoped-role decisions and security approval |
| WZP-CORP-04 | AWAITING OWNER DECISION | GOV model is proposed only | Approval/delegation/audit owner and policy decisions |
| WZP-CORP-05 | PLANNED | No corporate administrative capability | ORG/IAM/GOV approvals and scoped admin requirements |
| WZP-MOB-01 | AWAITING OWNER DECISION | No mobile project; technology not selected | Approve users, criteria, device matrix and evaluation owners |
| WZP-MOB-02 | BLOCKED | No mobile client or stable public API | MOB-01 decision, API-02 and selected public feature packages |
| WZP-MOB-03 | PLANNED | No device/store/security evidence | Approved platform and device matrix; security/accessibility review |
| WZP-OPS-01 | AWAITING OWNER DECISION | Docs only; infrastructure not inspected | Assign operations owner and approve environment verification scope |
| WZP-AUD-01 | PLANNED | No frozen beta inventory or comprehensive audit evidence | Approved beta scope and Gate 1 package evidence |
| WZP-BETA-01 | NOT AUTHORIZED | No beta authorization or cohort evidence | Gate 2 pass and separate written project-owner approval |
| WZP-AUD-02 | NOT AUTHORIZED | No production audit or launch evidence | Beta exit, Gate 3 audit and separate launch approval |

## Conditional acceptance evidence for possible future notification/bookmark corrections

If notifications or bookmarks are separately approved for implementation or included in a later inventory, the package would require:

- Owner-approved behavior for notification read state and bookmark URL format.
- Test setup proven not to connect to a database or use shared storage, or an explicitly approved disposable target when unavoidable.
- Tests demonstrate GET never mutates; POST requires authenticated session, CSRF and rate policy; invalid public IDs fail safely.
- Relevant PHP syntax, targeted security regressions, user journey, and diff review pass.
- No changes to schema, public/corporate identity, Python service, or unrelated routes.
- Independent reviewer and change authority are named.

This is not a WZP-01 or WZP-02 exit criterion and is not a baseline B beta prerequisite. D11–D13 remain pending until the relevant feature scope is approved.

## Release sequencing boundary

WZP-AUD-01, WZP-BETA-01 and WZP-AUD-02 remain later controls. A local code change or passing isolated test does not approve beta, production access, deployment, or a release.
