<?php

namespace App\Filament\Widgets;

use App\Models\Lesson;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class MostWatchedLessonsTable extends TableWidget
{
    protected static ?string $heading = 'Most Watched Lessons';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Lesson::query()
                ->select(['id', 'course_id', 'title'])
                ->with('course:id,title')
                ->withCount('watchHistory')
                ->orderByDesc('watch_history_count'))
            ->columns([
                TextColumn::make('title')->searchable(),
                TextColumn::make('course.title')->label('Course'),
                TextColumn::make('watch_history_count')->label('Views')->sortable(),
            ])
            ->paginated([5, 10])
            ->defaultPaginationPageOption(5);
    }
}
