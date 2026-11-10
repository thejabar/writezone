"""Sanitised outer ASGI boundary for unexpected HTTP request exceptions."""

from collections.abc import Awaitable, Callable
from datetime import datetime, timezone
import logging
from typing import Any
from uuid import uuid4

from starlette.responses import JSONResponse


ASGIReceive = Callable[[], Awaitable[dict[str, Any]]]
ASGISend = Callable[[dict[str, Any]], Awaitable[None]]
ASGIApp = Callable[
    [dict[str, Any], ASGIReceive, ASGISend],
    Awaitable[None],
]


class SanitizedExceptionBoundary:
    """Catch Starlette's re-raised HTTP exceptions before Uvicorn sees them."""

    def __init__(self, app: ASGIApp) -> None:
        self.inner_app = app
        self.logger = logging.getLogger("uvicorn.error")

    async def __call__(
        self,
        scope: dict[str, Any],
        receive: ASGIReceive,
        send: ASGISend,
    ) -> None:
        if scope.get("type") != "http":
            await self.inner_app(scope, receive, send)
            return

        request_id = uuid4().hex
        response_started = False

        async def send_with_request_id(message: dict[str, Any]) -> None:
            nonlocal response_started
            if message.get("type") == "http.response.start":
                response_started = True
                if message.get("status") == 500:
                    headers = list(message.get("headers", []))
                    if not any(
                        name.lower() == b"x-wz-request-id"
                        for name, _ in headers
                    ):
                        headers.append((b"x-wz-request-id", request_id.encode("ascii")))
                    if not any(name.lower() == b"connection" for name, _ in headers):
                        # A server error can happen before an endpoint or
                        # middleware has consumed the request body. Closing
                        # avoids reusing a connection with unread bytes.
                        headers.append((b"connection", b"close"))
                    message = {**message, "headers": headers}
            await send(message)

        try:
            await self.inner_app(scope, receive, send_with_request_id)
        except Exception:
            self._record_sanitized_event(request_id)
            if not response_started:
                response = JSONResponse(
                    status_code=500,
                    content={"error": {"code": "internal_error"}},
                    headers={
                        "X-WZ-Request-ID": request_id,
                        "Connection": "close",
                    },
                )
                try:
                    await response(scope, receive, send_with_request_id)
                except Exception:
                    # A disconnected client must not cause the exception to
                    # escape to Uvicorn's traceback logger.
                    return

    def _record_sanitized_event(self, request_id: str) -> None:
        timestamp = datetime.now(timezone.utc).isoformat(timespec="milliseconds")
        try:
            self.logger.error(
                "writezone_event category=unhandled_exception request_id=%s timestamp=%s",
                request_id,
                timestamp,
            )
        except Exception:
            # Logging failures must not re-emit the original exception.
            return
