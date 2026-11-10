<?php

declare(strict_types=1);

namespace App\Intelligence\Remote;

final readonly class HttpTransportResponse
{
    /**
     * @var array<string, list<string>> Lowercase header names with all values.
     */
    public array $headers;

    public function __construct(
        public int $statusCode,
        public string $body,
        public ?string $failureCategory = null,
        array $headers = []
    ) {
        $normalized = [];

        foreach ($headers as $name => $values) {
            if (! is_string($name)) {
                continue;
            }

            $normalizedName = strtolower($name);

            if (preg_match('/\A[a-z0-9!#$%&\'*+.^_`|~-]+\z/', $normalizedName) !== 1) {
                continue;
            }

            foreach (is_string($values) ? [$values] : (is_array($values) ? $values : []) as $value) {
                if (is_string($value)) {
                    $normalized[$normalizedName][] = $value;
                }
            }
        }

        $this->headers = $normalized;
    }

    public static function failure(string $category): self
    {
        return new self(0, '', $category);
    }

    /**
     * @return list<string>
     */
    public function headerValues(string $name): array
    {
        return $this->headers[strtolower($name)] ?? [];
    }
}
