<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoWatchHistory extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'video_watch_history';

    protected $fillable = [
        'user_id',
        'lesson_id',
        'watched_at',
        'event_type',
        'percentage',
        'watched_second',
    ];

    protected $casts = [
        'watched_at' => 'datetime',
    ];

    public static function validationRules(?self $history = null): array
    {
        return [
            'user_id' => ['required', 'uuid', 'exists:users,id'],
            'lesson_id' => ['required', 'uuid', 'exists:lessons,id'],
            'watched_at' => ['required', 'date'],
            'event_type' => ['required', 'string', 'max:255'],
            'percentage' => ['required', 'integer', 'min:0', 'max:100'],
            'watched_second' => ['required', 'integer', 'min:0'],
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function scopeRecent($query)
    {
        return $query->latest('watched_at');
    }
}
