<?php

namespace Database\Factories;

use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LessonProgressFactory extends Factory
{
    public function definition(): array
    {
        $percentage = fake()->numberBetween(0, 100);

        return [
            'user_id' => User::factory(),
            'lesson_id' => Lesson::factory(),
            'watched_percentage' => $percentage,
            'last_watched_second' => fake()->numberBetween(0, 900),
            'completed' => $percentage >= 100,
        ];
    }
}
