# PHP Python Intelligence Client

The WP6B client is an opt-in, server-side transport foundation. It is not
registered with routes, the Intelligence Lab, or ordinary PHP workflows.
`WRITEZONE_INTELLIGENCE_ENABLED` defaults to `false`; no request is sent while
the feature is disabled.

## Protected runtime configuration

Configure these values in protected process environment settings for each
environment. Local development may use the repository's ignored `.env` file
through the existing server bootstrap, but production secrets belong in the
hosting provider's protected runtime secret store. Never place them in source,
URLs, browser-visible configuration, or logs.

- `WRITEZONE_INTELLIGENCE_URL`: trusted service base URL; default
  `http://127.0.0.1:8765`. HTTP is accepted only for a loopback host. Remote
  service URLs must use HTTPS with certificate verification.
- `WRITEZONE_INTELLIGENCE_ENABLED`: explicit `true` to enable; otherwise off.
- `WRITEZONE_INTELLIGENCE_SERVICE_TOKEN` and
  `WRITEZONE_INTELLIGENCE_KEY_ID`: current credential pair.
- `WRITEZONE_INTELLIGENCE_NEXT_SERVICE_TOKEN` and
  `WRITEZONE_INTELLIGENCE_NEXT_KEY_ID`: optional rotation overlap pair.
- `WRITEZONE_INTELLIGENCE_ACTIVE_KEY`: `current` by default; set to `next`
  during a controlled client rotation after both pairs are provisioned.
- `WRITEZONE_INTELLIGENCE_REVOKED_KEY_IDS`: optional comma-separated IDs that
  must not be selected.
- `WRITEZONE_INTELLIGENCE_MAX_TEXT_BYTES` and
  `WRITEZONE_INTELLIGENCE_MAX_TEXT_CODE_POINTS`: defaults 32768 and 8000.
- `WRITEZONE_INTELLIGENCE_CONNECT_TIMEOUT_MS`,
  `WRITEZONE_INTELLIGENCE_TOTAL_TIMEOUT_MS`, and
  `WRITEZONE_INTELLIGENCE_MAX_RETRIES`: defaults 250 ms, 5000 ms, and one
  retry. The client rejects values that exceed those approved limits.

Use dedicated random credentials for each environment and keep the PHP
credential pair aligned with the Python service's current/next key rotation
configuration. WP6B does not create credentials or verify Hostinger runtime
capabilities.

## Destination ownership and transport

`PythonClassificationClientFactory` loads the runtime configuration once and
validates it through `PythonClassificationClientConfig`. That validated
configuration creates one immutable `ConfiguredServiceDestination`, which is
bound to the production `CurlHttpTransport`. Invalid configuration prevents
factory construction. Tests may inject an `HttpTransport` fake without creating
a production transport.

The transport API accepts only a relative endpoint path; it does not accept an
absolute URL. The client uses the fixed `/internal/v1/classifications` path.
The destination comes only from `WRITEZONE_INTELLIGENCE_URL`: HTTP remains
limited to loopback, and explicitly configured HTTPS destinations remain
supported with certificate and hostname verification. There is no hostname
allowlist; protected runtime configuration is the authority for the HTTPS
destination.

The transport rejects absolute and protocol-relative URLs, traversal paths,
queries, fragments, and unsupported URL forms before cURL. It disables
redirects and proxies, permits only HTTP and HTTPS, and caps response bodies at
64 KiB and aggregate response headers at 16 KiB. Response headers and bodies
are not included in client outcomes.

The isolated tests use synthetic data and temporary loopback fixtures. They do
not verify a real TLS handshake, DNS resolution or rebinding behavior, proxy
traversal, or Hostinger connectivity. Those network properties require a
separately approved environment and test plan.

## Request and outcome behavior

The client accepts text and English language only. It validates UTF-8, the
32 KiB byte limit, and the 8000 Unicode code point limit before encoding a
request with schema version 1, a UUIDv4, and taxonomy `wz-topics-v1`. It never
accepts a destination URL or arbitrary payload from a caller.

Unavailable transport, authentication, protocol, validation, or service
conditions produce an explicit unavailable outcome. HTTP 503
`classification_unavailable` is unavailable, not a successful abstention.
HTTP responses must contain exactly one supported
`Content-Type`: `application/json`, optionally followed by
`charset=utf-8` (case-insensitive; quoted UTF-8 is accepted). Missing,
duplicate, non-JSON, or unsupported charset values produce a generic
`invalid_response` unavailable outcome. The transport retains bounded response
headers internally; unrelated headers are not copied into outcomes.

The client does not log request or response bodies, credentials, response
headers, or exception messages. Only bounded operational metadata is available
from the outcome. A successful outcome can only be created from a
`ValidatedClassificationResponse` produced after the complete response
contract passes validation.

The current Python service intentionally returns 503 until WP7 is approved.
WP6B tests use a fake HTTP transport; no user content is sent to a running
service. The direct cURL regression tests use only a short-lived fixture bound
to `127.0.0.1` with synthetic data; they do not contact the Lab or another
network service.

## Credential rotation procedure

Rotate a pair without an interval in which PHP selects a key the Python service
rejects:

1. Keep Python current key A and add next key B. Provision the matching PHP
   current A and next B pairs, then set `WRITEZONE_INTELLIGENCE_ACTIVE_KEY=next`
   so PHP selects B while Python accepts both.
2. Promote B to Python current and remove its Python next slot. PHP can continue
   selecting B from its next slot throughout this change.
3. Move B into PHP's current pair, clear the PHP next pair, and set the active
   key to `current`. Verify B remains accepted by Python.
4. Remove A from PHP before adding A's identifier to
   `WRITEZONE_INTELLIGENCE_REVOKED_KEY_IDS`; then revoke A in Python. PHP keeps
   using B throughout.

Apply each environment's protected configuration through its approved secret
management process. Do not place token values in source, documentation,
command history, test output, or logs. WP6B tests exercise this transition with
synthetic tokens only.
