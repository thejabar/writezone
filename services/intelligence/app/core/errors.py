"""Sanitised HTTP error responses for the private intelligence API."""

from fastapi import FastAPI, Request
from fastapi.exceptions import RequestValidationError
from starlette.exceptions import HTTPException as StarletteHTTPException
from starlette.responses import JSONResponse


def error_response(status_code: int, code: str) -> JSONResponse:
    """Build an error response without reflecting request or configuration data."""
    headers = {"WWW-Authenticate": "Bearer"} if status_code == 401 else None
    return JSONResponse(
        status_code=status_code,
        content={"error": {"code": code}},
        headers=headers,
    )


async def handle_validation_error(
    _request: Request,
    exception: RequestValidationError,
) -> JSONResponse:
    """Hide Pydantic details and classify malformed JSON separately."""
    malformed_json = any(
        error.get("type") == "json_invalid"
        for error in exception.errors()
    )
    if malformed_json:
        return error_response(400, "invalid_request")
    return error_response(422, "invalid_request")


async def handle_http_error(
    _request: Request,
    exception: StarletteHTTPException,
) -> JSONResponse:
    """Map framework exceptions to a small, non-sensitive error vocabulary."""
    if exception.status_code == 400:
        code = "invalid_request"
    elif exception.status_code == 401:
        code = "unauthorized"
    elif exception.status_code == 413:
        code = "payload_too_large"
    elif exception.status_code == 415:
        code = "unsupported_media_type"
    elif exception.status_code == 422:
        code = "invalid_request"
    elif exception.status_code == 429:
        code = "rate_limited"
    elif exception.status_code == 503:
        detail = exception.detail
        code = (
            "classification_unavailable"
            if isinstance(detail, dict)
            and detail.get("code") == "classification_unavailable"
            else "service_unavailable"
        )
    elif exception.status_code == 404:
        code = "not_found"
    elif exception.status_code == 405:
        code = "method_not_allowed"
    else:
        code = "request_failed"
    return error_response(exception.status_code, code)


async def handle_unexpected_error(
    _request: Request,
    _exception: Exception,
) -> JSONResponse:
    """Keep unexpected exception details out of HTTP responses."""
    return error_response(500, "internal_error")


def install_error_handlers(app: FastAPI) -> None:
    """Register central sanitised handlers for validation and HTTP errors."""
    app.add_exception_handler(RequestValidationError, handle_validation_error)
    app.add_exception_handler(StarletteHTTPException, handle_http_error)
    app.add_exception_handler(Exception, handle_unexpected_error)
