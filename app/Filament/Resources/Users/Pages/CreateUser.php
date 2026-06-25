<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\Instrument;
use App\Services\StudentCourseAccessManager;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
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
