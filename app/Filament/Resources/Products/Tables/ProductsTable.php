<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Product;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('product_type')
                    ->label('Product Type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => str($state)->headline()->toString()),
                TextColumn::make('price_type')->badge()->formatStateUsing(fn (string $state): string => str($state)->title()->toString()),
                TextColumn::make('price')->label('Price')->formatStateUsing(fn ($state, Product $record): string => $record->price_label)->sortable(),
                IconColumn::make('is_new_release')->label('New')->boolean(),
                IconColumn::make('is_active')->label('Active')->boolean(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('product_type')
                    ->label('Product Type')
                    ->options([
                        Product::PRODUCT_TYPE_INSTRUMENT => 'Instrument',
                        Product::PRODUCT_TYPE_MATERIALS => 'Materials',
                    ]),
                SelectFilter::make('price_type')
                    ->label('Free/Paid')
                    ->options([
                        Product::PRICE_TYPE_FREE => 'Free',
                        Product::PRICE_TYPE_PAID => 'Paid',
                    ]),
                TernaryFilter::make('is_new_release')->label('New Release'),
                TernaryFilter::make('is_active')->label('Active'),
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
