# Intelligence Integration Readiness

**Assessment date:** 2026-11-08
**Authority:** WZ-MASTER-ROADMAP-2027.2 and docs/Engineering/PYTHON_INTELLIGENCE_CLIENT.md
**Status:** Local code/documentation inspection only; no services or external providers started.

## Existing foundation

### Python service

services/intelligence/ contains the FastAPI application, configuration, authentication, bounded request handling and Pydantic contracts. The health route is available within the private service design. The classification route deliberately returns 503 classification_unavailable; no classifier, model or external AI provider is implemented. The README documents loopback binding. AST parsing succeeded for 19 Python files under Windows Python 3.14.3, but FastAPI was unavailable in that interpreter, so unit tests and dependency compatibility were not checked here.

### PHP clien

app/Intelligence/Remote/ and config/intelligence.php provide a server-side client and configuration. WRITEZONE_INTELLIGENCE_ENABLED defaults false. The factory validates config and binds the cURL transport to a configured service destination; request data cannot select the URL. Documentation describes loopback-only HTTP, configured HTTPS with TLS verification, disabled redirects/proxies, bounded timeouts/retries and bounded response body/headers. The client is not instantiated by routes or ordinary user workflows.

The PHP isolated suite could not run because PHP/Composer are absent in Windows and WSL returned E_ACCESSDENIED. Therefore the documented/tested claims were not re-executed in this assessment.

## Current capability status

| Capability | Status |
|---|---|
| Python service foundation | Reported complete in roadmap; local source exists |
| Python API security boundary | Reported complete; local source exists |
| PHP client and destination trust boundary | Awaiting technical validation in canonical roadmap |
| Lab and consent integration | Not authorized |
| Cross-language integration testing | Not authorized |
| Classifier / WP7 | Not authorized; unavailable behavior remains |
| Production provider or model | Not implemented or approved |

The roadmap’s controlled status is not runtime evidence. This assessment does not close WP6B or change any work-package status.

## Controls and limits

- Keep the feature disabled by default and unavailable behavior harmless to normal PHP workflows.
- Store environment-specific service credentials only in protected runtime configuration; never in browser code, source, URLs, logs or test output.
- Keep the destination configuration-controlled; loopback HTTP only for local development, explicit HTTPS for a separately approved remote deployment.
- Preserve request text and response-body limits, generic errors, TLS checks, redirect/proxy restrictions and bounded timeouts.
- Do not transmit user content until WP6C consent/product decisions, WP6D cross-language verification, privacy review and separate authorization exist.
- The Python endpoint has no model and must not return fabricated labels. Do not expose the private endpoint as mobile/public API.
- Validate real TLS/DNS/network behavior only in an explicitly approved environment; local synthetic fixtures do not establish production network safety.

## Required gates

1. Independent closure of WP6B destination/transport security findings.
2. Separate explicit authorization for WP6C Lab integration and consent controls.
3. Isolated PHP/Python contract tests under WP6D, using synthetic text and approved local service only.
4. Separate model/quality/privacy decision for WP7, including taxonomy, abstention, language, evaluation corpus, quality thresholds and human oversight.
5. Deployment-specific secrets, monitoring, incident response and rollback readiness before any production consideration.

No database, application .env, production credentials, remote service or external AI provider was accessed.
