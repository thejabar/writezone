"""Isolated tests for private service authentication."""

import base64
import secrets
import unittest
from unittest.mock import patch

from fastapi import HTTPException

from app.core.config import is_valid_service_token, load_settings
from app.core.security import (
    AuthenticationConfigurationError,
    ServiceAuthenticationError,
    require_service_auth,
    verify_service_credentials,
)


CURRENT_TOKEN = base64.urlsafe_b64encode(bytes(range(32))).decode("ascii").rstrip("=")
NEXT_TOKEN = base64.urlsafe_b64encode(bytes(range(1, 33))).decode("ascii").rstrip("=")


def valid_environment(**overrides: str) -> dict[str, str]:
    values = {
        "WRITEZONE_INTELLIGENCE_VERSION": "0.1.0",
        "WRITEZONE_INTELLIGENCE_SERVICE_TOKEN": CURRENT_TOKEN,
        "WRITEZONE_INTELLIGENCE_KEY_ID": "current-2026",
        "WRITEZONE_INTELLIGENCE_NEXT_SERVICE_TOKEN": NEXT_TOKEN,
        "WRITEZONE_INTELLIGENCE_NEXT_KEY_ID": "next-2026",
        "WRITEZONE_INTELLIGENCE_REVOKED_KEY_IDS": "retired-2025",
    }
    values.update(overrides)
    return values


class ServiceSecurityTests(unittest.TestCase):
    def setUp(self) -> None:
        self.settings = load_settings(valid_environment())

    def test_missing_authentication_configuration_fails_closed(self) -> None:
        with self.assertRaises(AuthenticationConfigurationError):
            verify_service_credentials("Bearer anything", "current-2026", load_settings({}))

    def test_valid_256_bit_token_configuration_is_accepted(self) -> None:
        settings = load_settings(valid_environment())

        self.assertEqual(settings.service_token, CURRENT_TOKEN)
        self.assertEqual(settings.next_service_token, NEXT_TOKEN)

    def test_short_token_is_rejected(self) -> None:
        with self.assertRaises(ValueError):
            load_settings(valid_environment(WRITEZONE_INTELLIGENCE_SERVICE_TOKEN="short"))

    def test_malformed_token_is_rejected(self) -> None:
        with self.assertRaises(ValueError):
            load_settings(
                valid_environment(WRITEZONE_INTELLIGENCE_SERVICE_TOKEN=CURRENT_TOKEN + "=")
            )

    def test_token_must_round_trip_to_the_same_canonical_encoding(self) -> None:
        replacement = "B" if CURRENT_TOKEN[-1] != "B" else "C"
        noncanonical_token = CURRENT_TOKEN[:-1] + replacement
        decoded = base64.b64decode(
            noncanonical_token + "=",
            altchars=b"-_",
            validate=True,
        )
        reencoded = base64.urlsafe_b64encode(decoded).decode("ascii").rstrip("=")

        self.assertTrue(is_valid_service_token(CURRENT_TOKEN))
        self.assertEqual(len(decoded), 32)
        self.assertNotEqual(reencoded, noncanonical_token)
        self.assertFalse(is_valid_service_token(noncanonical_token))
        with self.assertRaises(ValueError):
            load_settings(
                valid_environment(
                    WRITEZONE_INTELLIGENCE_SERVICE_TOKEN=noncanonical_token
                )
            )

    def test_non_ascii_token_is_rejected(self) -> None:
        with self.assertRaises(ValueError):
            load_settings(valid_environment(WRITEZONE_INTELLIGENCE_SERVICE_TOKEN="é" * 43))

    def test_invalid_key_id_is_rejected(self) -> None:
        with self.assertRaises(ValueError):
            load_settings(valid_environment(WRITEZONE_INTELLIGENCE_KEY_ID="bad key"))

    def test_current_key_is_accepted_during_overlap(self) -> None:
        verify_service_credentials(
            f"Bearer {CURRENT_TOKEN}",
            "current-2026",
            self.settings,
        )

    def test_next_key_is_accepted_during_overlap(self) -> None:
        verify_service_credentials(
            f"Bearer {NEXT_TOKEN}",
            "next-2026",
            self.settings,
        )

    def test_invalid_token_is_rejected(self) -> None:
        with self.assertRaises(ServiceAuthenticationError):
            verify_service_credentials(f"Bearer {NEXT_TOKEN}", "current-2026", self.settings)

    def test_unknown_key_id_is_rejected(self) -> None:
        with self.assertRaises(ServiceAuthenticationError):
            verify_service_credentials(
                f"Bearer {CURRENT_TOKEN}",
                "unknown-2026",
                self.settings,
            )

    def test_revoked_key_id_is_rejected(self) -> None:
        with self.assertRaises(ServiceAuthenticationError):
            verify_service_credentials(
                f"Bearer {CURRENT_TOKEN}",
                "retired-2025",
                self.settings,
            )

    def test_duplicate_current_and_next_identifiers_are_rejected(self) -> None:
        with self.assertRaises(ValueError):
            load_settings(
                valid_environment(WRITEZONE_INTELLIGENCE_NEXT_KEY_ID="current-2026")
            )

    def test_identical_current_and_next_tokens_are_rejected(self) -> None:
        with self.assertRaises(ValueError):
            load_settings(
                valid_environment(
                    WRITEZONE_INTELLIGENCE_NEXT_SERVICE_TOKEN=CURRENT_TOKEN
                )
            )

    def test_revoked_active_identifier_is_rejected(self) -> None:
        with self.assertRaises(ValueError):
            load_settings(
                valid_environment(WRITEZONE_INTELLIGENCE_REVOKED_KEY_IDS="current-2026")
            )

    def test_duplicate_revoked_identifiers_are_rejected(self) -> None:
        with self.assertRaises(ValueError):
            load_settings(
                valid_environment(
                    WRITEZONE_INTELLIGENCE_REVOKED_KEY_IDS="retired-2025,retired-2025"
                )
            )

    def test_partial_key_configuration_is_rejected(self) -> None:
        with self.assertRaises(ValueError):
            load_settings(valid_environment(WRITEZONE_INTELLIGENCE_NEXT_KEY_ID=""))

        with self.assertRaises(ValueError):
            load_settings(valid_environment(WRITEZONE_INTELLIGENCE_KEY_ID=""))

    def test_missing_or_wrong_bearer_scheme_is_rejected(self) -> None:
        for header in (None, f"Basic {CURRENT_TOKEN}"):
            with self.subTest(header=header), self.assertRaises(ServiceAuthenticationError):
                verify_service_credentials(header, "current-2026", self.settings)

    def test_constant_time_comparison_primitive_is_used(self) -> None:
        with patch(
            "app.core.security.secrets.compare_digest",
            wraps=secrets.compare_digest,
        ) as compare_digest:
            verify_service_credentials(
                f"Bearer {CURRENT_TOKEN}",
                "current-2026",
                self.settings,
            )

        self.assertEqual(compare_digest.call_count, 1)

    def test_missing_authentication_configuration_maps_to_503(self) -> None:
        with patch("app.core.security.get_settings", return_value=load_settings({})):
            with self.assertRaises(HTTPException) as error:
                require_service_auth(None, None)

        self.assertEqual(error.exception.status_code, 503)
        self.assertEqual(error.exception.detail, {"code": "service_auth_unavailable"})

    def test_invalid_authentication_configuration_maps_to_503(self) -> None:
        with patch("app.core.security.get_settings", side_effect=ValueError("invalid")):
            with self.assertRaises(HTTPException) as error:
                require_service_auth(None, None)

        self.assertEqual(error.exception.status_code, 503)
        self.assertEqual(error.exception.detail, {"code": "service_auth_unavailable"})

    def test_invalid_credentials_map_to_generic_401(self) -> None:
        with patch("app.core.security.get_settings", return_value=self.settings):
            with self.assertRaises(HTTPException) as error:
                require_service_auth(f"Bearer {CURRENT_TOKEN}", "unknown-2026")

        self.assertEqual(error.exception.status_code, 401)
        self.assertEqual(error.exception.detail, {"code": "unauthorized"})

    def test_semantic_version_is_strictly_validated(self) -> None:
        self.assertEqual(
            load_settings(valid_environment(WRITEZONE_INTELLIGENCE_VERSION="1.2.3-rc.1+build.7"))
            .service_version,
            "1.2.3-rc.1+build.7",
        )
        for version in ("", "v1.2.3", "01.2.3", "1.2", "1.2.3-01"):
            with self.subTest(version=version), self.assertRaises(ValueError):
                load_settings(valid_environment(WRITEZONE_INTELLIGENCE_VERSION=version))


if __name__ == "__main__":
    unittest.main()
