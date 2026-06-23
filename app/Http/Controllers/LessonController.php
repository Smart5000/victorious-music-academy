<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Contracts\View\View;

class LessonController extends Controller
{
    public function show(Lesson $lesson): View
    {
        $lesson->load([
            'course.instrument',
            'course.category',
            'course.lessons' => fn ($query) => $query->ordered()->with('progress'),
        ]);

        abort_if(! $lesson->course->instrument->is_active || $lesson->course->instrument->coming_soon, 404);

        $lessons = $lesson->course->lessons;
        $currentIndex = $lessons->search(fn (Lesson $courseLesson) => $courseLesson->is($lesson));

        return view('lessons.show', [
            'lesson' => $lesson,
            'progress' => $lesson->progress()->where('user_id', auth()->id())->first(),
            'nextLesson' => $currentIndex !== false ? $lessons->get($currentIndex + 1) : null,
        ]);
    }
}
