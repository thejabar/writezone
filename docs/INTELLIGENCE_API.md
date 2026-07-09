# WriteZone Intelligence API

Version: 0.7.0

Status: Draft

---

# Overview

The WriteZone Intelligence API provides a single interface for analyzing
written content across every WriteZone product.

Consumers include:

- WriteZone Studio
- Writ Composer
- Article Editor
- Mobile Applications
- Moderator Dashboard
- Future Public SDK

The Intelligence Engine is designed around explainable analysis.

Every response should answer:

1. What was observed?
2. How was it evaluated?
3. Why?
4. What should improve?

---

# Endpoints

## Analyze Content

POST

/api/intelligence/analyze

Description

Analyze submitted content and return an explainable intelligence report.

---

# Request

Content-Type

application/x-www-form-urlencoded

Example

content=Your text goes here...

---

# Successful Response

HTTP

200 OK

```json
{
    "success": true,

    "report": {

        "score": 87,

        "metrics": {

            "characters": 542,
            "words": 91,
            "sentences": 8,
            "paragraphs": 3,
            "mentions": 0,
            "hashtags": 2,
            "links": 1,
            "emojis": 0

        },

        "quality": {

            "breakdown": {

                "structure": 90,
                "readability": 76,
                "vocabulary": 84

            },

            "strengths": [

                "Well organised into multiple paragraphs."

            ],

            "suggestions": [

                "Expand supporting evidence."

            ]

        },

        "metadata": {

            "engineVersion": "0.7.0",

            "evaluators": 3,

            "generatedAt": "2026-07-06T18:20:00Z"

        }

    }

}
```

---

# Validation Errors

HTTP

422

```json
{
    "success": false,

    "message": "No content provided."
}
```

---

# Future Response Fields

The following sections are planned but may not yet be implemented.

## Credibility

- Evidence assessment
- Claim detection
- Source quality
- Bias analysis
- Consistency analysis

---

## Safety

- Personal information exposure
- Harmful certainty
- Defamation indicators
- Risk advisories

---

## Publishing Readiness

- Overall readiness score
- Publication confidence
- Recommended improvements

---

## Benchmarks

Comparison against:

- Articles
- Writs
- Academic writing
- Business writing

---

# API Design Principles

The Intelligence API follows these principles.

- Explain every meaningful conclusion.
- Never overstate certainty.
- Separate observation from recommendation.
- Prefer transparency over confidence.
- Remain backward compatible whenever possible.
- Extend responses without breaking existing clients.

---

# Versioning Strategy

Minor releases

Add new fields.

Major releases

May introduce new endpoints while maintaining compatibility.

Clients should ignore unknown response fields.

---

# Long-Term Vision

The Intelligence API will become the single intelligence interface
used throughout the WriteZone ecosystem.

Every writing experience should consume the same intelligence engine.

One engine.

Many clients.

Consistent intelligence.
