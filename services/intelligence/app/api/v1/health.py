"""Minimal health route with no infrastructure or secret disclosure."""

from fastapi import APIRouter

from app.core.config import get_settings
from app.schemas.health import HealthResponse


router = APIRouter()


@router.get("/health", response_model=HealthResponse, include_in_schema=False)
def health() -> HealthResponse:
    """Report process availability without probing databases or other services."""
    settings = get_settings()
    return HealthResponse(
        status="ok",
        service="writezone-intelligence",
        service_version=settings.service_version,
    )
