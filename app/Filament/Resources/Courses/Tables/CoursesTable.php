<?php

namespace App\Filament\Resources\Courses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->with(['instrument', 'category']))
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('instrument.title')->sortable(),
                TextColumn::make('category.name')->sortable(),
                TextColumn::make('order')->sortable(),
                IconColumn::make('is_premium')->label('Premium')->boolean(),
            ])
            ->filters([
                SelectFilter::make('instrument')->relationship('instrument', 'title')->searchable()->preload(),
                SelectFilter::make('category')->relationship('category', 'name')->searchable()->preload(),
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
