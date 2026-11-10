"""Version 1 classification contract; no model or classification logic."""

from enum import StrEnum
import re
from typing import Literal
from uuid import UUID

from pydantic import BaseModel, ConfigDict, Field, field_validator, model_validator

from app.core.config import is_valid_semantic_version


_LANGUAGE_TAG_PATTERN = re.compile(
    r"^(?:[A-Za-z]{2,3}(?:-[A-Za-z]{3}){0,3}|[A-Za-z]{4}|[A-Za-z]{5,8})"
    r"(?:-[A-Za-z]{4})?(?:-(?:[A-Za-z]{2}|[0-9]{3}))?"
    r"(?:-(?:[A-Za-z0-9]{5,8}|[0-9][A-Za-z0-9]{3}))*"
    r"(?:-(?:[0-9A-WY-Za-wy-z])(?:-[A-Za-z0-9]{2,8})+)*"
    r"(?:-[xX](?:-[A-Za-z0-9]{1,8})+)?$"
)


class TopicLabel(StrEnum):
    ARTS_CULTURE = "arts_culture"
    BUSINESS_ECONOMICS = "business_economics"
    EDUCATION = "education"
    ENVIRONMENT = "environment"
    HEALTH = "health"
    HISTORY = "history"
    PHILOSOPHY = "philosophy"
    SCIENCE = "science"
    SOCIETY_POLITICS = "society_politics"
    TECHNOLOGY = "technology"
    OTHER = "other"


class ClassificationRequest(BaseModel):
    """Approved WP4A content-classification request contract."""

    model_config = ConfigDict(extra="forbid")

    schema_version: Literal[1]
    request_id: UUID
    taxonomy_version: Literal["wz-topics-v1"]
    language: str
    text: str

    @field_validator("request_id")
    @classmethod
    def require_uuid_v4(cls, value: UUID) -> UUID:
        if value.version != 4:
            raise ValueError("request_id must be a UUIDv4")
        return value

    @field_validator("language")
    @classmethod
    def require_supported_language(cls, value: str) -> str:
        if not _LANGUAGE_TAG_PATTERN.fullmatch(value):
            raise ValueError("language must be a valid BCP 47 language tag")
        if value.casefold() != "en":
            raise ValueError("only English (en) is supported")
        return "en"

    @field_validator("text")
    @classmethod
    def validate_text_limits(cls, value: str) -> str:
        if not value or not value.strip():
            raise ValueError("text must not be empty")
        try:
            encoded = value.encode("utf-8", errors="strict")
        except UnicodeEncodeError as error:
            raise ValueError("text must be valid UTF-8") from error
        if len(encoded) > 32 * 1024:
            raise ValueError("text exceeds the 32 KiB UTF-8 limit")
        if len(value) > 8_000:
            raise ValueError("text exceeds the 8,000 code point limit")
        return value


class ClassificationLabel(BaseModel):
    """One taxonomy label with a calibrated confidence value."""

    model_config = ConfigDict(extra="forbid")

    id: TopicLabel
    confidence: float = Field(ge=0.0, le=1.0, allow_inf_nan=False)

    @field_validator("confidence", mode="before")
    @classmethod
    def require_numeric_confidence(cls, value: object) -> object:
        if isinstance(value, bool) or not isinstance(value, (int, float)):
            raise ValueError("confidence must be a number")
        return value


class ClassificationResponse(BaseModel):
    """Approved WP4A response contract for future WP7 classification."""

    model_config = ConfigDict(extra="forbid")

    schema_version: Literal[1]
    request_id: UUID
    service_version: str = Field(min_length=1, max_length=64)
    taxonomy_version: Literal["wz-topics-v1"]
    language: Literal["en"]
    abstained: bool
    labels: list[ClassificationLabel] = Field(max_length=3)

    @field_validator("request_id")
    @classmethod
    def require_uuid_v4(cls, value: UUID) -> UUID:
        if value.version != 4:
            raise ValueError("request_id must be a UUIDv4")
        return value

    @field_validator("service_version")
    @classmethod
    def require_semantic_version(cls, value: str) -> str:
        if not is_valid_semantic_version(value):
            raise ValueError("service_version must be a valid semantic version")
        return value

    @model_validator(mode="after")
    def validate_labels_and_abstention(self) -> "ClassificationResponse":
        label_ids = [label.id for label in self.labels]
        if len(label_ids) != len(set(label_ids)):
            raise ValueError("labels must be unique")
        confidences = [label.confidence for label in self.labels]
        if confidences != sorted(confidences, reverse=True):
            raise ValueError("labels must be ordered by descending confidence")
        if self.abstained != (len(self.labels) == 0):
            raise ValueError("abstained must match whether labels are empty")
        return self
