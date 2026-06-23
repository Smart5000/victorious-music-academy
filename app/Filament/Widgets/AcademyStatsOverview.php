<?php

namespace App\Filament\Widgets;

use App\Models\LessonProgress;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class AcademyStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Academy Overview';

    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $stats = Cache::remember('filament.dashboard.academy-stats', now()->addMinutes(5), fn (): array => [
            'students' => User::query()->students()->count(),
            'learning' => LessonProgress::query()->inProgress()->distinct()->count('user_id'),
            'completed' => LessonProgress::query()->completed()->count(),
        ]);

        return [
            Stat::make('Total Students', $stats['students'])
                ->description('Registered learner accounts')
                ->color('primary'),
            Stat::make('Students Currently Learning', $stats['learning'])
                ->description('Students with active lesson progress')
                ->color('info'),
            Stat::make('Completed Lessons', $stats['completed'])
                ->description('Lessons watched to 100%')
                ->color('success'),
        ];
    }
}
