<?php

namespace App\Filament\Resources\Thumbnails\Pages;

use App\Filament\Resources\Thumbnails\ThumbnailResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditThumbnail extends EditRecord
{
    protected static string $resource = ThumbnailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
