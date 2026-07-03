# Collection Guide

> **Version:** 1.0 (Draft)
>
> **Status:** Active
>
> **Owner:** Engineering
>
> **Scope:** Collection Framework

---

# Purpose

This document defines the Collection framework used throughout WriteZone.

Collections provide a consistent, strongly-typed abstraction over groups of objects and replace the use of raw arrays wherever practical.

---

# Philosophy

Collections represent ordered groups of related objects.

Instead of exposing implementation details through arrays, collections provide a stable and expressive API.

---

# Why Collections?

Collections improve:

- Readability
- Reusability
- Discoverability
- Type safety
- Testability
- API consistency

---

# Collection Contract

Every collection implementation should satisfy the `Collection` interface.

Minimum responsibilities include:

- Return all items
- Report item count
- Determine emptiness
- Access first item
- Access last item
- Access items by key
- Determine key existence
- Export to array

---

# Implementations

Current implementation:

- ArrayCollection

Future implementations may include:

- LazyCollection
- CursorCollection
- ImmutableCollection
- PagedCollection
- CachedCollection

---

# Design Principles

Collections should:

- Be predictable
- Be reusable
- Hide storage details
- Remain framework independent where practical

Collections should not contain business logic.

---

# Usage

Prefer:

```php
$results->count();

$results->first();

$results->last();
```

Instead of directly manipulating arrays.

---

# Engineering Rule

If multiple objects are returned together, prefer a Collection instead of a raw array.

---

# Related Documents

- RESULT_OBJECTS.md
- PIPELINE_GUIDE.md
- FOUNDATION.md
- IMMUTABILITY.md
