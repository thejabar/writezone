"""Isolated ASGI tests for the protected classification API boundary."""

import base64
import json
import os
import re
import unittest
from unittest.mock import patch


CURRENT_TOKEN = base64.urlsafe_b64encode(bytes(range(32))).decode("ascii").rstrip("=")
NEXT_TOKEN = base64.urlsafe_b64encode(bytes(range(1, 33))).decode("ascii").rstrip("=")
TEST_ENVIRONMENT = {
    "WRITEZONE_INTELLIGENCE_VERSION": "0.1.0",
    "WRITEZONE_INTELLIGENCE_SERVICE_TOKEN": CURRENT_TOKEN,
    "WRITEZONE_INTELLIGENCE_KEY_ID": "current-test",
    "WRITEZONE_INTELLIGENCE_NEXT_SERVICE_TOKEN": NEXT_TOKEN,
    "WRITEZONE_INTELLIGENCE_NEXT_KEY_ID": "next-test",
    "WRITEZONE_INTELLIGENCE_REVOKED_KEY_IDS": "revoked-test",
}

with patch.dict(os.environ, TEST_ENVIRONMENT):
    from app.main import app

from app.core.classification_boundary import MAX_CLASSIFICATION_BODY_BYTES


VALID_REQUEST = {
    "schema_version": 1,
    "request_id": "550e8400-e29b-41d4-a716-446655440000",
    "taxonomy_version": "wz-topics-v1",
    "language": "en",
    "text": "A short test passage about science.",
}


async def call_asgi(
    *,
    body: bytes = b"",
    body_chunks: list[bytes] | None = None,
    headers: list[tuple[bytes, bytes]] | None = None,
    add_content_length: bool = True,
    path: str = "/internal/v1/classifications",
    method: str = "POST",
    capture_response_headers: dict[str, str] | None = None,
) -> tuple[int, bytes]:
    raw_headers = list(headers or [])
    if add_content_length and not any(name.lower() == b"content-length" for name, _ in raw_headers):
        raw_headers.append((b"content-length", str(len(body)).encode("ascii")))

    chunks = body_chunks if body_chunks is not None else [body]
    messages = [
        {
            "type": "http.request",
            "body": chunk,
            "more_body": index < len(chunks) - 1,
        }
        for index, chunk in enumerate(chunks)
    ]
    message_index = 0
    sent: list[dict[str, object]] = []

    async def receive() -> dict[str, object]:
        nonlocal message_index
        if message_index >= len(messages):
            return {"type": "http.disconnect"}
        message = messages[message_index]
        message_index += 1
        return message

    async def send(message: dict[str, object]) -> None:
        sent.append(message)

    scope: dict[str, object] = {
        "type": "http",
        "asgi": {"version": "3.0"},
        "http_version": "1.1",
        "method": method,
        "scheme": "http",
        "path": path,
        "raw_path": path.encode("ascii"),
        "query_string": b"",
        "root_path": "",
        "headers": raw_headers,
        "client": ("127.0.0.1", 12345),
        "server": ("127.0.0.1", 8765),
    }

    await app(scope, receive, send)
    start = next(message for message in sent if message["type"] == "http.response.start")
    if capture_response_headers is not None:
        capture_response_headers.update(
            {
                name.decode("latin-1").casefold(): value.decode("latin-1")
                for name, value in start.get("headers", [])
            }
        )
    response_body = b"".join(
        message.get("body", b"")
        for message in sent
        if message["type"] == "http.response.body"
    )
    return int(start["status"]), response_body


def authenticated_headers(
    token: str = CURRENT_TOKEN,
    key_id: str = "current-test",
    content_type: bytes = b"application/json",
) -> list[tuple[bytes, bytes]]:
    return [
        (b"authorization", f"Bearer {token}".encode("ascii")),
        (b"x-wz-key-id", key_id.encode("ascii")),
        (b"content-type", content_type),
    ]


def request_body(**overrides: object) -> bytes:
    request = dict(VALID_REQUEST)
    request.update(overrides)
    return json.dumps(request).encode("utf-8")


FAKE_BEARER_MARKER = "Bearer FAKE_TEST_TOKEN_7b2a"
FAKE_CONFIG_MARKER = "FAKE_CONFIG_SECRET_52d9"
FAKE_USER_TEXT_MARKER = "SYNTHETIC_USER_TEXT_c18e"


def raise_with_sensitive_markers(payload: dict[str, str]) -> None:
    raise RuntimeError(
        f"{payload['token']}|{payload['configuration']}|{payload['text']}"
    )


def raise_without_reading_body() -> None:
    raise RuntimeError("synthetic unread-body failure")


app.inner_app.add_api_route(
    "/__test__/sensitive-exception",
    raise_with_sensitive_markers,
    methods=["POST"],
    include_in_schema=False,
)
app.inner_app.add_api_route(
    "/__test__/unread-body-exception",
    raise_without_reading_body,
    methods=["POST"],
    include_in_schema=False,
)


class ClassificationApiTests(unittest.IsolatedAsyncioTestCase):
    def setUp(self) -> None:
        self.environment = patch.dict(os.environ, TEST_ENVIRONMENT)
        self.environment.start()
        self.addCleanup(self.environment.stop)

    async def test_valid_json_is_authenticated_validated_and_unavailable(self) -> None:
        status, body = await call_asgi(
            body=request_body(),
            headers=authenticated_headers(),
        )

        self.assertEqual(status, 503)
        self.assertEqual(
            json.loads(body),
            {"error": {"code": "classification_unavailable"}},
        )

    async def test_missing_authentication_is_rejected_generically(self) -> None:
        response_headers: dict[str, str] = {}
        status, body = await call_asgi(
            body=request_body(),
            headers=[(b"content-type", b"application/json")],
            capture_response_headers=response_headers,
        )

        self.assertEqual(status, 401)
        self.assertEqual(json.loads(body), {"error": {"code": "unauthorized"}})
        self.assertNotIn(VALID_REQUEST["text"].encode(), body)
        self.assertEqual(response_headers.get("connection"), "close")

    async def test_duplicate_authorization_headers_are_rejected_and_closed(self) -> None:
        headers = authenticated_headers() + [
            (b"authorization", b"Bearer another-token")
        ]
        response_headers: dict[str, str] = {}
        status, body = await call_asgi(
            body=request_body(),
            headers=headers,
            capture_response_headers=response_headers,
        )

        self.assertEqual(status, 401)
        self.assertEqual(json.loads(body), {"error": {"code": "unauthorized"}})
        self.assertEqual(response_headers.get("connection"), "close")

    async def test_wrong_authorization_scheme_is_rejected_generically(self) -> None:
        headers = [
            (b"authorization", f"Basic {CURRENT_TOKEN}".encode("ascii")),
            (b"x-wz-key-id", b"current-test"),
            (b"content-type", b"application/json"),
        ]
        status, body = await call_asgi(body=request_body(), headers=headers)

        self.assertEqual(status, 401)
        self.assertEqual(json.loads(body), {"error": {"code": "unauthorized"}})

    async def test_authentication_is_checked_before_body_acceptance(self) -> None:
        status, body = await call_asgi(
            body_chunks=[b"x" * (MAX_CLASSIFICATION_BODY_BYTES + 1)],
            headers=[(b"content-type", b"application/json")],
            add_content_length=False,
        )

        self.assertEqual(status, 401)
        self.assertEqual(json.loads(body), {"error": {"code": "unauthorized"}})

    async def test_malformed_authentication_is_rejected(self) -> None:
        headers = authenticated_headers(token="short")
        status, body = await call_asgi(body=request_body(), headers=headers)

        self.assertEqual(status, 401)
        self.assertEqual(json.loads(body), {"error": {"code": "unauthorized"}})
        self.assertNotIn(b"short", body)

    async def test_unknown_key_id_is_rejected(self) -> None:
        status, body = await call_asgi(
            body=request_body(),
            headers=authenticated_headers(key_id="unknown-test"),
        )

        self.assertEqual(status, 401)
        self.assertEqual(json.loads(body), {"error": {"code": "unauthorized"}})

    async def test_revoked_key_id_is_rejected(self) -> None:
        status, body = await call_asgi(
            body=request_body(),
            headers=authenticated_headers(key_id="revoked-test"),
        )

        self.assertEqual(status, 401)
        self.assertEqual(json.loads(body), {"error": {"code": "unauthorized"}})

    async def test_current_key_is_accepted(self) -> None:
        status, body = await call_asgi(
            body=request_body(),
            headers=authenticated_headers(CURRENT_TOKEN, "current-test"),
        )

        self.assertEqual(status, 503)
        self.assertEqual(json.loads(body)["error"]["code"], "classification_unavailable")

    async def test_next_key_is_accepted_during_rotation(self) -> None:
        status, body = await call_asgi(
            body=request_body(),
            headers=authenticated_headers(NEXT_TOKEN, "next-test"),
        )

        self.assertEqual(status, 503)
        self.assertEqual(json.loads(body)["error"]["code"], "classification_unavailable")

    async def test_missing_authentication_configuration_fails_closed(self) -> None:
        with patch.dict(
            os.environ,
            {
                "WRITEZONE_INTELLIGENCE_SERVICE_TOKEN": "",
                "WRITEZONE_INTELLIGENCE_KEY_ID": "",
                "WRITEZONE_INTELLIGENCE_NEXT_SERVICE_TOKEN": "",
                "WRITEZONE_INTELLIGENCE_NEXT_KEY_ID": "",
                "WRITEZONE_INTELLIGENCE_REVOKED_KEY_IDS": "",
            },
        ):
            status, body = await call_asgi(
                body=request_body(),
                headers=authenticated_headers(),
            )

        self.assertEqual(status, 503)
        self.assertEqual(json.loads(body), {"error": {"code": "service_unavailable"}})
        self.assertNotIn(CURRENT_TOKEN.encode(), body)

    async def test_malformed_json_returns_generic_400(self) -> None:
        status, body = await call_asgi(
            body=b'{"text":"private-sentinel"',
            headers=authenticated_headers(),
        )

        self.assertEqual(status, 400)
        self.assertEqual(json.loads(body), {"error": {"code": "invalid_request"}})
        self.assertNotIn(b"private-sentinel", body)

    async def test_empty_body_returns_generic_validation_error(self) -> None:
        status, body = await call_asgi(
            body=b"",
            headers=authenticated_headers(),
        )

        self.assertEqual(status, 422)
        self.assertEqual(json.loads(body), {"error": {"code": "invalid_request"}})

    async def test_invalid_schema_and_language_are_redacted(self) -> None:
        for payload in (
            request_body(language="fr", text="private-language-sentinel"),
            request_body(schema_version=2, text="private-schema-sentinel"),
            request_body(extra_secret="private-extra-sentinel"),
        ):
            with self.subTest(payload=payload[:30]):
                status, body = await call_asgi(
                    body=payload,
                    headers=authenticated_headers(),
                )
                self.assertEqual(status, 422)
                self.assertEqual(json.loads(body), {"error": {"code": "invalid_request"}})
                self.assertNotIn(b"private-", body)

    async def test_unsupported_media_type_returns_generic_415(self) -> None:
        status, body = await call_asgi(
            body=request_body(),
            headers=authenticated_headers(content_type=b"text/plain"),
        )

        self.assertEqual(status, 415)
        self.assertEqual(json.loads(body), {"error": {"code": "unsupported_media_type"}})

    async def test_unsupported_content_encoding_returns_generic_415(self) -> None:
        headers = authenticated_headers() + [(b"content-encoding", b"gzip")]
        status, body = await call_asgi(body=request_body(), headers=headers)

        self.assertEqual(status, 415)
        self.assertEqual(json.loads(body), {"error": {"code": "unsupported_media_type"}})

    async def test_oversized_content_length_is_rejected(self) -> None:
        headers = authenticated_headers() + [
            (b"content-length", str(MAX_CLASSIFICATION_BODY_BYTES + 1).encode("ascii"))
        ]
        status, body = await call_asgi(body=b"", headers=headers, add_content_length=False)

        self.assertEqual(status, 413)
        self.assertEqual(json.loads(body), {"error": {"code": "payload_too_large"}})

    async def test_oversized_content_length_is_rejected_before_json_parsing(self) -> None:
        headers = authenticated_headers() + [
            (b"content-length", str(MAX_CLASSIFICATION_BODY_BYTES + 1).encode("ascii"))
        ]
        status, body = await call_asgi(
            body=b"not-json-private-sentinel",
            headers=headers,
            add_content_length=False,
        )

        self.assertEqual(status, 413)
        self.assertNotIn(b"private-sentinel", body)

    async def test_oversized_chunked_body_is_rejected(self) -> None:
        headers = authenticated_headers() + [(b"transfer-encoding", b"chunked")]
        status, body = await call_asgi(
            body_chunks=[b"x" * MAX_CLASSIFICATION_BODY_BYTES, b"x"],
            headers=headers,
            add_content_length=False,
        )

        self.assertEqual(status, 413)
        self.assertEqual(json.loads(body), {"error": {"code": "payload_too_large"}})

    async def test_oversized_stream_is_rejected_despite_misleading_length(self) -> None:
        headers = authenticated_headers() + [(b"content-length", b"1")]
        status, body = await call_asgi(
            body_chunks=[b"x" * (MAX_CLASSIFICATION_BODY_BYTES + 1)],
            headers=headers,
            add_content_length=False,
        )

        self.assertEqual(status, 413)
        self.assertEqual(json.loads(body), {"error": {"code": "payload_too_large"}})

    async def test_missing_content_length_still_enforces_stream_limit(self) -> None:
        headers = authenticated_headers()
        status, body = await call_asgi(
            body_chunks=[b"x" * (MAX_CLASSIFICATION_BODY_BYTES // 2)] * 3,
            headers=headers,
            add_content_length=False,
        )

        self.assertEqual(status, 413)
        self.assertEqual(json.loads(body), {"error": {"code": "payload_too_large"}})

    async def test_malformed_and_ambiguous_content_lengths_are_rejected(self) -> None:
        for content_lengths in (
            [b"+12"],
            [b"1, 1"],
            [b"1", b"1"],
        ):
            with self.subTest(content_lengths=content_lengths):
                headers = authenticated_headers() + [
                    (b"content-length", value) for value in content_lengths
                ]
                status, body = await call_asgi(
                    body=b"{}",
                    headers=headers,
                    add_content_length=False,
                )
                self.assertEqual(status, 400)
                self.assertEqual(json.loads(body), {"error": {"code": "invalid_request"}})

    async def test_content_length_and_transfer_encoding_conflict_is_rejected(self) -> None:
        headers = authenticated_headers() + [
            (b"content-length", b"2"),
            (b"transfer-encoding", b"chunked"),
        ]
        response_headers: dict[str, str] = {}
        status, body = await call_asgi(
            body=b"{}",
            headers=headers,
            add_content_length=False,
            capture_response_headers=response_headers,
        )

        self.assertEqual(status, 400)
        self.assertEqual(json.loads(body), {"error": {"code": "invalid_request"}})
        self.assertEqual(response_headers.get("connection"), "close")

    async def test_negative_content_length_is_rejected(self) -> None:
        headers = authenticated_headers() + [(b"content-length", b"-1")]
        status, body = await call_asgi(
            body=b"",
            headers=headers,
            add_content_length=False,
        )

        self.assertEqual(status, 400)
        self.assertEqual(json.loads(body), {"error": {"code": "invalid_request"}})

    async def test_unequal_duplicate_content_lengths_are_rejected_at_asgi_boundary(self) -> None:
        headers = authenticated_headers() + [
            (b"content-length", b"2"),
            (b"content-length", b"3"),
        ]
        status, body = await call_asgi(
            body=b"{}",
            headers=headers,
            add_content_length=False,
        )

        self.assertEqual(status, 400)
        self.assertEqual(json.loads(body), {"error": {"code": "invalid_request"}})

    async def test_stream_length_mismatch_is_rejected(self) -> None:
        headers = authenticated_headers() + [(b"content-length", b"1")]
        status, body = await call_asgi(
            body=b"{}",
            headers=headers,
            add_content_length=False,
        )

        self.assertEqual(status, 400)
        self.assertEqual(json.loads(body), {"error": {"code": "invalid_request"}})

    async def test_valid_chunked_request_reaches_unavailable_handler(self) -> None:
        body = request_body()
        headers = authenticated_headers() + [(b"transfer-encoding", b"chunked")]
        status, response = await call_asgi(
            body_chunks=[body[:12], body[12:]],
            headers=headers,
            add_content_length=False,
        )

        self.assertEqual(status, 503)
        self.assertEqual(
            json.loads(response),
            {"error": {"code": "classification_unavailable"}},
        )

    async def test_rejected_method_closes_connection(self) -> None:
        response_headers: dict[str, str] = {}
        status, body = await call_asgi(
            body=request_body(),
            headers=authenticated_headers(),
            method="GET",
            capture_response_headers=response_headers,
        )

        self.assertEqual(status, 405)
        self.assertEqual(json.loads(body), {"error": {"code": "method_not_allowed"}})
        self.assertEqual(response_headers.get("connection"), "close")

    async def test_unexpected_exception_logs_only_sanitized_diagnostic(self) -> None:
        body = json.dumps(
            {
                "token": FAKE_BEARER_MARKER,
                "configuration": FAKE_CONFIG_MARKER,
                "text": FAKE_USER_TEXT_MARKER,
            }
        ).encode("utf-8")
        response_headers: dict[str, str] = {}

        with self.assertLogs("uvicorn.error", level="ERROR") as captured:
            status, response_body = await call_asgi(
                body=body,
                headers=[(b"content-type", b"application/json")],
                path="/__test__/sensitive-exception",
                capture_response_headers=response_headers,
            )

        self.assertEqual(status, 500)
        self.assertEqual(
            json.loads(response_body),
            {"error": {"code": "internal_error"}},
        )
        request_id = response_headers.get("x-wz-request-id", "")
        self.assertRegex(request_id, r"^[0-9a-f]{32}$")
        self.assertEqual(len(captured.records), 1)
        record = captured.records[0]
        self.assertIsNone(record.exc_info)
        self.assertIsNone(record.exc_text)
        log_text = "\n".join(captured.output)
        for marker in (
            FAKE_BEARER_MARKER,
            FAKE_CONFIG_MARKER,
            FAKE_USER_TEXT_MARKER,
            "RuntimeError",
            "Traceback",
        ):
            self.assertNotIn(marker, log_text)
        self.assertIn("category=unhandled_exception", log_text)
        self.assertIn(f"request_id={request_id}", log_text)
        self.assertRegex(
            log_text,
            r"timestamp=\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}",
        )
        for marker in (
            FAKE_BEARER_MARKER,
            FAKE_CONFIG_MARKER,
            FAKE_USER_TEXT_MARKER,
        ):
            self.assertNotIn(marker.encode("utf-8"), response_body)

    async def test_unexpected_exception_closes_connection_with_unread_body(self) -> None:
        response_headers: dict[str, str] = {}

        with self.assertLogs("uvicorn.error", level="ERROR"):
            status, response_body = await call_asgi(
                body=request_body(),
                headers=[(b"content-type", b"application/json")],
                path="/__test__/unread-body-exception",
                capture_response_headers=response_headers,
            )

        self.assertEqual(status, 500)
        self.assertEqual(
            json.loads(response_body),
            {"error": {"code": "internal_error"}},
        )
        self.assertEqual(response_headers.get("connection"), "close")


if __name__ == "__main__":
    unittest.main()
