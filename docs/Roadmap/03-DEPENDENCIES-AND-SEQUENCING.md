# Dependencies and Sequencing

**Authority:** WZ-MASTER-ROADMAP-2027.2
**Dependency authority:** The register and graph in this document are the single authoritative package dependency model. Package status remains controlled by docs/Roadmap/02-WORK-PACKAGE-REGISTER.md; dates and status labels do not create dependency edges.

**WZP-01.8/WZP-02.1 status note (2026-11-09):** The mandatory WZP-01 → WZP-02 dependency edge is unchanged. WZP-01.8 authorized the bounded initial local, read-only WZP-02 verification; the later WZP-02.1 command authorizes only remediation-planning documents and safe documentation validation. Neither action satisfies WZP-02 exit, appoints an independent reviewer, clears database/test-safety gates, or authorizes runtime implementation or release.

## Graph semantics and scope gates

A mandatory prerequisite must complete before the dependent package starts. A conditional prerequisite applies only when the consuming API, feature, or release inventory includes that capability. Decision, consent, environment, and approval prerequisites are recorded separately from package IDs. The complete graph is the union of all conditional edges; each approved release graph is the applicable subgraph.

Release inventories are distinct: A, the complete programme; B, public beta; and C, initial production. B and C are separately approved and frozen. A package excluded from B or C is not a dependency or cleared for that release. See the scope definitions in the master README.

## Authoritative package dependency register

| Package | Mandatory package prerequisites | Conditional package prerequisites | Reason and non-package entry evidence |
|---|---|---|---|
| WZP-00 | — | — | Programme-baseline documentation package; inputs are the owner command and governing repository documents. |
| WZP-01 | WZP-00 | — | Uses the baseline to close scope, shared policy/permission design, decisions, and owner appointments. |
| WZP-02 | WZP-01 | — | WZP-01.8 authorizes the initial bounded read-only verification against the approved candidate scope; package exit and database verification still require their evidence, assignments, and separate approvals. |
| WZP-03 | WZP-01 | — | Gate 1 controls use the approved scope, status evidence, and reviewer assignment model. |
| WZP-PUB-01 | WZP-01, WZP-02 | — | Creation/profile work needs approved journeys and a reconciled source baseline. |
| WZP-API-01 | WZP-01, WZP-02 | — | Contracts need approved consumers and current route/model evidence. |
| WZP-PUB-02 | WZP-01, WZP-02, WZP-PUB-01, WZP-API-01 | — | Uses WZP-01 shared permission, reporting, and moderation-authority design; builds participation after creation/profile foundations. It does not wait on WZP-TRUST-01 implementation. |
| WZP-PUB-03 | WZP-01, WZP-02, WZP-PUB-01, WZP-API-01 | — | Non-AI content identifiers, metadata, explicit links, provenance, basic search, and discovery need stable content and contract foundations. No intelligence prerequisite. |
| WZP-PUB-04 | WZP-01, WZP-API-01 | — | Locale and accessibility requirements must be approved and represented in shared contracts. Mobile assessment remains parallel. |
| WZP-TRUST-01 | WZP-01 | WZP-PUB-01 for public Writ report-action integration; WZP-PUB-02 for discussion/community moderation integration | Under CR-WZP-2026-11-09-01, the bounded public Writ reporting slice approved by WZP01-D03 is independent of PUB-02 and is a safety dependency whenever public Writ publishing is in B. Interaction moderation remains conditional on PUB-02. |
| WZP-TRUST-02 | WZP-01, WZP-TRUST-01, WZP-API-01 | WZP-CORP-03, only if corporate operators are in scope | Administrative controls require the trust workflows and API/security design. The identity authority for any operator must be explicitly decided. |
| WZP-API-02 | WZP-API-01, WZP-02 | WZP-PUB-01, WZP-PUB-02, WZP-PUB-03, WZP-PUB-04, WZP-TRUST-01, WZP-TRUST-02, WZP-CORP-05, only for approved endpoints from those domains | Implements only reviewed contracts and domain behavior. It must not be a prerequisite of the domain packages that supply its behavior. |
| WZP-INT-00 | — | — | Pre-existing WP5 foundation; status is owner-reported. No earlier package in this roadmap is claimed as its historical prerequisite. |
| WZP-INT-01 | WZP-INT-00 | — | WP6A boundary work follows the Python foundation. |
| WZP-INT-02 | WZP-INT-01 | — | PHP client verification follows the Python security boundary. |
| WZP-INT-03 | WZP-INT-02 | — | Lab/consent integration cannot precede client boundary validation and separate consent approval. |
| WZP-INT-04 | WZP-INT-02, WZP-INT-03 | — | Cross-language evidence requires both bounded client and approved Lab contract. |
| WZP-INT-05 | WZP-INT-04, WZP-PUB-03 | — | Classification is an advanced consumer of the stable non-AI knowledge/content interface and approved integration contract. |
| WZP-INT-06 | WZP-PUB-03, WZP-API-01 | WZP-INT-05, only if the approved recommendation consumes classifier output | Recommendations consume stable discovery contracts. Separate quality, privacy, and product approval is mandatory. |
| WZP-CORP-00 | — | — | Accepted WP1.5.2.1 documentation is a proposed design input, not runtime authority. |
| WZP-CORP-01 | WZP-CORP-00 | — | Corporate decision and schema readiness is based on the documented design and separately approved local test target. |
| WZP-CORP-02 | WZP-CORP-01 | — | ORG implementation waits for owner/source and schema readiness decisions. |
| WZP-CORP-03 | WZP-CORP-01, WZP-CORP-02 | — | Corporate IAM requires approved organisation/workforce references and identity policies. |
| WZP-CORP-04 | WZP-CORP-02, WZP-CORP-03 | — | Governance approval/audit workflows bind to the organisation and corporate IAM authorities. |
| WZP-CORP-05 | WZP-CORP-02, WZP-CORP-03, WZP-CORP-04, WZP-API-01 | — | Administrative capabilities require ORG/IAM/GOV foundations and approved contracts. |
| WZP-MOB-01 | WZP-01 | — | Platform evaluation needs approved mobile product scope; it may proceed in parallel with WZP-API-01. |
| WZP-MOB-02 | WZP-MOB-01, WZP-API-02 | WZP-PUB-01, WZP-PUB-02, WZP-PUB-03, WZP-PUB-04 for selected mobile journeys; WZP-TRUST-01 when a selected journey includes discussions or communities | Mobile implementation needs the approved platform and stable contracts for each selected capability; interactive journeys also require their moderation path. |
| WZP-MOB-03 | WZP-MOB-02 | — | Device/security/store-readiness work follows app implementation. It has no WZP-OPS-01 or WZP-AUD-01 prerequisite. |
| WZP-OPS-01 | WZP-01, WZP-02 | Selected component packages from WZP-PUB-01, WZP-PUB-02, WZP-PUB-03, WZP-PUB-04, WZP-TRUST-01, WZP-TRUST-02, WZP-API-02, WZP-INT-03, WZP-INT-05, WZP-INT-06, WZP-CORP-02, WZP-CORP-03, WZP-CORP-04, WZP-CORP-05, and WZP-MOB-03, separately for B or C | The beta-readiness output depends only on B and its transitive safety-critical dependencies; production-readiness depends only on C. If public Writ publishing is in B, the WZP-TRUST-01 content-only reporting slice and its operational evidence are mandatory even when PUB-02 is excluded. Interaction moderation additionally requires PUB-02 plus the applicable TRUST-01 path. C-only work cannot block Gate 2. |
| WZP-AUD-01 | WZP-01, WZP-03, WZP-OPS-01 beta-readiness output | Every package and safety-critical dependency in frozen B | Gate 2 inventories and audits all beta assets, but does not require complete programme scope or C-only capabilities. |
| WZP-BETA-01 | WZP-AUD-01 pass, WZP-OPS-01 beta-readiness output | — | Requires the frozen B inventory, approved support/privacy plan, and separate written project-owner authorization. Audit clearance alone does not authorize beta. |
| WZP-AUD-02 | WZP-BETA-01 exit, WZP-03, WZP-OPS-01 production-readiness output | Every package and dependency in frozen C | Gate 3 audits the exact production inventory, including C-only capabilities, beta findings, release identity, operations, and recovery evidence. |

## Controlled WZP-02.1 child workstream (not part of the 32-package baseline)

| Work item | Input | Output | Next gate |
|---|---|---|---|
| WZP-02 initial read-only verification | WZP-01.8 bounded authorization | Source/test-safety/environment/schema/security/B findings and limitations | Planning may identify candidate corrections; it does not establish WZP-02 exit. |
| WZP-02.1 remediation planning | Initial report, current source, WZP-01.6 and WZP-01.8 decisions | Eleven proposed documents, ownership register, and separate implementation approval units | Project Owner selects exact units and appoints required owners/reviewer. |
| Any runtime/database correction | Explicitly approved WZP-02.1 unit plus required environment, target, and role evidence | Bounded implementation and independent evidence | Separate Project Owner decision for subsequent work; no edge automatically authorizes beta or release. |

This child workstream records the current planning handoff only. It does not add a release capability, create a new product dependency, change the approved 32-package graph, or authorize a test/database operation.

## Complete dependency graph

Dashed edges mean “only when included in the specified contract or release inventory.” The B and C inventory nodes represent separately frozen owner-approved component lists. Their edges are conditional; the diagram does not imply that every candidate capability is included.

~~~mermaid
flowchart TD
    P00["WZP-00"] --> P01["WZP-01"]
    P01 --> P02["WZP-02"]
    P01 --> P03["WZP-03"]
    P01 --> PUB1["WZP-PUB-01"]
    P02 --> PUB1
    P01 --> API1["WZP-API-01"]
    P02 --> API1
    P01 --> PUB2["WZP-PUB-02"]
    P02 --> PUB2
    PUB1 --> PUB2
    API1 --> PUB2
    P01 --> PUB3["WZP-PUB-03"]
    P02 --> PUB3
    PUB1 --> PUB3
    API1 --> PUB3
    P01 --> PUB4["WZP-PUB-04"]
    API1 --> PUB4
    P01 --> TRUST1["WZP-TRUST-01"]
    PUB1 -. public Writ report integration .-> TRUST1
    PUB2 -. interaction moderation only .-> TRUST1
    TRUST1 --> TRUST2["WZP-TRUST-02"]
    P01 --> TRUST2
    API1 --> TRUST2
    CORP3["WZP-CORP-03"] -. corporate operators only .-> TRUST2
    API1 --> API2["WZP-API-02"]
    P02 --> API2
    PUB1 -. endpoint selected .-> API2
    PUB2 -. endpoint selected .-> API2
    PUB3 -. endpoint selected .-> API2
    PUB4 -. endpoint selected .-> API2
    TRUST1 -. endpoint selected .-> API2
    TRUST2 -. endpoint selected .-> API2
    CORP5["WZP-CORP-05"] -. endpoint selected .-> API2
    INT0["WZP-INT-00"] --> INT1["WZP-INT-01"]
    INT1 --> INT2["WZP-INT-02"]
    INT2 --> INT3["WZP-INT-03"]
    INT2 --> INT4["WZP-INT-04"]
    INT3 --> INT4
    INT4 --> INT5["WZP-INT-05"]
    PUB3 --> INT5
    INT5 -. classifier required .-> INT6["WZP-INT-06"]
    PUB3 --> INT6
    API1 --> INT6
    CORP0["WZP-CORP-00"] --> CORP1["WZP-CORP-01"]
    CORP1 --> CORP2["WZP-CORP-02"]
    CORP1 --> CORP3
    CORP2 --> CORP3
    CORP2 --> CORP4["WZP-CORP-04"]
    CORP3 --> CORP4
    CORP2 --> CORP5
    CORP3 --> CORP5
    CORP4 --> CORP5
    API1 --> CORP5
    P01 --> MOB1["WZP-MOB-01"]
    MOB1 --> MOB2["WZP-MOB-02"]
    API2 --> MOB2
    PUB1 -. mobile journey selected .-> MOB2
    PUB2 -. mobile journey selected .-> MOB2
    TRUST1 -. mobile discussion journey selected .-> MOB2
    PUB3 -. mobile journey selected .-> MOB2
    PUB4 -. mobile journey selected .-> MOB2
    MOB2 --> MOB3["WZP-MOB-03"]
    P01 --> OPS["WZP-OPS-01"]
    P02 --> OPS
    OPS --> OPSB["WZP-OPS-01 beta-readiness output"]
    OPS --> OPSC["WZP-OPS-01 production-readiness output"]
    P01 --> B["Frozen B inventory"]
    P01 --> C["Frozen C inventory"]
    TRUST1 --> WITREPORT["Content-only public Writ reporting slice"]
    PUB1 -. Writ surface integration .-> WITREPORT
    WITREPORT -. required if Writ publishing is in B .-> B
    PUB2 --> INTERACTION["Public discussions plus moderation release bundle"]
    TRUST1 --> INTERACTION
    PUB1 -. selected in inventory .-> B
    PUB3 -. selected in inventory .-> B
    PUB4 -. selected in inventory .-> B
    INTERACTION -. selected in inventory .-> B
    TRUST2 -. selected in inventory .-> B
    API2 -. selected in inventory .-> B
    INT3 -. selected in inventory .-> B
    INT5 -. selected in inventory .-> B
    INT6 -. selected in inventory .-> B
    CORP2 -. selected in inventory .-> B
    CORP3 -. selected in inventory .-> B
    CORP4 -. selected in inventory .-> B
    CORP5 -. selected in inventory .-> B
    MOB3 -. selected in inventory .-> B
    PUB1 -. selected in inventory .-> C
    PUB3 -. selected in inventory .-> C
    PUB4 -. selected in inventory .-> C
    INTERACTION -. selected in inventory .-> C
    TRUST2 -. selected in inventory .-> C
    API2 -. selected in inventory .-> C
    INT3 -. selected in inventory .-> C
    INT5 -. selected in inventory .-> C
    INT6 -. selected in inventory .-> C
    CORP2 -. selected in inventory .-> C
    CORP3 -. selected in inventory .-> C
    CORP4 -. selected in inventory .-> C
    CORP5 -. selected in inventory .-> C
    MOB3 -. selected in inventory .-> C
    B --> OPSB
    C --> OPSC
    P03 --> AUD1["WZP-AUD-01"]
    B --> AUD1
    OPSB --> AUD1
    AUD1 --> BETA["WZP-BETA-01"]
    OPSB --> BETA
    P03 --> AUD2["WZP-AUD-02"]
    C --> AUD2
    OPSC --> AUD2
    BETA --> AUD2
    AUD2 --> OWNER["Separate project-owner launch approval"]
~~~

## Sequencing decisions

1. WZP-00 establishes the documentation baseline. WZP-01 is closed with conditions under WZP-01.8; approved scope, shared policy/permission decisions, target definitions, and explicit owner blockers remain the baseline. WZP-01.8 authorizes only an initial local, read-only WZP-02 verification; full WZP-02 exit requires its remaining evidence and applicable owner/reviewer assignments. WZP-03 applies after its own package approval.
2. Trust has two scoped paths under CR-WZP-2026-11-09-01: (a) content-only public Writ reporting within WZP-TRUST-01, independent of WZP-PUB-02 and required when public Writ publishing is included; (b) discussion/community reporting and moderation, which depends on WZP-PUB-02 and requires the applicable WZP-TRUST-01 interaction path. Both require the approved WZP-01 authority foundation and package-specific approval.
3. Knowledge sequence is: WZP-PUB-03 delivers content identifiers, metadata, explicit relationships, provenance, and non-AI search/discovery → separately approved WZP-INT-05 and WZP-INT-06 may consume its stable contracts. No WZP-INT package blocks the knowledge foundation.
4. Preserve the intelligence sequence: WP5 → WP5.1 → WP5.2 → WP6A → WP6A.1 → WP6B → WP6B.1 → WP6B.2 → WP6C → WP6D → WP7. Completion reports do not override the package status, technical validation, or separate approval.
5. WZP-MOB-01 assessment may run beside API contract work. WZP-MOB-02 waits for the approved platform and stable WZP-API-02 contracts. WZP-MOB-03 follows WZP-MOB-02; operations and audit wait for its output only when mobile is included in B or C.
6. Corporate sequence is WZP-CORP-00 design → WZP-CORP-01 decisions/schema/test readiness → WZ-ORG → WZ-IAM → WZ-GOV → corporate administration. It is not a public website dependency when excluded from the relevant release.
7. WZP-OPS-01, WZP-AUD-01, and WZP-AUD-02 use separate B and C readiness outputs. B is frozen before Gate 2; C is frozen separately for Gate 3. C-only work never blocks beta, and any included component receives full corresponding audit coverage.

## Safe parallel work

- WZP-01 owner decisions and product discovery can run alongside corporate source reconciliation planning, without touching unapproved schema or runtime.
- Public creation/profile, discussion, search/knowledge foundations, and mobile platform assessment may proceed after their listed prerequisites. The content-only Writ reporting slice does not wait for WZP-PUB-02; interaction moderation does.
- WZP-PUB-03 and advanced intelligence evaluation can be planned independently, but advanced intelligence cannot substitute for the stable non-AI foundation.
- Corporate design/decision closure can run alongside public work; corporate runtime does not become a public release dependency unless selected in B or C.
- Infrastructure planning can proceed without accessing live environments. Environment verification requires separate authorization.

Do not run parallel work that changes the same authentication/session boundary, database objects, API contract, or release artifact without an agreed integration owner and explicit scope.

## Critical path and cycle validation

The logical release path is WZP-00 → WZP-01 → WZP-02 → selected public feature packages and contracts → any selected safety-critical integrations → the matching WZP-OPS-01 readiness output → Gate 2 → controlled beta → beta finding closure → Gate 3 for C → separate owner launch approval.

If mobile is in B or C, its path adds WZP-MOB-01 → WZP-MOB-02 → WZP-MOB-03 before that inventory’s operations and audit outputs. If corporate capabilities are selected, their WZP-CORP dependency chain must also complete. Intelligence capabilities enter only when separately approved and included. No actual duration-based critical path can be calculated until scope, owners, capacity, and effort estimates are approved; the November 2026–December 2027 window remains provisional.

Static validation must treat every dashed edge as a possible edge and fail on any directed cycle or unknown package ID. The WZ-MASTER-ROADMAP-2027.2 full dependency graph is validated as a DAG; the release-specific B and C graphs are subgraphs and are also acyclic.
