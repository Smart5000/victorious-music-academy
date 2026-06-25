<?php

namespace App\Filament\Resources\HomepageIntroVideos\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class HomepageIntroVideoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('video')
                    ->label('Homepage Intro Video')
                    ->disk('public')
                    ->directory('homepage/intro-videos')
                    ->visibility('public')
                    ->acceptedFileTypes(['video/mp4', 'video/webm'])
                    ->helperText('MP4 is recommended. Maximum file size: 100 MB.')
                    ->maxSize(102400)
                    ->fetchFileInformation(false)
                    ->deletable()
                    ->openable()
                    ->required(),
                FileUpload::make('poster')
                    ->label('Video Poster Image')
                    ->image()
                    ->disk('public')
                    ->directory('homepage/intro-posters')
                    ->visibility('public')
                    ->acceptedFileTypes(['image/jpg', 'image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(5120)
                    ->fetchFileInformation(false)
                    ->deletable()
                    ->openable(),
                Toggle::make('is_active')
                    ->label('Is Active')
                    ->default(true),
            ]);
    }
}
