<?php

namespace App\Filament\Resources\Thumbnails\Pages;

use App\Filament\Resources\Thumbnails\ThumbnailResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListThumbnails extends ListRecords
{
    protected static string $resource = ThumbnailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
