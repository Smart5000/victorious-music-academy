<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonProgress extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'lesson_progress';

    protected $fillable = [
        'user_id',
        'lesson_id',
        'watched_percentage',
        'last_watched_second',
        'completed',
    ];

    protected $casts = [
        'completed' => 'boolean',
    ];

    public static function validationRules(?self $progress = null): array
    {
        return [
            'user_id' => ['required', 'uuid', 'exists:users,id'],
            'lesson_id' => ['required', 'uuid', 'exists:lessons,id'],
            'watched_percentage' => ['required', 'integer', 'min:0', 'max:100'],
            'last_watched_second' => ['required', 'integer', 'min:0'],
            'completed' => ['boolean'],
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

    public function scopeCompleted($query)
    {
        return $query->where('completed', true);
    }

    public function scopeInProgress($query)
    {
        return $query->where('completed', false)->where('watched_percentage', '>', 0);
    }
}
