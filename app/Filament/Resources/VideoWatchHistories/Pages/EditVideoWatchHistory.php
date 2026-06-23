<?php

namespace App\Filament\Resources\VideoWatchHistories\Pages;

use App\Filament\Resources\VideoWatchHistories\VideoWatchHistoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVideoWatchHistory extends EditRecord
{
    protected static string $resource = VideoWatchHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
