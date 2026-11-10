"""Isolated tests for health metadata and route registration."""

import unittest

from app.api.v1.health import health, router


class HealthTests(unittest.TestCase):
    def test_health_response_is_minimal_and_valid(self) -> None:
        response = health()

        self.assertEqual(response.status, "ok")
        self.assertEqual(response.service, "writezone-intelligence")
        self.assertTrue(response.service_version)
        self.assertEqual(
            set(response.model_dump()),
            {"status", "service", "service_version"},
        )

    def test_health_route_is_registered_on_v1_router(self) -> None:
        routes = {route.path: route for route in router.routes}

        self.assertIn("/health", routes)
        self.assertIn("GET", routes["/health"].methods)


if __name__ == "__main__":
    unittest.main()
