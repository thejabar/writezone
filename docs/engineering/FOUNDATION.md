# Foundation

> **Version:** 1.0 (Draft)
>
> **Status:** Active
>
> **Owner:** Engineering
>
> **Scope:** Platform Architecture

---

# Purpose

The Foundation defines the architectural rules that govern the entire WriteZone platform.

Everything built within WriteZone must conform to these rules.

The Foundation changes very rarely because it provides the stability upon which all other systems depend.

---

# Architectural Layers

WriteZone is organised into three architectural layers.

```
Foundation
        ↓
Platform
        ↓
Application
```

Dependencies always flow downward.

Application depends on Platform.

Platform depends on Foundation.

Foundation depends on nothing within the application.

---

# Layer 1 — Foundation

The Foundation provides reusable infrastructure and engineering primitives.

Examples include:

- Core framework
- Support library
- Collections
- Contracts
- Events
- Exceptions
- Configuration
- Utilities

Characteristics:

- Highly stable
- Framework independent where practical
- Shared across multiple subsystems
- Rarely modified

---

# Layer 2 — Platform

The Platform contains the business capabilities of WriteZone.

Examples include:

- Feed
- Intelligence
- Ranking
- Search
- Recommendations
- Moderation
- Notifications
- Users

Characteristics:

- Evolves regularly
- Uses Foundation services
- Contains business logic
- Independent of presentation

---

# Layer 3 — Application

The Application exposes the platform to users.

Examples include:

- Controllers
- Routes
- Views
- APIs
- Console Commands
- Admin Interface

Characteristics:

- User-facing
- Frequently updated
- Coordinates platform services
- Contains minimal business logic

---

# Dependency Rules

The following dependency direction is mandatory:

```
Application
        ↓
Platform
        ↓
Foundation
```

The reverse dependency is never permitted.

Foundation must never depend on Platform.

Platform must never depend on Application.

---

# Engineering Philosophy

The Foundation exists to maximise:

- Simplicity
- Stability
- Predictability
- Reusability
- Testability

New infrastructure should only be introduced when it benefits multiple subsystems.

---

# Evolution Strategy

Changes to the Foundation should be rare.

When a foundational change is required, an Architecture Decision Record (ADR) should be created before implementation.

---

# Related Documents

- DESIGN_PRINCIPLES.md
- DEPENDENCY_RULES.md
- ARCHITECTURE.md
- ADR/
