<?php

namespace App\Http\Controllers;

use App\Models\Instrument;
use App\Models\SubscriptionPlan;
use App\Services\StudentCourseAccessManager;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class AcademyController extends Controller
{
    public function index(): View
    {
        return view('academy.index', [
            'instruments' => Instrument::query()->active()->with(['courses.category', 'courses.lessons.progress'])->withCount('courses')->orderBy('title')->get(),
        ]);
    }

    public function instrument(Instrument $instrument, Request $request, StudentCourseAccessManager $courseAccess): View|RedirectResponse
    {
        abort_if(! $instrument->is_active || $instrument->coming_soon, 404);

        $activeSubscription = $request->user()->activeSubscription();

        if ($activeSubscription && $request->user()->selected_instrument_id && $request->user()->selected_instrument_id !== $instrument->id) {
            return to_route('dashboard')->with('status', 'Your subscription is currently linked to '.$request->user()->selectedInstrument?->title.'. Please contact admin if this was a mistake.');
        }

        if ($activeSubscription && $request->user()->selected_instrument_id === $instrument->id) {
            $courseAccess->initializeForInstrument($request->user(), $instrument);
        }

        return view('academy.instrument', [
            'instrument' => $instrument->load(['courses' => fn ($query) => $query
                ->ordered()
                ->with('category')
                ->withCount('lessons'),
            ]),
            'plans' => SubscriptionPlan::query()->active()->ordered()->get(),
            'activeSubscription' => $activeSubscription,
        ]);
    }
}
