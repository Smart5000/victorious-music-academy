<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LessonFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->words(4, true);

        return [
            'course_id' => Course::factory(),
            'title' => str($title)->title()->toString(),
            'slug' => Str::slug($title),
            'video_url' => fake()->url(),
            'duration' => fake()->numberBetween(180, 1200),
            'description' => fake()->paragraph(),
            'lesson_order' => fake()->numberBetween(1, 30),
            'is_free_preview' => fake()->boolean(20),
            'is_premium' => false,
        ];
    }
}
