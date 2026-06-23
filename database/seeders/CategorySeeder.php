<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        collect([
            ['name' => 'Beginner'],
            ['name' => 'Intermediate'],
            ['name' => 'Advanced'],
        ])->each(fn (array $category) => Category::query()->updateOrCreate(
            ['slug' => Str::slug($category['name'])],
            [
                ...$category,
                'slug' => Str::slug($category['name']),
                'description' => "A {$category['name']} learning path for young musicians.",
            ],
        ));
    }
}
