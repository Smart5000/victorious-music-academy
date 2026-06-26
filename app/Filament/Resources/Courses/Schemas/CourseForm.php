<?php

namespace App\Filament\Resources\Courses\Schemas;

use App\Support\FilamentCloudinaryUpload;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('instrument_id')->relationship('instrument', 'title')->required(),
                Select::make('category_id')->relationship('category', 'name')->required(),
                TextInput::make('title')->required()->maxLength(255),
                TextInput::make('slug')->required()->unique(ignoreRecord: true)->maxLength(255),
                Textarea::make('description')->columnSpanFull(),
                FilamentCloudinaryUpload::image(FileUpload::make('thumbnail')
                    ->image()
                    ->disk('public')
                    ->directory('courses')
                    ->visibility('public')
                    ->acceptedFileTypes(['image/jpg', 'image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(5120)
                    ->fetchFileInformation(false)
                    ->deletable()
                    ->openable(), 'victorious-music-academy/courses'),
                TextInput::make('order')->numeric()->default(0)->required(),
                Toggle::make('is_premium')
                    ->label('Premium Course')
                    ->helperText('Students need an active subscription to open this course.')
                    ->default(false),
            ]);
    }
}
