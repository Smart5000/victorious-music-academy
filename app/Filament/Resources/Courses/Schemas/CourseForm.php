<?php

namespace App\Filament\Resources\Courses\Schemas;

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
                FileUpload::make('thumbnail')->image()->directory('courses'),
                TextInput::make('order')->numeric()->default(0)->required(),
                Toggle::make('is_premium')
                    ->label('Premium Course')
                    ->helperText('Students need an active subscription to open this course.')
                    ->default(false),
            ]);
    }
}
