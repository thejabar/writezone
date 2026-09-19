<?php

declare(strict_types=1);

namespace App\Intelligence\Language\Resources;

interface LanguageResources
{
    /**
     * @return string[]
     */
    public function fillerWords(): array;

    /**
     * @return string[]
     */
    public function transitionWords(): array;
}
