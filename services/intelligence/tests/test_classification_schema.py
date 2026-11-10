"""Isolated tests for the v1 classification contract models."""

import unittest
from uuid import uuid4

from pydantic import ValidationError

from app.schemas.classification import ClassificationRequest, ClassificationResponse


def valid_request(**overrides: object) -> dict[str, object]:
    values: dict[str, object] = {
        "schema_version": 1,
        "request_id": str(uuid4()),
        "taxonomy_version": "wz-topics-v1",
        "language": "en",
        "text": "A short example about science.",
    }
    values.update(overrides)
    return values


def valid_response(**overrides: object) -> dict[str, object]:
    values: dict[str, object] = {
        "schema_version": 1,
        "request_id": str(uuid4()),
        "service_version": "0.1.0",
        "taxonomy_version": "wz-topics-v1",
        "language": "en",
        "abstained": False,
        "labels": [{"id": "science", "confidence": 0.91}],
    }
    values.update(overrides)
    return values


class ClassificationSchemaTests(unittest.TestCase):
    def test_valid_request_is_accepted(self) -> None:
        request = ClassificationRequest.model_validate(valid_request())

        self.assertEqual(request.language, "en")
        self.assertEqual(request.taxonomy_version, "wz-topics-v1")

    def test_invalid_uuid_is_rejected(self) -> None:
        with self.assertRaises(ValidationError):
            ClassificationRequest.model_validate(valid_request(request_id="not-a-uuid"))

    def test_non_v4_uuid_is_rejected(self) -> None:
        with self.assertRaises(ValidationError):
            ClassificationRequest.model_validate(
                valid_request(request_id="00000000-0000-1000-8000-000000000000")
            )

    def test_empty_text_is_rejected(self) -> None:
        for text in ("", "   ", "\n\t"):
            with self.subTest(text=repr(text)), self.assertRaises(ValidationError):
                ClassificationRequest.model_validate(valid_request(text=text))

    def test_text_over_32_kib_is_rejected(self) -> None:
        with self.assertRaises(ValidationError):
            ClassificationRequest.model_validate(valid_request(text="a" * (32 * 1024 + 1)))

    def test_text_over_8000_code_points_is_rejected(self) -> None:
        with self.assertRaises(ValidationError):
            ClassificationRequest.model_validate(valid_request(text="😀" * 8_001))

    def test_unsupported_language_is_rejected(self) -> None:
        with self.assertRaises(ValidationError):
            ClassificationRequest.model_validate(valid_request(language="fr"))

    def test_malformed_language_tag_is_rejected(self) -> None:
        with self.assertRaises(ValidationError):
            ClassificationRequest.model_validate(valid_request(language="en--US"))

    def test_valid_response_is_accepted(self) -> None:
        response = ClassificationResponse.model_validate(valid_response())

        self.assertEqual(response.labels[0].id, "science")
        self.assertEqual(response.labels[0].confidence, 0.91)

    def test_response_service_version_accepts_strict_semver(self) -> None:
        response = ClassificationResponse.model_validate(
            valid_response(service_version="1.2.3-rc.1+build.7")
        )

        self.assertEqual(response.service_version, "1.2.3-rc.1+build.7")

    def test_response_service_version_rejects_invalid_values(self) -> None:
        for version in ("", "v1.2.3", "01.2.3", "1.2", "1.2.3-01", "1" * 65):
            with self.subTest(version=version), self.assertRaises(ValidationError):
                ClassificationResponse.model_validate(
                    valid_response(service_version=version)
                )

    def test_invalid_taxonomy_label_is_rejected(self) -> None:
        with self.assertRaises(ValidationError):
            ClassificationResponse.model_validate(
                valid_response(labels=[{"id": "not_a_topic", "confidence": 0.8}])
            )

    def test_duplicate_labels_are_rejected(self) -> None:
        with self.assertRaises(ValidationError):
            ClassificationResponse.model_validate(
                valid_response(
                    labels=[
                        {"id": "science", "confidence": 0.91},
                        {"id": "science", "confidence": 0.82},
                    ]
                )
            )

    def test_labels_must_be_ordered_by_descending_confidence(self) -> None:
        ClassificationResponse.model_validate(
            valid_response(
                labels=[
                    {"id": "science", "confidence": 0.91},
                    {"id": "education", "confidence": 0.82},
                    {"id": "technology", "confidence": 0.71},
                ]
            )
        )
        with self.assertRaises(ValidationError):
            ClassificationResponse.model_validate(
                valid_response(
                    labels=[
                        {"id": "science", "confidence": 0.71},
                        {"id": "education", "confidence": 0.82},
                    ]
                )
            )

    def test_more_than_three_labels_are_rejected(self) -> None:
        with self.assertRaises(ValidationError):
            ClassificationResponse.model_validate(
                valid_response(
                    labels=[
                        {"id": "science", "confidence": 0.95},
                        {"id": "education", "confidence": 0.85},
                        {"id": "technology", "confidence": 0.75},
                        {"id": "history", "confidence": 0.65},
                    ]
                )
            )

    def test_invalid_confidence_is_rejected(self) -> None:
        for confidence in (-0.01, 1.01, True, float("nan")):
            with self.subTest(confidence=confidence), self.assertRaises(ValidationError):
                ClassificationResponse.model_validate(
                    valid_response(labels=[{"id": "science", "confidence": confidence}])
                )

    def test_abstention_response_is_accepted(self) -> None:
        response = ClassificationResponse.model_validate(
            valid_response(abstained=True, labels=[])
        )

        self.assertTrue(response.abstained)
        self.assertEqual(response.labels, [])

    def test_empty_labels_without_abstention_are_rejected(self) -> None:
        with self.assertRaises(ValidationError):
            ClassificationResponse.model_validate(valid_response(labels=[]))


if __name__ == "__main__":
    unittest.main()
