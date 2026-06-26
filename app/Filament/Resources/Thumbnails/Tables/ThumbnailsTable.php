<?php

namespace App\Filament\Resources\Thumbnails\Tables;

use App\Support\Media;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ThumbnailsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail_url')
                    ->label('Image')
                    ->getStateUsing(fn ($record): ?string => Media::url($record->thumbnail_url, $record->path)),
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('thumbnailable_type')->label('Type')->formatStateUsing(fn (?string $state): string => class_basename($state ?? '')),
                IconColumn::make('is_primary')->boolean(),
                TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->filters([
                //
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
