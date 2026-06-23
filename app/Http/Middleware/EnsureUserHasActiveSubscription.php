<?php

namespace App\Http\Middleware;

use App\Models\Course;
use App\Models\Lesson;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasActiveSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $lesson = $request->route('lesson');
        $course = $request->route('course');

        $requiresSubscription = match (true) {
            $lesson instanceof Lesson => $lesson->loadMissing('course.instrument')->requiresSubscription(),
            $course instanceof Course => (bool) $course->is_premium,
            default => false,
        };

        if (! $requiresSubscription || $request->user()?->hasActiveSubscription()) {
            return $next($request);
        }

        $message = 'Subscribe to continue learning this course.';

        if ($request->expectsJson()) {
            return response()->json(['message' => $message], 403);
        }

        $instrument = match (true) {
            $lesson instanceof Lesson => $lesson->course->instrument,
            $course instanceof Course => $course->loadMissing('instrument')->instrument,
            default => null,
        };

        return $instrument
            ? to_route('academy.instrument', $instrument)->with('status', $message)
            : to_route('academy.index')->with('status', $message);
    }
}
