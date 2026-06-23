<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')->required()->unique(ignoreRecord: true)->maxLength(255),
                Textarea::make('value')->columnSpanFull(),
                Select::make('type')->options([
                    'text' => 'Text',
                    'textarea' => 'Textarea',
                    'url' => 'URL',
                    'email' => 'Email',
                ])->required(),
                TextInput::make('group')->required()->maxLength(255),
            ]);
    }
}
