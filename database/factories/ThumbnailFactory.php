<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ThumbnailFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->words(3, true),
            'path' => 'thumbnails/'.fake()->uuid().'.jpg',
            'alt_text' => fake()->sentence(),
            'is_primary' => fake()->boolean(80),
        ];
    }
}
