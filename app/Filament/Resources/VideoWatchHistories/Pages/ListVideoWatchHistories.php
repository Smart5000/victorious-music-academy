<?php

namespace App\Filament\Resources\VideoWatchHistories\Pages;

use App\Filament\Resources\VideoWatchHistories\VideoWatchHistoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVideoWatchHistories extends ListRecords
{
    protected static string $resource = VideoWatchHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
