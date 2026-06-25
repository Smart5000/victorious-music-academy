<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Instrument;
use App\Models\StudentCourseAccess;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class StudentCourseAccessManager
{
    public function initializeForInstrument(User $user, Instrument $instrument): void
    {
        $user->forceFill(['selected_instrument_id' => $instrument->id])->save();

        $courses = $instrument->courses()->ordered()->get();

        foreach ($courses as $index => $course) {
            $access = StudentCourseAccess::query()->firstOrNew([
                'user_id' => $user->id,
                'course_id' => $course->id,
            ]);

            if (! $access->exists) {
                $access->fill([
                    'status' => $index === 0 ? StudentCourseAccess::STATUS_UNLOCKED : StudentCourseAccess::STATUS_LOCKED,
                    'unlocked_by' => $index === 0 ? StudentCourseAccess::UNLOCKED_BY_SYSTEM : null,
                    'unlocked_at' => $index === 0 ? now() : null,
                ])->save();
            }
        }
    }

    public function isCourseAccessible(User $user, Course $course): bool
    {
        if (! $user->hasActiveSubscription()) {
            return false;
        }

        if ($user->selected_instrument_id !== $course->instrument_id) {
            return false;
        }

        return StudentCourseAccess::query()
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereIn('status', [StudentCourseAccess::STATUS_UNLOCKED, StudentCourseAccess::STATUS_COMPLETED])
            ->exists();
    }

    public function completeCourseIfReady(User $user, Course $course): void
    {
        $lessonIds = $course->lessons()->pluck('id');

        if ($lessonIds->isEmpty()) {
            return;
        }

        $completedLessonCount = $user->lessonProgress()
            ->whereIn('lesson_id', $lessonIds)
            ->where('completed', true)
            ->count();

        if ($completedLessonCount < $lessonIds->count()) {
            return;
        }

        StudentCourseAccess::query()->updateOrCreate(
            ['user_id' => $user->id, 'course_id' => $course->id],
            [
                'status' => StudentCourseAccess::STATUS_COMPLETED,
                'completed_at' => now(),
            ],
        );

        $nextCourse = Course::query()
            ->where('instrument_id', $course->instrument_id)
            ->where('order', '>', $course->order)
            ->ordered()
            ->first();

        if (! $nextCourse) {
            return;
        }

        $nextAccess = StudentCourseAccess::query()->firstOrNew([
            'user_id' => $user->id,
            'course_id' => $nextCourse->id,
        ]);

        if (! $nextAccess->exists || $nextAccess->status === StudentCourseAccess::STATUS_LOCKED) {
            $nextAccess->fill([
                'status' => StudentCourseAccess::STATUS_UNLOCKED,
                'unlocked_by' => StudentCourseAccess::UNLOCKED_BY_SYSTEM,
                'unlocked_at' => now(),
            ])->save();
        }
    }

    public function coursesForDashboard(User $user): Collection
    {
        if (! $user->selected_instrument_id) {
            return new Collection();
        }

        return Course::query()
            ->where('instrument_id', $user->selected_instrument_id)
            ->with(['category', 'studentAccesses' => fn ($query) => $query->where('user_id', $user->id), 'lessons.progress'])
            ->withCount('lessons')
            ->ordered()
            ->get();
    }
}
