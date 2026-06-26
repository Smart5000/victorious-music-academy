<?php

namespace App\Support;

class CloudinaryUploadRegistry
{
    protected static array $uploads = [];

    public static function remember(array $upload): void
    {
        if (! isset($upload['secure_url'])) {
            return;
        }

        static::$uploads[$upload['secure_url']] = $upload;
    }

    public static function get(?string $secureUrl): ?array
    {
        if (! $secureUrl) {
            return null;
        }

        return static::$uploads[$secureUrl] ?? null;
    }
}
