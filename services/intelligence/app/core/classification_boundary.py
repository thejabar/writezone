"""Route-scoped authentication and raw-body boundary for classification requests."""

from collections.abc import Awaitable, Callable
import re
from typing import Any

from app.core.errors import error_response
from app.core.security import (
    AuthenticationConfigurationError,
    ServiceAuthenticationError,
    verify_service_credentials,
)


CLASSIFICATION_PATH = "/internal/v1/classifications"
MAX_CLASSIFICATION_BODY_BYTES = 256 * 1024
_DECIMAL_LENGTH = re.compile(rb"^[0-9]+$")

ASGIReceive = Callable[[], Awaitable[dict[str, Any]]]
ASGISend = Callable[[dict[str, Any]], Awaitable[None]]


class ClassificationRequestBoundary:
    """Authenticate and bound classification bodies before JSON parsing."""

    def __init__(self, app: Callable[..., Awaitable[None]]) -> None:
        self.app = app

    async def __call__(
        self,
        scope: dict[str, Any],
        receive: ASGIReceive,
        send: ASGISend,
    ) -> None:
        if (
            scope.get("type") != "http"
            or scope.get("path") != CLASSIFICATION_PATH
        ):
            await self.app(scope, receive, send)
            return

        if scope.get("method") != "POST":
            await self._respond(send, 405, "method_not_allowed")
            return

        headers = scope.get("headers", [])
        authorization = self._single_header(headers, b"authorization")
        key_id = self._single_header(headers, b"x-wz-key-id")
        if authorization is _AMBIGUOUS or key_id is _AMBIGUOUS:
            await self._respond(send, 401, "unauthorized")
            return

        try:
            verify_service_credentials(
                self._decode_header(authorization),
                self._decode_header(key_id),
            )
        except (AuthenticationConfigurationError, ValueError):
            await self._respond(send, 503, "service_unavailable")
            return
        except ServiceAuthenticationError:
            await self._respond(send, 401, "unauthorized")
            return

        scope.setdefault("state", {})["classification_auth_verified"] = True

        header_error = self._validate_representation_headers(headers)
        if header_error is not None:
            await self._respond(send, *header_error)
            return

        length_result = self._content_length(headers)
        if isinstance(length_result, tuple):
            await self._respond(send, *length_result)
            return
        content_length = length_result
        if content_length is not None and content_length > MAX_CLASSIFICATION_BODY_BYTES:
            await self._respond(send, 413, "payload_too_large")
            return

        body_result = await self._read_body(receive, content_length)
        if body_result is None:
            return
        if isinstance(body_result, tuple):
            await self._respond(send, *body_result)
            return
        body = body_result

        delivered = False

        async def replay_body() -> dict[str, Any]:
            nonlocal delivered
            if delivered:
                return {"type": "http.disconnect"}
            delivered = True
            return {"type": "http.request", "body": body, "more_body": False}

        await self.app(scope, replay_body, send)

    async def _read_body(
        self,
        receive: ASGIReceive,
        declared_length: int | None,
    ) -> bytes | tuple[int, str] | None:
        chunks: list[bytes] = []
        total = 0
        while True:
            message = await receive()
            message_type = message.get("type")
            if message_type == "http.disconnect":
                return None
            if message_type != "http.request":
                return (400, "invalid_request")

            chunk = message.get("body", b"")
            if not isinstance(chunk, bytes):
                return (400, "invalid_request")
            total += len(chunk)
            if total > MAX_CLASSIFICATION_BODY_BYTES:
                return (413, "payload_too_large")
            if declared_length is not None and total > declared_length:
                return (400, "invalid_request")
            chunks.append(chunk)

            if not message.get("more_body", False):
                break

        if declared_length is not None and total != declared_length:
            return (400, "invalid_request")
        return b"".join(chunks)

    async def _respond(self, send: ASGISend, status_code: int, code: str) -> None:
        response = error_response(status_code, code)
        # This response may be sent before the body is drained. Do not reuse
        # the HTTP/1.1 connection with unread or ambiguously framed bytes.
        response.headers["Connection"] = "close"
        await response({"type": "http"}, _unused_receive, send)

    @staticmethod
    def _single_header(
        headers: list[tuple[bytes, bytes]],
        name: bytes,
    ) -> bytes | object | None:
        values = [value for header_name, value in headers if header_name.lower() == name]
        if len(values) > 1:
            return _AMBIGUOUS
        return values[0] if values else None

    @staticmethod
    def _decode_header(value: bytes | object | None) -> str | None:
        if value is None or value is _AMBIGUOUS:
            return None
        return value.decode("latin-1")

    @classmethod
    def _content_length(
        cls,
        headers: list[tuple[bytes, bytes]],
    ) -> int | tuple[int, str] | None:
        values = [value for name, value in headers if name.lower() == b"content-length"]
        transfer_encodings = [
            value for name, value in headers if name.lower() == b"transfer-encoding"
        ]
        if len(values) > 1 or len(transfer_encodings) > 1:
            return (400, "invalid_request")
        if values and transfer_encodings:
            return (400, "invalid_request")
        if transfer_encodings:
            try:
                transfer_encoding = transfer_encodings[0].decode("ascii").strip().casefold()
            except UnicodeDecodeError:
                return (400, "invalid_request")
            if transfer_encoding != "chunked":
                return (400, "invalid_request")
        if not values:
            return None

        raw_length = values[0]
        if not _DECIMAL_LENGTH.fullmatch(raw_length):
            return (400, "invalid_request")
        normalized_length = raw_length.lstrip(b"0") or b"0"
        limit = str(MAX_CLASSIFICATION_BODY_BYTES).encode("ascii")
        if (
            len(normalized_length) > len(limit)
            or (
                len(normalized_length) == len(limit)
                and normalized_length > limit
            )
        ):
            return (413, "payload_too_large")
        if len(raw_length) > 64:
            return (400, "invalid_request")
        return int(normalized_length)

    @staticmethod
    def _validate_representation_headers(
        headers: list[tuple[bytes, bytes]],
    ) -> tuple[int, str] | None:
        content_types = [value for name, value in headers if name.lower() == b"content-type"]
        if len(content_types) != 1:
            return (415, "unsupported_media_type")
        try:
            content_type = content_types[0].decode("ascii")
            content_encodings = [
                value for name, value in headers if name.lower() == b"content-encoding"
            ]
            if len(content_encodings) > 1:
                return (415, "unsupported_media_type")
            if content_encodings:
                encoding = content_encodings[0].decode("ascii").strip().casefold()
                if encoding != "identity":
                    return (415, "unsupported_media_type")
        except UnicodeDecodeError:
            return (415, "unsupported_media_type")

        parts = [part.strip() for part in content_type.split(";")]
        if parts[0].casefold() != "application/json":
            return (415, "unsupported_media_type")
        for parameter in parts[1:]:
            name, separator, value = parameter.partition("=")
            if (
                not separator
                or name.strip().casefold() != "charset"
                or value.strip().strip("\"'").casefold() != "utf-8"
            ):
                return (415, "unsupported_media_type")
        if len(parts) > 2:
            return (415, "unsupported_media_type")
        return None


async def _unused_receive() -> dict[str, Any]:
    return {"type": "http.disconnect"}


_AMBIGUOUS = object()
