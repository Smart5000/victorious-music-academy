<?php

namespace App\Filament\Resources\StoreBanners\Schemas;

use App\Support\FilamentCloudinaryUpload;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class StoreBannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FilamentCloudinaryUpload::image(FileUpload::make('image')
                    ->label('Store Banner Image')
                    ->image()
                    ->disk('public')
                    ->directory('store/banners')
                    ->visibility('public')
                    ->acceptedFileTypes(['image/jpg', 'image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(5120)
                    ->fetchFileInformation(false)
                    ->deletable()
                    ->openable()
                    ->required(), 'victorious-music-academy/store/banners'),
                Toggle::make('is_active')
                    ->label('Is Active')
                    ->default(true),
            ]);
    }
}
