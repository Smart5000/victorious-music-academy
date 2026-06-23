<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = Auth::user();
        $hasActiveSubscription = $user->hasActiveSubscription();

        $recentProgress = $user->lessonProgress()
            ->with(['lesson.course.instrument', 'lesson.progress'])
            ->when(! $hasActiveSubscription, fn ($query) => $query->whereHas(
                'lesson',
                fn ($query) => $query->availableWithoutSubscription(),
            ))
            ->latest('updated_at')
            ->limit(6)
            ->get();

        $recommendedLessons = Lesson::query()
            ->with(['course.instrument', 'progress' => fn ($query) => $query->where('user_id', $user->id)])
            ->when(! $hasActiveSubscription, fn ($query) => $query->availableWithoutSubscription())
            ->whereDoesntHave('progress', fn ($query) => $query->where('user_id', $user->id)->where('completed', true))
            ->ordered()
            ->limit(4)
            ->get();

        return view('dashboard', [
            'activeSubscription' => $user->activeSubscription(),
            'recentProgress' => $recentProgress,
            'recommendedLessons' => $recommendedLessons,
            'overallProgress' => (int) round($user->lessonProgress()->avg('watched_percentage') ?? 0),
            'completedLessons' => $user->lessonProgress()->completed()->count(),
        ]);
    }
}
