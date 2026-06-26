<?php

namespace App\Models;

use App\Support\CloudinaryModelMedia;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreBanner extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'image',
        'banner_url',
        'banner_public_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(fn (self $banner) => CloudinaryModelMedia::sync($banner, 'image', 'banner_url', 'banner_public_id'));
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
