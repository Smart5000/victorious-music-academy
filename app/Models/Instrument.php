<?php

namespace App\Models;

use App\Support\CloudinaryModelMedia;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instrument extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'thumbnail',
        'thumbnail_url',
        'thumbnail_public_id',
        'coming_soon',
        'is_active',
    ];

    protected $casts = [
        'coming_soon' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(fn (self $instrument) => CloudinaryModelMedia::sync($instrument, 'thumbnail', 'thumbnail_url', 'thumbnail_public_id'));
    }

    public static function validationRules(?self $instrument = null): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'string', 'max:255'],
            'coming_soon' => ['boolean'],
            'is_active' => ['boolean'],
        ];
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function thumbnails()
    {
        return $this->morphMany(Thumbnail::class, 'thumbnailable');
    }

    public function scopeAvailable($query)
    {
        return $query->active()->where('coming_soon', false);
    }

    public function scopeComingSoon($query)
    {
        return $query->active()->where('coming_soon', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }
}
