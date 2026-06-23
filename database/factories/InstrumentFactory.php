<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InstrumentFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->randomElement(['Guitar', 'Keyboard', 'Violin', 'Drums']).' '.fake()->unique()->word();

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => fake()->sentence(),
            'thumbnail' => null,
            'coming_soon' => false,
            'is_active' => true,
        ];
    }
}
