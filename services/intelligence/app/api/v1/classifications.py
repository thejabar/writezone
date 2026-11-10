"""Protected v1 classification boundary; classification itself is not implemented."""

from fastapi import APIRouter, Depends, HTTPException

from app.core.security import require_classification_service_auth
from app.schemas.classification import ClassificationRequest


router = APIRouter()


@router.post(
    "/classifications",
    dependencies=[Depends(require_classification_service_auth)],
    include_in_schema=False,
)
def classify(_request: ClassificationRequest) -> None:
    """Validate an authenticated request and fail closed until WP7 exists."""
    raise HTTPException(
        status_code=503,
        detail={"code": "classification_unavailable"},
    )
