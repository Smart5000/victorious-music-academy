<?php

namespace App\Filament\Resources\PaymentTransactions\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PaymentTransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->with(['user', 'plan']))
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('reference')->searchable()->copyable(),
                TextColumn::make('user.email')->label('Student')->searchable(),
                TextColumn::make('plan.name')->label('Plan'),
                TextColumn::make('amount')->money('NGN', divideBy: 100)->sortable(),
                TextColumn::make('status')->badge()->formatStateUsing(fn (string $state) => str($state)->headline()),
                TextColumn::make('gateway')->badge(),
                TextColumn::make('paid_at')->dateTime()->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'success' => 'Success',
                    'failed' => 'Failed',
                ]),
                SelectFilter::make('subscription_plan_id')->relationship('plan', 'name')->label('Plan'),
            ]);
    }
}
