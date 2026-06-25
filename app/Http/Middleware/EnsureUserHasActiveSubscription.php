<?php

namespace App\Http\Middleware;

use App\Models\Course;
use App\Models\Lesson;
use App\Services\StudentCourseAccessManager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasActiveSubscription
{
    public function __construct(private readonly StudentCourseAccessManager $courseAccess)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $lesson = $request->route('lesson');
        $course = $request->route('course');

        $requiresSubscription = match (true) {
            $lesson instanceof Lesson => $lesson->loadMissing('course.instrument')->requiresSubscription(),
            $course instanceof Course => true,
            default => false,
        };

        if (! $requiresSubscription) {
            return $next($request);
        }

        $user = $request->user();

        if ($user?->hasActiveSubscription()) {
            $resolvedCourse = match (true) {
                $lesson instanceof Lesson => $lesson->course,
                $course instanceof Course => $course,
                default => null,
            };

            if ($resolvedCourse && $this->courseAccess->isCourseAccessible($user, $resolvedCourse)) {
                return $next($request);
            }

            $lockedMessage = 'Complete the previous course to unlock this course.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $lockedMessage], 403);
            }

            return to_route('dashboard')->with('status', $lockedMessage);
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
