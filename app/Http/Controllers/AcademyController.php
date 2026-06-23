<?php

namespace App\Http\Controllers;

use App\Models\Instrument;
use App\Models\SubscriptionPlan;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AcademyController extends Controller
{
    public function index(): View
    {
        return view('academy.index', [
            'instruments' => Instrument::query()->active()->with(['courses.category', 'courses.lessons.progress'])->withCount('courses')->orderBy('title')->get(),
        ]);
    }

    public function instrument(Instrument $instrument, Request $request): View
    {
        abort_if(! $instrument->is_active || $instrument->coming_soon, 404);

        return view('academy.instrument', [
            'instrument' => $instrument->load(['courses' => fn ($query) => $query
                ->ordered()
                ->with(['category', 'lessons' => fn ($lessons) => $lessons->ordered()->with('progress')]),
            ]),
            'plans' => SubscriptionPlan::query()->active()->ordered()->get(),
            'activeSubscription' => $request->user()->activeSubscription(),
        ]);
    }
}
