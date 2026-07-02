# WriteZone Engineering Handbook

# Volume II

# Architecture

Version: 1.0

Status: Active

---

© 2026 WriteZone

This document forms Volume II of the WriteZone Engineering Handbook.

Its purpose is to document the technical architecture of the WriteZone platform.

Unlike AI_CONTEXT.md, which defines engineering philosophy and AI collaboration principles, this volume focuses on implementation architecture.

It explains how the platform is organised, how architectural layers interact, and how new capabilities should integrate into the existing system.

This document is intended for:

- software engineers
- architects
- AI assistants
- reviewers
- future contributors

Every contributor should understand this document before making significant architectural changes.

---

# Table of Contents

## Part I

Architecture Fundamentals

1. Architectural Philosophy
2. High-Level Architecture
3. Request Lifecycle

---

## Part II

Core Application Layers

4. Routing Layer
5. Middleware Layer
6. Controller Layer
7. Service Layer
8. Engine Layer
9. Pipeline Layer
10. Processor Layer
11. Signal Layer
12. Model Layer
13. View Layer

---

## Part III

Platform Architecture

14. Feed Architecture
15. Intelligence Architecture
16. Authentication Architecture
17. Notification Architecture
18. Search Architecture

---

## Part IV

Infrastructure

19. Database Architecture
20. Security Architecture
21. Performance Strategy
22. Scalability Strategy

---

## Part V

Future Evolution

23. Future Architecture

---

## Appendices

Appendix A
Directory Structure

Appendix B
Dependency Rules

Appendix C
Current Components

Appendix D
Future Components

Appendix E
Architectural Principles Reference

---

**End of Table of Contents**

---

# Part I

# Architecture Fundamentals

This part introduces the architectural philosophy that governs every technical decision made within WriteZone.

Unlike the AI Context handbook, which focuses on vision, collaboration, and engineering principles, this volume explains how the software itself is designed.

Every future architectural change should remain consistent with the concepts established in this part.

---

# Chapter 1

# Architectural Philosophy

## Purpose

The purpose of the WriteZone architecture is to create a platform that remains understandable, maintainable, scalable, and explainable throughout its lifetime.

Architecture exists to reduce complexity rather than increase it.

Every layer, component, and abstraction should simplify future development.

---

## Core Objectives

The architecture has been designed to achieve the following objectives:

- Maintainability
- Scalability
- Explainability
- Modularity
- Testability
- Performance
- Security
- Extensibility

Every architectural decision should strengthen at least one of these objectives without weakening the others.

---

## Design Philosophy

WriteZone is organised around responsibilities rather than technologies.

The architecture does not revolve around PHP, MySQL, or any particular framework.

Instead, it is organised around clearly defined responsibilities.

Examples include:

- Coordinating requests
- Executing workflows
- Producing intelligence
- Persisting data
- Rendering presentation

Each responsibility belongs to one architectural layer.

---

## Layered Architecture

WriteZone follows a layered architecture.

Each layer communicates only through clearly defined interfaces.

Responsibilities move in one direction.

```text
Presentation

↓

Application

↓

Business Logic

↓

Intelligence

↓

Persistence

↓

Infrastructure
```

This structure reduces coupling and allows components to evolve independently.

---

## Explainability

Explainability is considered a first-class architectural requirement.

Every intelligent observation should be traceable.

Every workflow should be understandable.

Every recommendation should be reproducible.

The platform should never depend upon unexplained behaviour.

---

## Modularity

Large systems become maintainable through modularity.

Every capability should exist as an independent component.

Modules should be:

- cohesive
- reusable
- independently testable
- loosely coupled

The objective is to minimise the impact of future change.

---

## Composition Over Complexity

WriteZone prefers composing small components instead of creating large monolithic classes.

Complex behaviour should emerge through cooperation between specialised components.

Examples include:

- Engines coordinating Pipelines
- Pipelines coordinating Processors
- Processors producing Signals
- Signal Collections accumulating evidence

No single class should become responsible for the entire workflow.

---

## Stable Boundaries

Architectural boundaries should remain stable.

Controllers should never become Services.

Services should never become Views.

Processors should never become Models.

Respecting these boundaries protects long-term maintainability.

---

## Evolution

The architecture is expected to evolve continuously.

New capabilities should normally require extension rather than replacement.

Examples include:

- introducing a new Processor
- adding another Signal
- extending an Engine
- creating another Service

Existing abstractions should remain stable while allowing the platform to grow.

---

## Architecture Summary

The WriteZone architecture is designed to support many years of continuous evolution.

Every architectural decision should reinforce:

- simplicity
- consistency
- explainability
- modularity
- maintainability

When uncertainty exists, contributors should choose the solution that best preserves these characteristics.

---

**End of Chapter 1**

---

# Chapter 2

# High-Level Architecture

## Purpose

This chapter provides a complete overview of the WriteZone architecture.

Rather than focusing on individual classes or implementation details, it explains how the major architectural layers cooperate to deliver platform functionality.

Every contributor should understand this chapter before working on any subsystem.

---

## Architectural Overview

WriteZone follows a layered architecture where each layer performs one clearly defined responsibility.

Responsibilities flow downward.

Results flow upward.

```text
                Browser
                   │
                   ▼
              HTTP Request
                   │
                   ▼
                Router
                   │
                   ▼
             Middleware
                   │
                   ▼
             Controller
                   │
                   ▼
               Service
                   │
                   ▼
                Engine
                   │
                   ▼
               Pipeline
                   │
                   ▼
             Processors
                   │
                   ▼
               Signals
                   │
                   ▼
          Signal Collection
                   │
                   ▼
          Enriched Candidate
                   │
                   ▼
          Ranking (Future)
                   │
                   ▼
      Recommendation (Future)
                   │
                   ▼
                View
                   │
                   ▼
               Response
                   │
                   ▼
                Browser
```

---

## Responsibility Flow

Every layer performs exactly one responsibility.

| Layer | Responsibility |
|--------|----------------|
| Router | Route incoming requests |
| Middleware | Validate and protect requests |
| Controller | Coordinate execution |
| Service | Orchestrate business operations |
| Engine | Execute workflows |
| Pipeline | Coordinate processors |
| Processor | Observe and analyse |
| Signal | Represent evidence |
| Model | Persist and retrieve data |
| View | Present prepared information |

No layer should assume responsibilities belonging to another.

---

## Dependency Direction

Dependencies should always move downward.

```text
Controllers

↓

Services

↓

Engines

↓

Pipelines

↓

Processors

↓

Signals

↓

Models
```

Lower layers should never depend upon higher layers.

This rule prevents circular dependencies and keeps the architecture maintainable.

---

## Data Flow

During execution, information moves through several stages.

```text
Database

↓

Models

↓

FeedItem

↓

FeedCandidate

↓

Pipeline

↓

Signals

↓

Enriched Candidate

↓

View

↓

HTML Response
```

Each transformation adds information while preserving previous observations.

---

## Intelligence Flow

The Intelligence Engine enriches data rather than replacing it.

```text
Raw Data

↓

Observation

↓

Signal

↓

Signal Collection

↓

Evidence

↓

Ranking

↓

Recommendation

↓

Presentation
```

This separation allows every intelligent decision to remain explainable.

---

## Current Implementation

The current platform includes the following architectural components:

### Presentation

- Views
- Layouts
- Templates

### Application

- Controllers
- Services

### Workflow

- Engines
- Pipeline

### Intelligence

- RelationshipProcessor
- FreshnessProcessor
- RelationshipSignal
- FreshnessSignal
- SignalCollection

### Domain

- FeedItem
- FeedCandidate
- FeedCandidateFactory

### Persistence

- Models
- Database

These components collectively implement the first generation of the WriteZone Intelligence Architecture.

---

## Architectural Characteristics

The current architecture demonstrates:

- Layered design
- Modular components
- Explainable intelligence
- Independent processors
- Immutable signals
- Lightweight controllers
- Workflow orchestration
- Clear separation of concerns

These characteristics should remain protected.

---

## Extension Points

Future capabilities should integrate into the existing architecture without requiring structural redesign.

Typical extension points include:

- new Engines
- new Processors
- new Signals
- new Services
- additional Pipelines
- specialised Candidates

The architecture has been intentionally designed to support gradual expansion.

---

## Chapter Summary

The high-level architecture establishes the structural framework of WriteZone.

Every subsystem described in subsequent chapters fits into this framework.

Contributors should understand this architectural map before examining individual implementation details.

---

**End of Chapter 2**

---

# Chapter 3

# Request Lifecycle

## Purpose

Every interaction within WriteZone begins as an HTTP request.

This chapter explains how a request travels through the platform from the moment it reaches the server until the final response is returned to the user's browser.

Understanding this lifecycle is essential before contributing to any architectural layer.

---

## Overview

A request passes through multiple independent architectural layers.

Each layer performs one responsibility before handing execution to the next.

The complete lifecycle is intentionally predictable.

```text
Browser

↓

public_html/index.php

↓

Composer Autoloader

↓

Bootstrap

↓

Application

↓

Router

↓

Middleware

↓

Controller

↓

Service

↓

Engine

↓

Pipeline

↓

Processors

↓

Signals

↓

Models

↓

Database

↓

Models

↓

Engine

↓

View

↓

HTML Response

↓

Browser
```

---

## Stage 1

### Browser Request

A visitor performs an action.

Examples include:

- Opening the homepage
- Publishing a writ
- Viewing a profile
- Following a user
- Opening notifications

The browser creates an HTTP request.

---

## Stage 2

### Front Controller

Every request enters the application through:

```text
public_html/index.php
```

The front controller performs only a small number of responsibilities.

It:

- loads Composer
- starts the session
- loads the application
- transfers execution

Business logic should never exist here.

---

## Stage 3

### Bootstrap

Bootstrap prepares the application.

Typical responsibilities include:

- configuration
- dependency loading
- environment setup
- application creation

Bootstrap executes once per request.

---

## Stage 4

### Routing

The Router determines which Controller should receive the request.

Routing should remain declarative.

Routes map URLs to application behaviour.

Example:

```text
GET /

↓

HomeController@index
```

---

## Stage 5

### Middleware

Middleware executes before business logic.

Responsibilities include:

- authentication
- authorisation
- rate limiting
- validation
- request filtering

Middleware may terminate the request early if necessary.

---

## Stage 6

### Controller

Controllers coordinate execution.

Controllers should remain lightweight.

Typical responsibilities include:

- reading parameters
- invoking Services
- returning Views

Controllers should avoid business calculations.

---

## Stage 7

### Service

Services coordinate business operations.

They may combine multiple Engines, Models, or repositories.

Services expose clean business interfaces to Controllers.

---

## Stage 8

### Engine

Engines execute complete workflows.

Current example:

```text
FeedEngine
```

The Feed Engine:

- retrieves feed rows
- creates FeedItems
- creates FeedCandidates
- executes Intelligence Pipelines
- returns enriched candidates

---

## Stage 9

### Pipeline

The Pipeline coordinates Processors.

It contains no business-specific behaviour.

Instead, it executes each Processor sequentially.

Current Pipeline:

```text
RelationshipProcessor

↓

FreshnessProcessor
```

Future processors can be added without changing existing processors.

---

## Stage 10

### Processors

Processors analyse one aspect of a Candidate.

Examples include:

- relationship
- freshness
- quality
- trust
- reputation

Each Processor produces one or more Signals.

Processors remain independent.

---

## Stage 11

### Signals

Signals describe observations.

Examples:

```text
RelationshipSignal

FreshnessSignal
```

Signals never perform orchestration.

They simply represent evidence.

---

## Stage 12

### Models

Models retrieve or persist information.

Examples include:

- User
- Writ
- Follow
- Comment
- Notification

Models should remain focused on persistence.

---

## Stage 13

### Database

Persistent information is stored within MySQL.

Models remain the only architectural layer responsible for database interaction.

Other layers should not communicate directly with persistence.

---

## Stage 14

### View Rendering

Once execution completes, Controllers return prepared data to Views.

Views convert prepared information into HTML.

Views should never:

- calculate intelligence
- execute workflows
- query persistence

Views present information only.

---

## Stage 15

### Browser Response

The generated HTML returns to the browser.

The request lifecycle completes.

Every future request repeats the same architectural pattern.

---

## Architectural Benefits

This lifecycle provides:

- predictability
- maintainability
- explainability
- modularity
- scalability

Every layer performs one responsibility.

No layer becomes excessively complex.

---

## Future Evolution

Future capabilities such as:

- Recommendation Engine
- Trust Engine
- Semantic Search
- AI Ranking
- Knowledge Graph

should integrate naturally into this lifecycle without altering its overall structure.

The request lifecycle should remain stable even as the platform grows.

---

## Chapter Summary

The Request Lifecycle defines the execution model of WriteZone.

Understanding this lifecycle enables contributors to identify where new functionality belongs and prevents architectural responsibilities from becoming blurred.

---

**End of Chapter 3**