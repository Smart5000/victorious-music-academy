<?php

namespace App\Filament\Resources\Thumbnails\Schemas;

use App\Support\FilamentCloudinaryUpload;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ThumbnailForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('thumbnailable_type')->options([
                    'App\\Models\\Instrument' => 'Instrument',
                    'App\\Models\\Course' => 'Course',
                    'App\\Models\\Lesson' => 'Lesson',
                ]),
                TextInput::make('thumbnailable_id')->label('Related Record UUID')->rule('uuid'),
                TextInput::make('title')->required()->maxLength(255),
                FilamentCloudinaryUpload::image(FileUpload::make('path')
                    ->image()
                    ->disk('public')
                    ->directory('thumbnails')
                    ->visibility('public')
                    ->acceptedFileTypes(['image/jpg', 'image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(5120)
                    ->fetchFileInformation(false)
                    ->deletable()
                    ->openable()
                    ->required(), 'victorious-music-academy/thumbnails'),
                TextInput::make('alt_text')->maxLength(255),
                Toggle::make('is_primary')->default(true),
            ]);
    }
}
