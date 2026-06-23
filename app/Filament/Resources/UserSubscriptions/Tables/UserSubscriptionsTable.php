<?php

namespace App\Filament\Resources\UserSubscriptions\Tables;

use App\Models\UserSubscription;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UserSubscriptionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->with(['user', 'plan']))
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('user.name')->label('Student')->searchable()->sortable(),
                TextColumn::make('user.email')->label('Email')->searchable(),
                TextColumn::make('plan.name')->label('Plan')->sortable(),
                TextColumn::make('status')->badge()->formatStateUsing(fn (string $state) => str($state)->headline()),
                TextColumn::make('paystack_customer_code')->label('Customer Code')->copyable()->toggleable(),
                TextColumn::make('paystack_subscription_code')->label('Subscription Code')->copyable()->toggleable(),
                TextColumn::make('starts_at')->dateTime()->sortable(),
                TextColumn::make('ends_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    UserSubscription::STATUS_ACTIVE => 'Active',
                    UserSubscription::STATUS_PENDING => 'Pending',
                    UserSubscription::STATUS_INACTIVE => 'Inactive',
                    UserSubscription::STATUS_CANCELLED => 'Cancelled',
                    UserSubscription::STATUS_EXPIRED => 'Expired',
                    UserSubscription::STATUS_FAILED => 'Failed',
                ]),
                SelectFilter::make('subscription_plan_id')->relationship('plan', 'name')->label('Plan'),
            ]);
    }
}
