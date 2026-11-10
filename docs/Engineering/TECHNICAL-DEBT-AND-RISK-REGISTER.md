# Technical Debt and Risk Register

**Assessment date:** 2026-11-08
**Severity is an engineering assessment for prioritization, not a formal release-gate decision.**

| ID | Severity | Finding and evidence | Impact | Dependency / recommended action |
|---|---|---|---|---|
| R-01 | High | The test collection includes DB writes and shared-storage deletion: WritPublicationEventRegression.php, ErrorHandlerRegression.php and RateLimitMiddlewareRegression.php are in the aggregate runner; RateLimitRouterRegression.php is outside it but also deletes shared files | Accidental write/deletion or misleading release evidence | Before routine test/release use, isolate DB identity and storage, or split the suite; WZP-02 |
| R-02 | High | Schema snapshot and root baseline disagree on users.handle, notifications.actor_id and actor index name | Unsafe migration or unintended nullability/data changes | Reconcile desired schema and migration history; no DB operation until approved target evidence |
| R-03 | High | GET /notifications mutates read state through NotificationController::index and Notification::markAllRead | Cross-site navigation may trigger state change; violates safe HTTP semantics | Make GET read-only; add explicit authenticated, CSRF-protected mutation and method tests |
| R-04 | High | No end-to-end reporting/moderation/appeals path while comments and votes exist | Abuse response and user protection are incomplete if participation expands | WZP-01 authority/policy closure; implement and integrate safety path before wider community release |
| R-05 | High | Migration status and release check require DB access; runner uses local config and has no independent target allowlist | Wrong-target connection risk and unsafe test execution | Fail-closed independent test identity policy; separate offline release checks |
| R-06 | Medium | Registration validation is incomplete; login errors reveal whether an account exists | Invalid account data and account enumeration | Approve validation/error contract; add isolated regression coverage |
| R-07 | Medium | Bookmark route constrains a public_id as numeric | Existing bookmark route can fail for nonnumeric public IDs | Align route constraint and lookup; add route/controller regression |
| R-08 | Medium | Feed retrieval is unpaginated; ranking scores are computed without final sort | Growing dataset can degrade latency and discovery quality | Define cursor/page and relevance acceptance before optimizing |
| R-09 | Medium | QueryBuilder interpolates table/column identifiers | Future dynamic identifier input can become SQL injection | Keep identifiers internal/allowlisted; test query builders at boundaries |
| R-10 | Medium | ErrorHandler logs exception message, file and line; no redaction/rotation/retention guarantee established | Sensitive values may reach persistent logs; disk/retention risk | Define safe structured logging, permissions, redaction, rotation and retention ownership |
| R-11 | Medium | No versioned public API or mobile contract; api.php empty | Native clients cannot rely on stable auth, errors or compatibility | WZP-API-01 before API implementation or mobile build |
| R-12 | Medium | Writ publish side effects are synchronous; dispatcher has no durable queue/outbox | Notification/event failure handling and retries are limited | Keep in-process for current bounded work; design durable outbox only for approved reliability needs |
| R-13 | Medium | Intelligence classification always unavailable; WP6B remains awaiting technical validation | Consumers could mistake transport foundation for delivered classification | Preserve disabled default; complete audit and separate WP6C/6D/WP7 approvals |
| R-14 | Medium | Corporate domain models are proposed only | Risk of conflating corporate access with public accounts | Maintain identity separation; resolve corporate owners/test/schema prerequisites before implementation |
| R-15 | Low | Font Awesome is served from third-party CDN without established SRI/privacy assessment | External availability and third-party request exposure | Document dependency; decide whether self-hosting or integrity controls are required |
| R-16 | Low | Empty config placeholders and no configured CI/linter/static-analysis tooling | Engineering checks depend on local manual execution | Add only after workflow ownership and isolated service policy are approved |
| R-17 | Medium | Feed/search/community/product acceptance thresholds are not owner-approved | “Complete” can be judged inconsistently | WZP-01 acceptance-target register with evidence source and decision owner |
| R-18 | Medium | No deployment, monitoring, backup/restore, DR or operations state was verified | Release supportability is unknown | WZP-OPS-01 and Gate 2/3 evidence; do not infer Hostinger state |

## Risk handling

- R-01, R-02 and R-05 are blockers for database-dependent verification and release-gate execution.
- R-03 should be an early bounded application correction once WZP-01 and safe test prerequisites are cleared.
- R-04 blocks expanding public participation beyond approved moderation scope.
- R-06/R-07 are suitable for the same narrowly scoped core-stabilization package after the verification harness is safe.
- R-11/R-13/R-14 require separate package approvals and must not be bundled into unrelated PHP repairs.
- Severity should be reviewed by the assigned security/QA and domain owner before a release decision.
