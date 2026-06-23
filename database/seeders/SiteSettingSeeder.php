<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            'site.name' => 'Victorious Victory Music Institute',
            'home.hero_title' => 'Music lessons that help children shine',
            'home.hero_subtitle' => 'Learn Guitar and Keyboard through fun, structured video lessons for ages 4–17.',
            'home.cta_text' => 'Start Learning for Free',
            'home.about_text' => 'Our academy gives young learners a friendly path into music with clear lessons, progress tracking, and a joyful learning experience.',
            'footer.text' => 'Helping young musicians grow with confidence.',
            'contact.email' => 'hello@victoriousvictorymusic.test',
        ])->each(fn (string $value, string $key) => SiteSetting::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => 'text', 'group' => str($key)->before('.')->toString()],
        ));
    }
}
