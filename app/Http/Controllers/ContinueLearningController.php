<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class ContinueLearningController extends Controller
{
    public function __invoke(): View
    {
        $user = Auth::user();

        $progressItems = $user
            ->lessonProgress()
            ->with(['lesson.course.instrument', 'lesson.progress'])
            ->when(! $user->hasActiveSubscription(), fn ($query) => $query->whereHas(
                'lesson',
                fn ($query) => $query->availableWithoutSubscription(),
            ))
            ->where('watched_percentage', '>', 0)
            ->orderByDesc('updated_at')
            ->paginate(9);

        return view('academy.continue-learning', [
            'progressItems' => $progressItems,
        ]);
    }
}
