<?php

namespace App\Http\Controllers;

use App\Models\HomepageIntroVideo;
use App\Models\Instrument;
use App\Models\Lesson;
use App\Models\SiteSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $instruments = Instrument::query()->active()->withCount('courses')->orderBy('title')->get();
        $introVideo = HomepageIntroVideo::query()->active()->latest('updated_at')->first();

        if ($introVideo && ! Storage::disk('public')->exists($introVideo->video)) {
            $introVideo = null;
        }

        if ($introVideo?->poster && ! Storage::disk('public')->exists($introVideo->poster)) {
            $introVideo->poster = null;
        }

        return view('home', [
            'introVideo' => $introVideo,
            'instruments' => $instruments,
            'featuredInstruments' => $instruments->where('coming_soon', false)->take(2),
            'comingSoonInstruments' => $instruments->where('coming_soon', true),
            'featuredLessons' => Lesson::query()
                ->whereHas('course.instrument', fn ($query) => $query->active()->where('coming_soon', false))
                ->with('course.instrument')
                ->ordered()
                ->limit(3)
                ->get(),
            'settings' => [
                'name' => SiteSetting::value('site.name', 'Victorious Victory Music Institute'),
                'heroTitle' => SiteSetting::value('home.hero_title', 'Music lessons that help children shine'),
                'heroSubtitle' => SiteSetting::value('home.hero_subtitle', 'Learn music through fun video lessons.'),
                'ctaText' => SiteSetting::value('home.cta_text', 'Start Learning for Free'),
                'about' => SiteSetting::value('home.about_text', 'A friendly online academy for young musicians.'),
            ],
        ]);
    }
}
