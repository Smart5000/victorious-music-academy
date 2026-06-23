<?php

namespace App\Filament\Resources\Lessons\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LessonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('course_id')->relationship('course', 'title')->required(),
                TextInput::make('title')->required()->maxLength(255),
                TextInput::make('slug')->required()->unique(ignoreRecord: true)->maxLength(255),
                TextInput::make('video_url')->url()->maxLength(255),
                TextInput::make('duration')->numeric(),
                Textarea::make('description')->columnSpanFull(),
                TextInput::make('lesson_order')->numeric()->default(0)->required(),
                Toggle::make('is_free_preview')
                    ->label('Free Preview')
                    ->helperText('Free previews remain accessible even when the course is premium.')
                    ->default(false),
                Toggle::make('is_premium')
                    ->label('Premium Lesson')
                    ->helperText('Students need an active subscription unless this is a free preview.')
                    ->default(false),
            ]);
    }
}
