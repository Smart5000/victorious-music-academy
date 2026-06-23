<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'course_id',
        'title',
        'slug',
        'video_url',
        'duration',
        'description',
        'lesson_order',
        'is_free_preview',
        'is_premium',
    ];

    protected $casts = [
        'is_free_preview' => 'boolean',
        'is_premium' => 'boolean',
    ];

    public static function validationRules(?self $lesson = null): array
    {
        return [
            'course_id' => ['required', 'uuid', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'video_url' => ['nullable', 'url', 'max:255'],
            'duration' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'lesson_order' => ['required', 'integer', 'min:0'],
            'is_free_preview' => ['boolean'],
            'is_premium' => ['boolean'],
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function progress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function watchHistory(): HasMany
    {
        return $this->hasMany(VideoWatchHistory::class);
    }

    public function thumbnails()
    {
        return $this->morphMany(Thumbnail::class, 'thumbnailable');
    }

    public function isYoutubeVideo(): bool
    {
        return $this->youtubeVideoId() !== null;
    }

    public function youtubeVideoId(): ?string
    {
        if (! $this->video_url) {
            return null;
        }

        $host = parse_url($this->video_url, PHP_URL_HOST);
        $path = trim(parse_url($this->video_url, PHP_URL_PATH) ?? '', '/');

        if (! $host) {
            return null;
        }

        $host = str($host)->lower()->replace('www.', '')->toString();

        if ($host === 'youtu.be') {
            return $path ?: null;
        }

        if (! str_contains($host, 'youtube.com')) {
            return null;
        }

        parse_str(parse_url($this->video_url, PHP_URL_QUERY) ?? '', $query);

        if (! empty($query['v'])) {
            return $query['v'];
        }

        if (str_starts_with($path, 'embed/')) {
            return str($path)->after('embed/')->before('/')->toString();
        }

        if (str_starts_with($path, 'shorts/')) {
            return str($path)->after('shorts/')->before('/')->toString();
        }

        return null;
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('lesson_order');
    }

    public function scopeFreePreview($query)
    {
        return $query->where('is_free_preview', true);
    }

    public function scopeAvailableWithoutSubscription($query)
    {
        return $query->where(function ($query) {
            $query->where('is_free_preview', true)
                ->orWhere(function ($query) {
                    $query->where('is_premium', false)
                        ->whereHas('course', fn ($course) => $course->where('is_premium', false));
                });
        });
    }

    public function requiresSubscription(): bool
    {
        return ! $this->is_free_preview && ($this->is_premium || $this->course?->is_premium);
    }
}
