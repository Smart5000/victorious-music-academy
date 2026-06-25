<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\VideoWatchHistory;
use App\Http\Requests\StoreLessonProgressRequest;
use App\Services\StudentCourseAccessManager;
use Illuminate\Http\JsonResponse;

class LessonProgressController extends Controller
{
    public function store(StoreLessonProgressRequest $request, Lesson $lesson, StudentCourseAccessManager $courseAccess): JsonResponse
    {
        $data = $request->validated();

        $progress = LessonProgress::query()->firstOrNew([
            'user_id' => $request->user()->id,
            'lesson_id' => $lesson->id,
        ]);

        $newPercent = max((int) $progress->watched_percentage, (int) $data['watched_percentage']);

        $progress->fill([
            'watched_percentage' => $newPercent,
            'last_watched_second' => (int) ($data['last_watched_second'] ?? $progress->last_watched_second),
            'completed' => $newPercent >= 95,
        ]);

        $progress->save();

        VideoWatchHistory::query()->create([
            'user_id' => $request->user()->id,
            'lesson_id' => $lesson->id,
            'watched_at' => now(),
            'event_type' => $newPercent >= 95 ? 'completed' : ($data['event_type'] ?? 'progress'),
            'percentage' => $newPercent,
            'watched_second' => (int) ($data['last_watched_second'] ?? 0),
        ]);

        if ($progress->completed) {
            $courseAccess->completeCourseIfReady($request->user(), $lesson->course()->firstOrFail());
        }

        return response()->json([
            'watched_percentage' => $progress->watched_percentage,
            'completed' => $progress->completed,
        ]);
    }
}
