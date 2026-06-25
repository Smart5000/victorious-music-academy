<?php

namespace App\Filament\Resources\StudentCourseAccesses\Pages;

use App\Filament\Resources\StudentCourseAccesses\StudentCourseAccessResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStudentCourseAccess extends EditRecord
{
    protected static string $resource = StudentCourseAccessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
