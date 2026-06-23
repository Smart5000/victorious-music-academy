<?php

namespace App\Filament\Resources\VideoWatchHistories\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VideoWatchHistoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')->relationship('user', 'email')->required(),
                Select::make('lesson_id')->relationship('lesson', 'title')->required(),
                DateTimePicker::make('watched_at')->required(),
                TextInput::make('percentage')->numeric()->minValue(0)->maxValue(100)->required(),
            ]);
    }
}
