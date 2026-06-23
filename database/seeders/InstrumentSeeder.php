<?php

namespace Database\Seeders;

use App\Models\Instrument;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InstrumentSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            ['title' => 'Guitar', 'coming_soon' => false],
            ['title' => 'Keyboard', 'coming_soon' => false],
            ['title' => 'Violin', 'coming_soon' => true],
            ['title' => 'Drums', 'coming_soon' => true],
        ])->each(fn (array $instrument) => Instrument::query()->updateOrCreate(
            ['slug' => Str::slug($instrument['title'])],
            [
                ...$instrument,
                'slug' => Str::slug($instrument['title']),
                'is_active' => true,
                'description' => $instrument['coming_soon']
                    ? "{$instrument['title']} lessons are coming soon."
                    : "Start your {$instrument['title']} journey with fun, child-friendly lessons.",
            ],
        ));
    }
}
