<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\StudentCourseAccess;
use App\Services\StudentCourseAccessManager;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function __invoke(StudentCourseAccessManager $courseAccess): View
    {
        $user = Auth::user();
        $hasActiveSubscription = $user->hasActiveSubscription();
        $selectedInstrument = $user->selectedInstrument;
        $dashboardCourses = $courseAccess->coursesForDashboard($user);
        $currentCourse = $dashboardCourses->first(fn ($course) => optional($course->studentAccesses->first())->status === StudentCourseAccess::STATUS_UNLOCKED);

        $recentProgress = $user->lessonProgress()
            ->with(['lesson.course.instrument', 'lesson.progress'])
            ->whereHas('lesson.course.instrument')
            ->when(! $hasActiveSubscription, fn ($query) => $query->whereHas(
                'lesson',
                fn ($query) => $query->availableWithoutSubscription(),
            ))
            ->latest('updated_at')
            ->limit(6)
            ->get();

        $recommendedLessons = Lesson::query()
            ->with(['course.instrument', 'progress' => fn ($query) => $query->where('user_id', $user->id)])
            ->whereHas('course.instrument')
            ->when(! $hasActiveSubscription, fn ($query) => $query->availableWithoutSubscription())
            ->when(
                $currentCourse,
                fn ($query) => $query->where('course_id', $currentCourse->id),
                fn ($query) => $query->whereKey([]),
            )
            ->whereDoesntHave('progress', fn ($query) => $query->where('user_id', $user->id)->where('completed', true))
            ->ordered()
            ->limit(4)
            ->get();

        return view('dashboard', [
            'activeSubscription' => $user->activeSubscription(),
            'selectedInstrument' => $selectedInstrument,
            'dashboardCourses' => $dashboardCourses,
            'currentCourse' => $currentCourse,
            'recentProgress' => $recentProgress,
            'recommendedLessons' => $recommendedLessons,
            'overallProgress' => (int) round($user->lessonProgress()->avg('watched_percentage') ?? 0),
            'completedLessons' => $user->lessonProgress()->completed()->count(),
        ]);
    }
}
