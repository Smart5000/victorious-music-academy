<?php

namespace App\Filament\Resources\Instruments\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class InstrumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Active')
                    ->sortable(),
                TextColumn::make('coming_soon')->badge()->formatStateUsing(fn (bool $state): string => $state ? 'Coming Soon' : 'Available')->sortable(),
                TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('Active'),
                TernaryFilter::make('coming_soon')->label('Coming Soon'),
            ])
            ->recordActions([
                Action::make('enable')
                    ->label('Enable')
                    ->icon('heroicon-m-eye')
                    ->color('success')
                    ->visible(fn ($record): bool => ! $record->is_active)
                    ->action(fn ($record) => $record->update(['is_active' => true])),
                Action::make('disable')
                    ->label('Disable')
                    ->icon('heroicon-m-eye-slash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Disable this instrument?')
                    ->modalDescription('Disabled instruments will not show on the student website, but their courses and lessons will remain saved.')
                    ->visible(fn ($record): bool => $record->is_active)
                    ->action(fn ($record) => $record->update(['is_active' => false])),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
