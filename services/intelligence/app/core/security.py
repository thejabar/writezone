"""Private service bearer authentication primitives."""

import secrets
from typing import Annotated

from fastapi import Header, HTTPException, Request, status

from app.core.config import Settings, get_settings, is_valid_key_id, is_valid_service_token


_DUMMY_TOKEN = "!" * 43


class AuthenticationConfigurationError(RuntimeError):
    """Raised when authentication is required but not configured."""


class ServiceAuthenticationError(RuntimeError):
    """Raised when supplied service credentials are missing or invalid."""


def verify_service_credentials(
    authorization: str | None,
    key_id: str | None,
    settings: Settings | None = None,
) -> None:
    """Validate a bearer token and key identifier without disclosing either."""
    active_settings = settings or get_settings()
    current_token = active_settings.service_token
    current_key_id = active_settings.key_id
    next_token = active_settings.next_service_token
    next_key_id = active_settings.next_key_id
    if not current_token or not current_key_id:
        raise AuthenticationConfigurationError(
            "service authentication is not configured"
        )

    scheme, separator, supplied_token = (authorization or "").partition(" ")
    is_bearer = scheme.casefold() == "bearer" and bool(separator)
    candidate_is_valid = is_valid_service_token(supplied_token)
    token_candidate = supplied_token if candidate_is_valid else _DUMMY_TOKEN
    valid_key_id = bool(key_id and is_valid_key_id(key_id))
    revoked = bool(key_id and key_id in active_settings.revoked_key_ids)

    selected_token: str | None = None
    if valid_key_id and not revoked:
        if key_id == current_key_id:
            selected_token = current_token
        elif next_key_id is not None and key_id == next_key_id:
            selected_token = next_token

    expected_token = selected_token or _DUMMY_TOKEN
    token_matches = secrets.compare_digest(token_candidate, expected_token)
    if not (
        is_bearer
        and candidate_is_valid
        and selected_token is not None
        and not revoked
        and token_matches
    ):
        raise ServiceAuthenticationError("service authentication failed")


def require_service_auth(
    authorization: Annotated[str | None, Header()] = None,
    key_id: Annotated[str | None, Header(alias="X-WZ-Key-Id")] = None,
) -> None:
    """FastAPI dependency for routes that require private service auth."""
    try:
        verify_service_credentials(authorization, key_id)
    except (AuthenticationConfigurationError, ValueError):
        raise HTTPException(
            status_code=status.HTTP_503_SERVICE_UNAVAILABLE,
            detail={"code": "service_auth_unavailable"},
        ) from None
    except ServiceAuthenticationError:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail={"code": "unauthorized"},
            headers={"WWW-Authenticate": "Bearer"},
        ) from None


def require_classification_service_auth(
    request: Request,
    authorization: Annotated[str | None, Header()] = None,
    key_id: Annotated[str | None, Header(alias="X-WZ-Key-Id")] = None,
) -> None:
    """Reuse the established auth dependency after pre-parse boundary checks."""
    if request.scope.get("state", {}).get("classification_auth_verified") is True:
        return
    require_service_auth(authorization, key_id)
