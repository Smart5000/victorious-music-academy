<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class Media
{
    public static function url(?string $cloudinaryUrl = null, ?string $legacyPath = null): ?string
    {
        if (static::isUrl($cloudinaryUrl)) {
            return $cloudinaryUrl;
        }

        if (static::isUrl($legacyPath)) {
            return $legacyPath;
        }

        if (blank($legacyPath)) {
            return null;
        }

        return asset('storage/'.ltrim($legacyPath, '/'));
    }

    public static function exists(?string $cloudinaryUrl = null, ?string $legacyPath = null): bool
    {
        if (static::isUrl($cloudinaryUrl) || static::isUrl($legacyPath)) {
            return true;
        }

        if (blank($legacyPath)) {
            return false;
        }

        return Storage::disk('public')->exists($legacyPath);
    }

    public static function isUrl(?string $value): bool
    {
        return is_string($value) && str_starts_with($value, 'http');
    }
}
