<?php

namespace App\Filament\Resources\StudentCourseAccesses;

use App\Filament\Resources\StudentCourseAccesses\Pages\CreateStudentCourseAccess;
use App\Filament\Resources\StudentCourseAccesses\Pages\EditStudentCourseAccess;
use App\Filament\Resources\StudentCourseAccesses\Pages\ListStudentCourseAccesses;
use App\Models\StudentCourseAccess;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StudentCourseAccessResource extends Resource
{
    protected static ?string $model = StudentCourseAccess::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLockClosed;

    protected static ?string $navigationLabel = 'Student Course Access';

    protected static ?string $modelLabel = 'Student Course Access';

    protected static string|\UnitEnum|null $navigationGroup = 'Academy Management';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Student')
                    ->relationship('user', 'name', fn (Builder $query) => $query->students())
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('course_id')
                    ->label('Course')
                    ->relationship('course', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('status')
                    ->options([
                        StudentCourseAccess::STATUS_LOCKED => 'Locked',
                        StudentCourseAccess::STATUS_UNLOCKED => 'Unlocked',
                        StudentCourseAccess::STATUS_COMPLETED => 'Completed',
                    ])
                    ->required(),
                Select::make('unlocked_by')
                    ->options([
                        StudentCourseAccess::UNLOCKED_BY_SYSTEM => 'System',
                        StudentCourseAccess::UNLOCKED_BY_ADMIN => 'Admin',
                    ])
                    ->default(StudentCourseAccess::UNLOCKED_BY_ADMIN),
                DateTimePicker::make('unlocked_at'),
                DateTimePicker::make('completed_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['user:id,name,email', 'course:id,title,instrument_id', 'course.instrument:id,title']))
            ->columns([
                TextColumn::make('user.name')->label('Student')->searchable()->sortable(),
                TextColumn::make('course.instrument.title')->label('Instrument')->sortable(),
                TextColumn::make('course.title')->label('Course')->searchable()->sortable(),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('unlocked_by')->badge()->sortable(),
                TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    StudentCourseAccess::STATUS_LOCKED => 'Locked',
                    StudentCourseAccess::STATUS_UNLOCKED => 'Unlocked',
                    StudentCourseAccess::STATUS_COMPLETED => 'Completed',
                ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStudentCourseAccesses::route('/'),
            'create' => CreateStudentCourseAccess::route('/create'),
            'edit' => EditStudentCourseAccess::route('/{record}/edit'),
        ];
    }
}
