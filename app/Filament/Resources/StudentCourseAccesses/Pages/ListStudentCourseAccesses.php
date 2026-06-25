<?php

namespace App\Filament\Resources\StudentCourseAccesses\Pages;

use App\Filament\Resources\StudentCourseAccesses\StudentCourseAccessResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStudentCourseAccesses extends ListRecords
{
    protected static string $resource = StudentCourseAccessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
