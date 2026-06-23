<?php

namespace App\Filament\Resources\Lessons\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class LessonsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->with('course'))
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('course.title')->sortable(),
                TextColumn::make('lesson_order')->sortable(),
                IconColumn::make('is_free_preview')->boolean(),
                IconColumn::make('is_premium')->label('Premium')->boolean(),
                TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('course')->relationship('course', 'title')->searchable(),
                TernaryFilter::make('is_free_preview')->label('Free Preview'),
                TernaryFilter::make('is_premium')->label('Premium'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
