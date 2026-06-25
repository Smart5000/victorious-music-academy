<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentCourseAccess extends Model
{
    use HasFactory, HasUuids;

    public const STATUS_LOCKED = 'locked';

    public const STATUS_UNLOCKED = 'unlocked';

    public const STATUS_COMPLETED = 'completed';

    public const UNLOCKED_BY_SYSTEM = 'system';

    public const UNLOCKED_BY_ADMIN = 'admin';

    protected $table = 'student_course_access';

    protected $fillable = [
        'user_id',
        'course_id',
        'status',
        'unlocked_by',
        'unlocked_at',
        'completed_at',
    ];

    protected $casts = [
        'unlocked_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function isAccessible(): bool
    {
        return in_array($this->status, [self::STATUS_UNLOCKED, self::STATUS_COMPLETED], true);
    }
}
