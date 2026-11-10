<?php

declare(strict_types=1);

namespace App\Intelligence\Remote;

/**
 * A response value that can only be created after protocol validation.
 */
final readonly class ValidatedClassificationResponse
{
    /** @param array<string, mixed> $classification */
    private function __construct(private array $classification)
    {
    }

    public static function fromJson(
        string $body,
        string $expectedRequestId,
        ?ClassificationResponseValidator $validator = null
    ): ?self {
        $classification = ($validator ?? new ClassificationResponseValidator())
            ->validate($body, $expectedRequestId);

        return $classification === null ? null : new self($classification);
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return $this->classification;
    }

    public function requestId(): string
    {
        return $this->classification['request_id'];
    }
}
