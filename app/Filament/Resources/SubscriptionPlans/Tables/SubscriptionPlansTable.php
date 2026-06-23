<?php

namespace App\Filament\Resources\SubscriptionPlans\Tables;

use App\Models\SubscriptionPlan;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SubscriptionPlansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('display_order')
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('price')->formatStateUsing(fn ($state, SubscriptionPlan $record) => $record->price_label)->sortable(),
                TextColumn::make('billing_interval')->label('Interval')->badge()->formatStateUsing(fn (string $state) => str($state)->headline()),
                TextColumn::make('paystack_plan_code')->label('Paystack Plan')->searchable()->copyable(),
                TextColumn::make('subscriptions_count')->counts('subscriptions')->label('Subscribers'),
                IconColumn::make('is_active')->label('Active')->boolean(),
            ])
            ->filters([
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
