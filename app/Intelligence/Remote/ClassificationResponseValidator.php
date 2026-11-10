<?php

declare(strict_types=1);

namespace App\Intelligence\Remote;

use JsonException;
use stdClass;

final class ClassificationResponseValidator
{
    private const TAXONOMY_VERSION = 'wz-topics-v1';
    private const SUPPORTED_LANGUAGE = 'en';
    private const TOPIC_IDS = [
        'arts_culture',
        'business_economics',
        'education',
        'environment',
        'health',
        'history',
        'philosophy',
        'science',
        'society_politics',
        'technology',
        'other',
    ];
    private const SEMVER_PATTERN = '/\A(0|[1-9][0-9]*)\.(0|[1-9][0-9]*)\.(0|[1-9][0-9]*)'
        . '(?:-((?:0|[1-9][0-9]*|[0-9]*[A-Za-z-][0-9A-Za-z-]*)'
        . '(?:\.(?:0|[1-9][0-9]*|[0-9]*[A-Za-z-][0-9A-Za-z-]*))*))?'
        . '(?:\+([0-9A-Za-z-]+(?:\.[0-9A-Za-z-]+)*))?\z/';

    /**
     * Return only the validated protocol fields, or null for any mismatch.
     *
     * @return array<string, mixed>|null
     */
    public function validate(string $body, string $expectedRequestId): ?array
    {
        try {
            $decoded = json_decode($body, false, 16, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return null;
        }

        if (! $decoded instanceof stdClass || ! self::hasExactKeys($decoded, [
            'schema_version',
            'request_id',
            'service_version',
            'taxonomy_version',
            'language',
            'abstained',
            'labels',
        ])) {
            return null;
        }

        if (
            $decoded->schema_version !== 1
            || ! is_string($decoded->request_id)
            || $decoded->request_id !== $expectedRequestId
            || ! self::isUuidV4($decoded->request_id)
            || ! is_string($decoded->service_version)
            || ! self::isValidSemanticVersion($decoded->service_version)
            || $decoded->taxonomy_version !== self::TAXONOMY_VERSION
            || $decoded->language !== self::SUPPORTED_LANGUAGE
            || ! is_bool($decoded->abstained)
            || ! is_array($decoded->labels)
            || ! array_is_list($decoded->labels)
            || count($decoded->labels) > 3
        ) {
            return null;
        }

        $labels = [];
        $seenIds = [];
        $previousConfidence = INF;

        foreach ($decoded->labels as $label) {
            if (
                ! $label instanceof stdClass
                || ! self::hasExactKeys($label, ['id', 'confidence'])
                || ! is_string($label->id)
                || ! in_array($label->id, self::TOPIC_IDS, true)
                || in_array($label->id, $seenIds, true)
                || (! is_int($label->confidence) && ! is_float($label->confidence))
                || ! is_finite((float) $label->confidence)
                || $label->confidence < 0
                || $label->confidence > 1
                || $label->confidence > $previousConfidence
            ) {
                return null;
            }

            $seenIds[] = $label->id;
            $previousConfidence = (float) $label->confidence;
            $labels[] = [
                'id' => $label->id,
                'confidence' => (float) $label->confidence,
            ];
        }

        if ($decoded->abstained !== ($labels === [])) {
            return null;
        }

        return [
            'schema_version' => 1,
            'request_id' => $expectedRequestId,
            'service_version' => $decoded->service_version,
            'taxonomy_version' => self::TAXONOMY_VERSION,
            'language' => self::SUPPORTED_LANGUAGE,
            'abstained' => $decoded->abstained,
            'labels' => $labels,
        ];
    }

    public function isClassificationUnavailable(string $body): bool
    {
        try {
            $decoded = json_decode($body, false, 8, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return false;
        }

        return $decoded instanceof stdClass
            && isset($decoded->error)
            && $decoded->error instanceof stdClass
            && ($decoded->error->code ?? null) === 'classification_unavailable';
    }

    private static function hasExactKeys(stdClass $value, array $expected): bool
    {
        $actual = array_keys(get_object_vars($value));
        sort($actual);
        sort($expected);

        return $actual === $expected;
    }

    private static function isValidSemanticVersion(string $version): bool
    {
        return strlen($version) <= 64 && preg_match(self::SEMVER_PATTERN, $version) === 1;
    }

    private static function isUuidV4(string $value): bool
    {
        return preg_match(
            '/\A[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}\z/i',
            $value
        ) === 1;
    }
}
