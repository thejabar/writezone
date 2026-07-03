# Result Objects

> **Version:** 1.0 (Draft)
>
> **Status:** Active
>
> **Owner:** Engineering
>
> **Scope:** Result Object Architecture

---

# Purpose

This document defines the Result Object pattern used throughout WriteZone.

Result Objects encapsulate the output of completed operations.

Instead of returning primitive values or loosely structured arrays, WriteZone returns strongly typed objects that clearly represent completed work.

---

# Philosophy

Every significant operation should return a Result Object.

Result Objects communicate intent, improve readability and provide a stable contract between subsystems.

---

# Why Result Objects?

Result Objects provide:

- Clear domain modelling
- Stable public APIs
- Improved readability
- Better testing
- Easier extension
- Reduced reliance on associative arrays

---

# Examples

Current Result Objects include:

- FeedResult
- RankingResult
- RankedCandidate

Future Result Objects may include:

- SearchResult
- RecommendationResult
- ModerationResult
- NotificationResult

---

# Design Principles

Result Objects should:

- Be immutable
- Represent completed work
- Expose meaningful methods
- Hide implementation details
- Avoid business logic

---

# Good Example

```php
$ranking = new RankingResult(
    score: 92.4,
    breakdown: $breakdown,
    confidence: 0.98
);
```

Consumers interact with the object:

```php
$ranking->score();

$ranking->confidence();

$ranking->breakdown();
```

They do not inspect internal arrays.

---

# Avoid

Returning anonymous arrays:

```php
return [
    'score' => 92.4,
    'confidence' => 0.98,
];
```

Associative arrays make contracts difficult to evolve safely.

---

# Evolution

As requirements grow, Result Objects may gain additional behaviour without changing their public purpose.

For example:

FeedResult may later include:

- Pagination
- Cursor information
- Diagnostics
- Execution time
- Metadata

Consumers continue interacting with the same object.

---

# Engineering Rule

If an operation represents completed work, prefer returning a dedicated Result Object instead of a primitive value or associative array.

---

# Related Documents

- IMMUTABILITY.md
- COLLECTION_GUIDE.md
- PIPELINE_GUIDE.md
- FOUNDATION.md
