<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('email')->email()->required()->unique(ignoreRecord: true)->maxLength(255),
                Select::make('role')->options([
                    'admin' => 'Admin',
                    'student' => 'Student',
                ])->required(),
                Select::make('selected_instrument_id')
                    ->label('Selected Instrument')
                    ->relationship('selectedInstrument', 'title')
                    ->searchable()
                    ->preload()
                    ->helperText('Admins can change this if a student selected the wrong instrument. Students cannot change it themselves.'),
            ]);
    }
}
