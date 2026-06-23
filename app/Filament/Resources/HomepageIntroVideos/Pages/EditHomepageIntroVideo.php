<?php

namespace App\Filament\Resources\HomepageIntroVideos\Pages;

use App\Filament\Resources\HomepageIntroVideos\HomepageIntroVideoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHomepageIntroVideo extends EditRecord
{
    protected static string $resource = HomepageIntroVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
