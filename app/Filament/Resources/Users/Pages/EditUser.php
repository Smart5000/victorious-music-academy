<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\Instrument;
use App\Services\StudentCourseAccessManager;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        if (! $this->record->selected_instrument_id) {
            return;
        }

        $instrument = Instrument::query()->find($this->record->selected_instrument_id);

        if ($instrument) {
            app(StudentCourseAccessManager::class)->initializeForInstrument($this->record, $instrument);
        }
    }
}
