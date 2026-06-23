<?php

namespace App\Filament\Resources\LessonProgress\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LessonProgressForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user.name')->disabled(),
                TextInput::make('lesson.title')->disabled(),
                TextInput::make('watched_percentage')->disabled(),
                TextInput::make('last_watched_second')->disabled(),
                TextInput::make('completed')->disabled(),
            ]);
    }
}
