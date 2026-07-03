# Dependency Rules

> **Version:** 1.0 (Draft)
>
> **Status:** Active
>
> **Owner:** Engineering
>
> **Scope:** Dependency Management

---

# Purpose

This document defines how dependencies flow throughout the WriteZone architecture.

Correct dependency management prevents tight coupling, reduces technical debt and improves long-term maintainability.

---

# Dependency Philosophy

Dependencies must always move in one direction.

```
Application
        ↓
Platform
        ↓
Foundation
```

Dependencies must never flow upward.

---

# Layer Responsibilities

## Foundation

The Foundation layer provides reusable infrastructure.

It has no knowledge of Platform or Application.

Examples:

- Core
- Support
- Collections
- Contracts
- Configuration
- Utilities

---

## Platform

The Platform layer contains business capabilities.

It may depend on Foundation.

It must never depend on Application.

Examples:

- Feed
- Ranking
- Intelligence
- Search
- Moderation
- Notifications

---

## Application

The Application layer coordinates user interactions.

It may depend on Platform and Foundation.

Business logic should remain inside Platform.

Examples:

- Controllers
- Routes
- Views
- API
- Console Commands

---

# Allowed Dependencies

```
Application
    ↓
Platform
    ↓
Foundation
```

Examples:

✓ Controller → FeedEngine

✓ FeedEngine → RankingEngine

✓ RankingEngine → Collection

---

# Forbidden Dependencies

The following dependencies are prohibited.

```
Foundation
        ↓
Platform
```

```
Platform
        ↓
Application
```

```
Foundation
        ↓
Controllers
```

These create circular architecture and increase coupling.

---

# Dependency Inversion

Whenever practical, modules should depend on abstractions.

Preferred:

```
FeedResult
        ↓
Collection
```

Avoid:

```
FeedResult
        ↓
ArrayCollection
```

This allows implementations to evolve without changing dependent modules.

---

# Cross-Module Communication

Modules should communicate through:

- Interfaces
- Result Objects
- Contracts
- Events

Direct knowledge of another module's internal implementation should be avoided.

---

# Architecture Decision Records

Changes affecting dependency direction require an Architecture Decision Record (ADR).

This ensures that structural changes are reviewed before implementation.

---

# Engineering Checklist

Before introducing a new dependency, verify:

- Does the dependency follow the architectural layers?
- Can an abstraction be used instead?
- Will this increase coupling?
- Is the dependency reusable?
- Will the design still make sense in five years?

---

# Related Documents

- FOUNDATION.md
- DESIGN_PRINCIPLES.md
- RESULT_OBJECTS.md
- PIPELINE_GUIDE.md
