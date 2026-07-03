# Naming Conventions

> **Version:** 1.0 (Draft)
>
> **Status:** Active
>
> **Owner:** Engineering
>
> **Scope:** Naming Standards

---

# Purpose

Consistent naming improves readability, discoverability and maintainability.

Within WriteZone, naming is considered part of the architecture rather than a cosmetic preference.

Every class, method, file and directory should communicate its purpose clearly.

---

# General Principles

Names should be:

- Clear
- Predictable
- Consistent
- Domain-oriented
- Self-explanatory

Avoid abbreviations unless they are universally recognised.

---

# Classes

Class names should use singular nouns.

Examples:

- FeedCandidate
- RankingEngine
- RelationshipProcessor
- QualitySignal
- ScoreBreakdown

Avoid generic names such as:

- Helper
- Utils
- Manager
- ProcessorHelper

---

# Interfaces

Interfaces represent capabilities.

Examples:

- Collection
- Signal
- Scorer

Avoid prefixing interfaces with "I".

Preferred:

Collection

Avoid:

ICollection

---

# Result Objects

Objects representing completed operations should end with:

Result

Examples:

- FeedResult
- RankingResult
- SearchResult

---

# Candidate Objects

Objects representing work in progress should end with:

Candidate

Examples:

- FeedCandidate
- RankedCandidate
- RecommendationCandidate

---

# Engines

Classes coordinating workflows should end with:

Engine

Examples:

- FeedEngine
- RankingEngine
- RecommendationEngine

---

# Processors

Classes generating intelligence should end with:

Processor

Examples:

- FreshnessProcessor
- RelationshipProcessor
- QualityProcessor

---

# Signals

Signals represent evidence.

Examples:

- FreshnessSignal
- RelationshipSignal
- QualitySignal

---

# Scorers

Scorers transform signals into weighted scores.

Examples:

- FreshnessScorer
- RelationshipScorer
- QualityScorer

---

# Methods

Methods should describe behaviour.

Preferred:

- execute()
- process()
- rank()
- rankAll()
- score()
- candidate()

Avoid unnecessary prefixes.

Preferred:

score()

Avoid:

getScore()

unless returning stored state.

---

# Variables

Variables should describe intent.

Preferred:

$candidate

$ranking

$signals

$collection

Avoid:

$data

$temp

$value1

$item2

unless context is extremely limited.

---

# File Structure

Directories should describe responsibilities.

Examples:

Feed/

Ranking/

Support/

Collections/

Results/

Contracts/

Policies/

Scorers/

---

# Engineering Rule

If a name requires additional explanation, the name should probably be improved.

Readable code begins with readable names.

---

# Related Documents

- FOUNDATION.md
- DESIGN_PRINCIPLES.md
- DEPENDENCY_RULES.md
- IMMUTABILITY.md
