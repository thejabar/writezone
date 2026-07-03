# Immutability

> **Version:** 1.0 (Draft)
>
> **Status:** Active
>
> **Owner:** Engineering
>
> **Scope:** Immutable Object Design

---

# Purpose

This document defines the immutable object philosophy used throughout WriteZone.

Immutability reduces bugs, improves predictability and makes software easier to reason about.

Whenever practical, objects should be immutable after construction.

---

# Philosophy

An immutable object cannot change its internal state after it has been created.

Instead of modifying existing objects, new objects should be created when different state is required.

This approach improves reliability and reduces unintended side effects.

---

# Why Immutability?

WriteZone uses immutable objects because they provide:

- Predictable behaviour
- Easier debugging
- Thread-safe design
- Reduced side effects
- Improved testability
- Clear object lifecycles

---

# Preferred Pattern

Construct complete objects.

Avoid partially initialised objects.

Preferred:

```php
$ranking = new RankingResult(
    score: 94.5,
    breakdown: $breakdown,
    confidence: 0.98
);
```

Avoid:

```php
$ranking = new RankingResult();

$ranking->setScore(...);

$ranking->setBreakdown(...);

$ranking->setConfidence(...);
```

---

# Readonly Properties

Whenever possible, use readonly properties.

Example:

```php
public readonly FeedItem $item;
```

Readonly properties clearly communicate that object state does not change.

---

# Result Objects

Result Objects should always be immutable.

Examples:

- FeedResult
- RankingResult
- RankedCandidate

These objects represent completed work and should never be modified.

---

# Signals

Signals represent observations.

Examples:

- FreshnessSignal
- RelationshipSignal
- QualitySignal

Signals should never change after creation.

---

# Feed Objects

Feed objects should remain immutable.

Examples:

- FeedItem
- FeedCandidate

Any additional information should be represented by creating a new object rather than modifying an existing one.

---

# Collections

Collections may expose read-only behaviour.

Modification operations should return new collections whenever practical.

---

# Benefits

Immutable objects provide:

- Simpler reasoning
- Better architecture
- Safer refactoring
- Reliable testing
- Lower maintenance cost

---

# Exceptions

Mutable objects are acceptable when they clearly represent changing workflow state.

Examples include:

- Builders
- Pipelines
- Temporary workflow coordinators

These should remain the exception rather than the rule.

---

# Engineering Rule

If an object represents completed knowledge, it should normally be immutable.

---

# Related Documents

- FOUNDATION.md
- DESIGN_PRINCIPLES.md
- RESULT_OBJECTS.md
- COLLECTION_GUIDE.md
