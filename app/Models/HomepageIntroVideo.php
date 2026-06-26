<?php

namespace App\Models;

use App\Support\CloudinaryModelMedia;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageIntroVideo extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'video',
        'video_url',
        'video_public_id',
        'poster',
        'poster_url',
        'poster_public_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $introVideo): void {
            CloudinaryModelMedia::sync($introVideo, 'video', 'video_url', 'video_public_id', 'video');
            CloudinaryModelMedia::sync($introVideo, 'poster', 'poster_url', 'poster_public_id');
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
