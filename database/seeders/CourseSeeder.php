<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\Instrument;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Guitar', 'Keyboard'] as $instrumentName) {
            $instrument = Instrument::query()->where('title', $instrumentName)->firstOrFail();

            foreach (['Beginner', 'Intermediate', 'Advanced'] as $index => $categoryName) {
                $category = Category::query()->where('name', $categoryName)->firstOrFail();
                $title = "{$categoryName} {$instrumentName}";

                Course::query()->updateOrCreate(
                    ['slug' => Str::slug($title)],
                    [
                        'instrument_id' => $instrument->id,
                        'category_id' => $category->id,
                        'title' => $title,
                        'slug' => Str::slug($title),
                        'description' => "A joyful {$categoryName} course for children learning {$instrumentName}.",
                        'order' => $index + 1,
                    ],
                );
            }
        }
    }
}
