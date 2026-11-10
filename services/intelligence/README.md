# WriteZone Intelligence Service

This directory contains the private Python service foundation for WriteZone. It exposes a minimal health endpoint and a protected v1 classification boundary. The endpoint validates requests but returns `503 classification_unavailable`; classification logic is intentionally not implemented until WP7 is separately approved.

## Runtime and dependencies

Use Python 3.12 or later in an isolated virtual environment outside the repository. The approved local environment is `/home/jabar/.venvs/writezone-intelligence`.

```sh
cd /mnt/c/Users/DELL/Projects/writezone/services/intelligence
source /home/jabar/.venvs/writezone-intelligence/bin/activate
python -m pip install -r requirements.txt
```

The service reads configuration only from its process environment. It does not load the PHP `.env` file. `WRITEZONE_INTELLIGENCE_VERSION` must be semantic version syntax (default `0.1.0`); `WRITEZONE_INTELLIGENCE_PORT` defaults to `8765`.

Authentication uses a 32-byte cryptographically random token represented as exactly 43 unpadded base64url ASCII characters. Configure the current pair with `WRITEZONE_INTELLIGENCE_SERVICE_TOKEN` and `WRITEZONE_INTELLIGENCE_KEY_ID`. The key ID format is 1-64 ASCII letters, digits, dots, underscores, or hyphens, beginning with a letter or digit. For an overlap rotation, also configure `WRITEZONE_INTELLIGENCE_NEXT_SERVICE_TOKEN` and `WRITEZONE_INTELLIGENCE_NEXT_KEY_ID`. `WRITEZONE_INTELLIGENCE_REVOKED_KEY_IDS` accepts a comma-separated list of revoked identifiers; an identifier cannot be active and revoked simultaneously. Current and next tokens and IDs must be distinct and are validated at configuration load. Provision tokens through approved secret storage; this service does not generate or store production credentials.

During rotation, configure the new pair as next while retaining the current pair, update the caller to use the next ID, then promote next to current and remove the old pair. Keep a retired identifier in the revoked list to prevent accidental reuse. Unknown and revoked IDs receive the same generic authentication failure.

## Local execution

Start with `python -m app.main`. The application binds only to `127.0.0.1`; the host address is fixed in code. It serves `GET /internal/v1/health` and a protected `POST /internal/v1/classifications` boundary. It has no public API, interactive documentation, database connection, external provider, model, or persistent background process. The health response contains only service status and version.

The service authentication boundary expects `Authorization: Bearer …` and `X-WZ-Key-Id`. It uses the existing constant-time token comparison and fails closed if required configuration is absent. For `POST /internal/v1/classifications`, authentication and a 256 KiB raw-body limit run before JSON parsing. Only `application/json` with absent or UTF-8 charset and identity/no content encoding is accepted. Generic errors do not include input, validation details, or credentials. Unexpected HTTP exceptions are caught outside Starlette's error middleware; only a fixed category, generated request ID, and UTC timestamp are logged, while the client receives a generic 500 with `X-WZ-Request-ID`. Early boundary rejections and 500 responses include `Connection: close` to prevent reuse when request bytes may remain unread. The isolated tests verify the ASGI header, not live Uvicorn socket reuse. Health is deliberately minimal and is intended to be reachable only through the loopback-bound service.

## Isolated tests

Run only this service's standard-library tests from this directory:

```sh
PYTHONDONTWRITEBYTECODE=1 python -m unittest discover -s tests -p 'test_*.py' -v
```

Tests do not connect to MariaDB, call external services, or access PHP application storage. The classification schemas enforce the v1 contract and do not perform classification.
