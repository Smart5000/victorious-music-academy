<?php

namespace App\Filament\Resources\SubscriptionPlans\Schemas;

use App\Models\SubscriptionPlan;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SubscriptionPlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('slug')->required()->unique(ignoreRecord: true)->maxLength(255),
            Textarea::make('description')->columnSpanFull(),
            TextInput::make('price')->label('Price (Naira)')->prefix('₦')->numeric()->minValue(1)->required(),
            Select::make('billing_interval')
                ->options([
                    SubscriptionPlan::INTERVAL_MONTHLY => 'Monthly',
                    SubscriptionPlan::INTERVAL_QUARTERLY => 'Quarterly',
                    SubscriptionPlan::INTERVAL_ANNUALLY => 'Yearly',
                ])
                ->required(),
            TextInput::make('paystack_plan_code')
                ->label('Paystack Plan Code')
                ->helperText('Create the recurring plan in Paystack, then paste its plan code here.')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),
            TextInput::make('display_order')->numeric()->minValue(0)->default(0)->required(),
            Toggle::make('is_active')->label('Active')->default(true),
        ]);
    }
}
