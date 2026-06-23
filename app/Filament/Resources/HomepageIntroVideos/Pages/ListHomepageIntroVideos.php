<?php

namespace App\Filament\Resources\HomepageIntroVideos\Pages;

use App\Filament\Resources\HomepageIntroVideos\HomepageIntroVideoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHomepageIntroVideos extends ListRecords
{
    protected static string $resource = HomepageIntroVideoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
