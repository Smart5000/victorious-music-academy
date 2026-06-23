<?php

namespace App\Filament\Widgets;

use App\Models\LessonProgress;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class CompletionStatisticsChart extends ChartWidget
{
    protected ?string $heading = 'Completion Statistics';

    protected string $color = 'success';

    protected ?string $pollingInterval = null;

    protected function getData(): array
    {
        $counts = Cache::remember('filament.dashboard.completion-statistics', now()->addMinutes(5), fn () => LessonProgress::query()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN completed = 1 THEN 1 ELSE 0 END) as completed')
            ->selectRaw('SUM(CASE WHEN completed = 0 AND watched_percentage > 0 THEN 1 ELSE 0 END) as in_progress')
            ->first());

        $completed = (int) ($counts?->completed ?? 0);
        $inProgress = (int) ($counts?->in_progress ?? 0);
        $notStarted = max((int) ($counts?->total ?? 0) - $completed - $inProgress, 0);

        return [
            'datasets' => [
                [
                    'label' => 'Lessons',
                    'data' => [$completed, $inProgress, $notStarted],
                    'backgroundColor' => ['#22c55e', '#f59e0b', '#94a3b8'],
                ],
            ],
            'labels' => ['Completed', 'In Progress', 'Not Started'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
