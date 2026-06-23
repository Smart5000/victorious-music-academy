<?php

namespace App\Filament\Widgets;

use App\Models\LessonProgress;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class StudentsCurrentlyLearningTable extends TableWidget
{
    protected static ?string $heading = 'Students Currently Learning';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => LessonProgress::query()
                ->select(['id', 'user_id', 'lesson_id', 'watched_percentage', 'completed', 'updated_at'])
                ->with(['user:id,name', 'lesson:id,title'])
                ->inProgress()
                ->latest('updated_at'))
            ->columns([
                TextColumn::make('user.name')->label('Student')->searchable(),
                TextColumn::make('lesson.title')->label('Lesson')->searchable(),
                TextColumn::make('watched_percentage')->suffix('%')->sortable(),
                IconColumn::make('completed')->boolean(),
                TextColumn::make('updated_at')->label('Last Activity')->since()->sortable(),
            ])
            ->paginated([5, 10])
            ->defaultPaginationPageOption(5);
    }
}
