# API and Mobile Readiness

**Assessment date:** 2026-11-08
**Status:** Planning and source inspection only; no API or mobile implementation.

## Current backend surface

routes/web.php provides server-rendered page routes and form submissions. routes/api.php and routes/admin.php are empty. The Lab route returns limited JSON, but it is not a public API contract. No OpenAPI specification, versioned endpoint namespace, API-specific authentication strategy, common error envelope, compatibility policy, push-notification integration or mobile project was found.

Existing web protection includes request-boundary, CSRF, rate-limit and session authentication middleware. These do not automatically define a mobile token strategy or public API boundary. The internal Python client is private server-to-server functionality; mobile clients must never receive its service credentials or call it directly.

## API foundation required before mobile implementation

1. Approve supported consumers and first-release feature inventory under WZP-01.
2. Define versioning and compatibility policy, resource identifiers, pagination/filter limits, and deterministic ordering.
3. Define public-user authentication, token/session issuance, rotation, revocation, recovery and secure mobile storage.
4. Define authorization and ownership rules per operation, including denial behavior.
5. Standardize request validation and generic error responses without leaking internals.
6. Define idempotency for retryable writes and duplicate submission behavior.
7. Define upload/media size/type/storage/scanning policy before enabling uploads.
8. Define abuse/rate limits and privacy-conscious request logging.
9. Publish an API contract and contract tests; include backward-compatibility tests for each supported client.
10. Establish API documentation and operational ownership before external exposure.

These are proposed prerequisites, not approval to implement an API.

## Mobile readiness

**Not ready for full application implementation.** The backend currently supports basic public writing and participation through server-rendered journeys, but has no stable mobile-facing API, app authentication contract, upload/push contract or compatibility tests. No mobile technology is selected.

Per docs/Roadmap/06-MOBILE-APPLICATION-PLAN.md, WZP-MOB-01 may assess requirements and technologies without selecting one until evidence and owner decisions are reviewed. Mobile implementation WZP-MOB-02 is blocked on stable WZP-API-02 contracts and selected public features. WZP-MOB-03 later defines device/security/store-readiness evidence.

## Recommended mobile contract scope

Start with sign-in/out and session expiry, profile basics, reading/search/saving content, draft/publish recovery, comments and notifications only after the related public journeys and safety controls are approved. Design accessibility, localization, offline drafts, conflict resolution, push consent, deletion/export obligations, diagnostics minimization and device coverage as explicit acceptance cases.

Do not choose Flutter, React Native or native iOS/Android in this assessment. Keep the PHP MVC backend and public identity; mobile uses a versioned public contract. Corporate IAM and private intelligence remain separate boundaries.

## Approval and sequencing

- **WZP-01:** product scope, target users, success measures, public journeys and named owners.
- **WZP-API-01:** API consumers, auth model, versioning, errors, limits, privacy and compatibility decisions.
- **WZP-MOB-01:** separate mobile technology/product assessment with approved criteria.
- **WZP-API-02:** implementation only after API-01 and bounded test environment are approved.
- **WZP-MOB-02:** only after API stability and selected feature packages are verified.
- **WZP-MOB-03:** security, device matrix, accessibility, signing/support and store-readiness review.

There is no mobile code, API service, push provider, app-store account or deployment state verified by this assessment.
