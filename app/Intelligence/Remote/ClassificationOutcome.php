<?php

declare(strict_types=1);

namespace App\Intelligence\Remote;

final readonly class ClassificationOutcome
{
    private function __construct(
        private bool $available,
        private ?array $classification,
        private ?string $requestId,
        private ?string $failureCategory,
        private ?int $httpStatus,
        private int $latencyMs
    ) {
    }

    public static function fromValidatedResponse(
        ValidatedClassificationResponse $classification,
        int $latencyMs
    ): self {
        return new self(
            true,
            $classification->toArray(),
            $classification->requestId(),
            null,
            200,
            max(0, $latencyMs)
        );
    }

    public static function unavailable(
        ?string $requestId,
        string $failureCategory,
        ?int $httpStatus,
        int $latencyMs
    ): self {
        return new self(
            false,
            null,
            $requestId,
            $failureCategory,
            $httpStatus,
            $latencyMs
        );
    }

    public function isAvailable(): bool
    {
        return $this->available;
    }

    public function isUnavailable(): bool
    {
        return ! $this->available;
    }

    public function classification(): ?array
    {
        return $this->classification;
    }

    public function isAbstained(): ?bool
    {
        if (! $this->available) {
            return null;
        }

        return $this->classification['abstained'];
    }

    public function requestId(): ?string
    {
        return $this->requestId;
    }

    public function failureCategory(): ?string
    {
        return $this->failureCategory;
    }

    public function httpStatus(): ?int
    {
        return $this->httpStatus;
    }

    public function latencyMs(): int
    {
        return $this->latencyMs;
    }
}
