<?php

declare(strict_types=1);

namespace App\Intelligence\Language;

use App\Intelligence\Language\Resources\EnglishLanguageResources;
use App\Intelligence\Language\Resources\LanguageResources;
use App\Intelligence\Language\Resources\PlaceholderLanguageResources;
use InvalidArgumentException;

final class LanguageRegistry
{
    /**
     * @var array<string, LanguageDefinition>
     */
    private const DEFINITIONS = [
        'en' => [
            'name' => 'English',
            'nativeName' => 'English',
            'script' => 'Latin',
            'status' => 'active',
        ],
        'zh' => [
            'name' => 'Chinese',
            'nativeName' => '中文',
            'script' => 'Han',
            'status' => 'placeholder',
        ],
        'hi' => [
            'name' => 'Hindi',
            'nativeName' => 'हिन्दी',
            'script' => 'Devanagari',
            'status' => 'placeholder',
        ],
        'es' => [
            'name' => 'Spanish',
            'nativeName' => 'Español',
            'script' => 'Latin',
            'status' => 'placeholder',
        ],
        'fr' => [
            'name' => 'French',
            'nativeName' => 'Français',
            'script' => 'Latin',
            'status' => 'placeholder',
        ],
        'ar' => [
            'name' => 'Arabic',
            'nativeName' => 'العربية',
            'script' => 'Arabic',
            'status' => 'placeholder',
        ],
        'bn' => [
            'name' => 'Bengali',
            'nativeName' => 'বাংলা',
            'script' => 'Bengali',
            'status' => 'placeholder',
        ],
        'pt' => [
            'name' => 'Portuguese',
            'nativeName' => 'Português',
            'script' => 'Latin',
            'status' => 'placeholder',
        ],
        'ru' => [
            'name' => 'Russian',
            'nativeName' => 'Русский',
            'script' => 'Cyrillic',
            'status' => 'placeholder',
        ],
        'ur' => [
            'name' => 'Urdu',
            'nativeName' => 'اردو',
            'script' => 'Arabic',
            'status' => 'placeholder',
        ],
        'id' => [
            'name' => 'Indonesian',
            'nativeName' => 'Bahasa Indonesia',
            'script' => 'Latin',
            'status' => 'placeholder',
        ],
        'de' => [
            'name' => 'German',
            'nativeName' => 'Deutsch',
            'script' => 'Latin',
            'status' => 'placeholder',
        ],
        'ja' => [
            'name' => 'Japanese',
            'nativeName' => '日本語',
            'script' => 'Japanese',
            'status' => 'placeholder',
        ],
        'pa' => [
            'name' => 'Punjabi',
            'nativeName' => 'ਪੰਜਾਬੀ',
            'script' => 'Gurmukhi',
            'status' => 'placeholder',
        ],
        'tr' => [
            'name' => 'Turkish',
            'nativeName' => 'Türkçe',
            'script' => 'Latin',
            'status' => 'placeholder',
        ],
        'ko' => [
            'name' => 'Korean',
            'nativeName' => '한국어',
            'script' => 'Hangul',
            'status' => 'placeholder',
        ],
        'vi' => [
            'name' => 'Vietnamese',
            'nativeName' => 'Tiếng Việt',
            'script' => 'Latin',
            'status' => 'placeholder',
        ],
        'it' => [
            'name' => 'Italian',
            'nativeName' => 'Italiano',
            'script' => 'Latin',
            'status' => 'placeholder',
        ],
        'fa' => [
            'name' => 'Persian',
            'nativeName' => 'فارسی',
            'script' => 'Arabic',
            'status' => 'placeholder',
        ],
        'th' => [
            'name' => 'Thai',
            'nativeName' => 'ไทย',
            'script' => 'Thai',
            'status' => 'placeholder',
        ],
    ];

    public static function default(): LanguageDefinition
    {
        return self::resolve('en');
    }

    public static function resolve(
        string $code
    ): LanguageDefinition {

        $code = strtolower(
            trim($code)
        );

        if (!isset(self::DEFINITIONS[$code])) {
            throw new InvalidArgumentException(
                "Unsupported language code: {$code}"
            );
        }

        $definition = self::DEFINITIONS[$code];

        return new LanguageDefinition(
            code: $code,
            name: $definition['name'],
            nativeName: $definition['nativeName'],
            script: $definition['script'],
            status: $definition['status'],
        );
    }

    /**
     * @return LanguageDefinition[]
     */
    public static function all(): array
    {
        $languages = [];

        foreach (array_keys(self::DEFINITIONS) as $code) {
            $languages[] = self::resolve($code);
        }

        return $languages;
    }

    public static function count(): int
    {
        return count(self::DEFINITIONS);
    }

    public static function resources(
        LanguageDefinition $language
    ): LanguageResources {

        if ($language->code() === 'en') {
            return new EnglishLanguageResources();
        }

        return new PlaceholderLanguageResources();
    }
}
