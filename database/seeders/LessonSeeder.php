<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        Course::query()->with('instrument')->each(function (Course $course): void {
            collect([
                'Meet Your Instrument',
                'First Notes and Sounds',
                'Simple Practice Song',
            ])->each(function (string $lessonTitle, int $index) use ($course): void {
                $slug = Str::slug("{$course->title} {$lessonTitle}");

                Lesson::query()->updateOrCreate(
                    ['slug' => $slug],
                    [
                        'course_id' => $course->id,
                        'title' => $lessonTitle,
                        'slug' => $slug,
                        'description' => "A short, friendly lesson to help children enjoy {$course->instrument->title}.",
                        'duration' => 300,
                        'lesson_order' => $index + 1,
                        'is_free_preview' => $index === 0,
                    ],
                );
            });
        });
    }
}
