<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function show(Course $course, Request $request): View
    {
        $course->load('instrument');

        abort_if(! $course->instrument->is_active || $course->instrument->coming_soon, 404);

        return view('courses.show', [
            'course' => $course->load([
                'category',
                'lessons' => fn ($query) => $query
                    ->when(! $request->user()->hasActiveSubscription(), fn ($query) => $query->availableWithoutSubscription())
                    ->ordered()
                    ->with('progress'),
            ]),
        ]);
    }
}
