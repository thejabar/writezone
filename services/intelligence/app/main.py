"""FastAPI application entry point for the private intelligence service."""

from fastapi import FastAPI

from app.api.v1.classifications import router as classifications_router
from app.api.v1.health import router as health_router
from app.core.classification_boundary import ClassificationRequestBoundary
from app.core.config import get_settings
from app.core.exception_boundary import SanitizedExceptionBoundary
from app.core.errors import install_error_handlers


settings = get_settings()

fastapi_app = FastAPI(
    title="WriteZone Intelligence Service",
    version=settings.service_version,
    docs_url=None,
    redoc_url=None,
    openapi_url=None,
)
fastapi_app.include_router(health_router, prefix="/internal/v1")
fastapi_app.include_router(classifications_router, prefix="/internal/v1")
fastapi_app.add_middleware(ClassificationRequestBoundary)
install_error_handlers(fastapi_app)

# This wrapper must remain outside Starlette's ServerErrorMiddleware so it can
# catch the exception that Starlette re-raises after sending its generic 500.
app = SanitizedExceptionBoundary(fastapi_app)


def run() -> None:
    """Run the service on loopback only; the bind address is not configurable."""
    import uvicorn

    uvicorn.run(
        app,
        host="127.0.0.1",
        port=settings.port,
        reload=False,
    )


if __name__ == "__main__":
    run()
