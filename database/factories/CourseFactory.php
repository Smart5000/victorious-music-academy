<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Instrument;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CourseFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);

        return [
            'instrument_id' => Instrument::factory(),
            'category_id' => Category::factory(),
            'title' => str($title)->title()->toString(),
            'slug' => Str::slug($title),
            'description' => fake()->paragraph(),
            'thumbnail' => null,
            'order' => fake()->numberBetween(1, 20),
            'is_premium' => false,
        ];
    }
}
