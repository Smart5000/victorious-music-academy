<?php

namespace App\Filament\Resources\Instruments\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class InstrumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')->required()->maxLength(255),
                TextInput::make('slug')->required()->unique(ignoreRecord: true)->maxLength(255),
                Textarea::make('description')->columnSpanFull(),
                FileUpload::make('thumbnail')
                    ->image()
                    ->disk('public')
                    ->directory('instruments')
                    ->visibility('public')
                    ->acceptedFileTypes(['image/jpg', 'image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(5120)
                    ->fetchFileInformation(false)
                    ->deletable()
                    ->openable(),
                Toggle::make('is_active')
                    ->label('Show on student website')
                    ->helperText('Turn this off to hide the instrument from students without deleting it.')
                    ->default(true),
                Toggle::make('coming_soon')->label('Coming Soon')->default(false),
            ]);
    }
}
