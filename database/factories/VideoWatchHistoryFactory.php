<?php

namespace Database\Factories;

use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class VideoWatchHistoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'lesson_id' => Lesson::factory(),
            'watched_at' => fake()->dateTimeBetween('-30 days'),
            'event_type' => fake()->randomElement(['play', 'pause', 'progress', 'completed']),
            'percentage' => fake()->numberBetween(1, 100),
            'watched_second' => fake()->numberBetween(1, 900),
        ];
    }
}
