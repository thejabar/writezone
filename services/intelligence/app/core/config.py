"""Environment-only configuration with safe local defaults."""

import base64
import binascii
from dataclasses import dataclass
import os
import re
from collections.abc import Mapping
import secrets


DEFAULT_SERVICE_VERSION = "0.1.0"
DEFAULT_PORT = 8765
_KEY_ID_PATTERN = re.compile(r"^[A-Za-z0-9][A-Za-z0-9._-]{0,63}$")
_TOKEN_PATTERN = re.compile(r"^[A-Za-z0-9_-]{43}$")
_SEMVER_PATTERN = re.compile(
    r"^(0|[1-9][0-9]*)\.(0|[1-9][0-9]*)\.(0|[1-9][0-9]*)"
    r"(?:-((?:0|[1-9][0-9]*|[0-9]*[A-Za-z-][0-9A-Za-z-]*)"
    r"(?:\.(?:0|[1-9][0-9]*|[0-9]*[A-Za-z-][0-9A-Za-z-]*))*))?"
    r"(?:\+([0-9A-Za-z-]+(?:\.[0-9A-Za-z-]+)*))?$"
)


@dataclass(frozen=True)
class Settings:
    service_version: str
    port: int
    service_token: str | None
    key_id: str | None
    next_service_token: str | None
    next_key_id: str | None
    revoked_key_ids: frozenset[str]


def is_valid_service_token(token: str) -> bool:
    """Require unpadded base64url encoding of exactly 32 token bytes."""
    if not _TOKEN_PATTERN.fullmatch(token):
        return False
    try:
        decoded = base64.b64decode(token + "=", altchars=b"-_", validate=True)
    except (binascii.Error, ValueError):
        return False
    return (
        len(decoded) == 32
        and base64.urlsafe_b64encode(decoded).decode("ascii").rstrip("=") == token
    )


def is_valid_key_id(key_id: str) -> bool:
    """Key IDs are 1-64 ASCII letters, digits, dot, underscore, or hyphen."""
    return bool(_KEY_ID_PATTERN.fullmatch(key_id))


def is_valid_semantic_version(version: str) -> bool:
    """Validate a strict SemVer value within the service's 64-character limit."""
    return len(version) <= 64 and bool(_SEMVER_PATTERN.fullmatch(version))


def _read_token(values: Mapping[str, str], name: str) -> str | None:
    token = values.get(name) or None
    if token is not None and not is_valid_service_token(token):
        raise ValueError(
            f"{name} must be 32 random bytes encoded as 43 unpadded base64url characters"
        )
    return token


def _read_key_id(values: Mapping[str, str], name: str) -> str | None:
    key_id = values.get(name) or None
    if key_id is not None and not is_valid_key_id(key_id):
        raise ValueError(f"{name} has an invalid format")
    return key_id


def _read_revoked_key_ids(values: Mapping[str, str]) -> frozenset[str]:
    raw_value = values.get("WRITEZONE_INTELLIGENCE_REVOKED_KEY_IDS", "")
    if not raw_value.strip():
        return frozenset()

    key_ids = [value.strip() for value in raw_value.split(",")]
    if any(not is_valid_key_id(key_id) for key_id in key_ids):
        raise ValueError("WRITEZONE_INTELLIGENCE_REVOKED_KEY_IDS has an invalid format")
    if len(key_ids) != len(set(key_ids)):
        raise ValueError("WRITEZONE_INTELLIGENCE_REVOKED_KEY_IDS contains duplicates")
    return frozenset(key_ids)


def load_settings(environ: Mapping[str, str] | None = None) -> Settings:
    """Load service settings without reading files or PHP configuration."""
    values = os.environ if environ is None else environ
    version = values.get("WRITEZONE_INTELLIGENCE_VERSION", DEFAULT_SERVICE_VERSION)
    if not is_valid_semantic_version(version):
        raise ValueError("WRITEZONE_INTELLIGENCE_VERSION must be a valid semantic version")

    port_value = values.get("WRITEZONE_INTELLIGENCE_PORT", str(DEFAULT_PORT))
    try:
        port = int(port_value)
    except (TypeError, ValueError) as error:
        raise ValueError("WRITEZONE_INTELLIGENCE_PORT must be an integer") from error
    if not 1024 <= port <= 65535:
        raise ValueError("WRITEZONE_INTELLIGENCE_PORT must be between 1024 and 65535")

    service_token = _read_token(values, "WRITEZONE_INTELLIGENCE_SERVICE_TOKEN")
    key_id = _read_key_id(values, "WRITEZONE_INTELLIGENCE_KEY_ID")
    next_service_token = _read_token(
        values,
        "WRITEZONE_INTELLIGENCE_NEXT_SERVICE_TOKEN",
    )
    next_key_id = _read_key_id(values, "WRITEZONE_INTELLIGENCE_NEXT_KEY_ID")
    revoked_key_ids = _read_revoked_key_ids(values)

    if (service_token is None) != (key_id is None):
        raise ValueError("current service token and key ID must be configured together")
    if (next_service_token is None) != (next_key_id is None):
        raise ValueError("next service token and key ID must be configured together")
    if next_service_token is not None and service_token is None:
        raise ValueError("a next key requires a configured current key")
    if next_service_token is not None and service_token is not None:
        if key_id == next_key_id:
            raise ValueError("current and next key IDs must be different")
        if secrets.compare_digest(service_token, next_service_token):
            raise ValueError("current and next tokens must be different")

    active_key_ids = {key_id, next_key_id} - {None}
    if active_key_ids.intersection(revoked_key_ids):
        raise ValueError("a key ID cannot be both active and revoked")

    return Settings(
        service_version=version,
        port=port,
        service_token=service_token,
        key_id=key_id,
        next_service_token=next_service_token,
        next_key_id=next_key_id,
        revoked_key_ids=revoked_key_ids,
    )


def get_settings() -> Settings:
    """Return current process settings; values are not cached or logged."""
    return load_settings()
