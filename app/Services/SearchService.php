<?php
declare(strict_types=1);
namespace App\Services;
use App\Models\Hashtag;
use App\Models\User;
use App\Models\Writ;
class SearchService
{
    public static function searchUsers(
        string $query
    ): array {
        return User::search(
            $query
        );
    }
    public static function searchWrits(
        string $query
    ): array {
        return Writ::search(
            $query
        );
    }
    public static function searchHashtags(
        string $query
    ): array {
        return Hashtag::search(
            $query
        );
    }
    public static function searchEverything(
        string $query
    ): array {
        return [
            'users' => static::searchUsers(
                $query
            ),
            'writs' => static::searchWrits(
                $query
            ),
            'hashtags' => static::searchHashtags(
                $query
            ),
        ];
    }
}