<?php

namespace App\Filament\Resources\VideoWatchHistories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class VideoWatchHistoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->with(['user', 'lesson']))
            ->columns([
                TextColumn::make('user.name')->label('Student')->searchable()->sortable(),
                TextColumn::make('user.email')->label('Email')->searchable(),
                TextColumn::make('lesson.title')->label('Lesson')->searchable()->sortable(),
                TextColumn::make('percentage')->suffix('%')->sortable(),
                TextColumn::make('watched_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('lesson')->relationship('lesson', 'title')->searchable(),
                SelectFilter::make('user')->relationship('user', 'email')->searchable(),
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
