<?php

namespace App\Filament\Resources\Thumbnails;

use App\Filament\Resources\Thumbnails\Pages\CreateThumbnail;
use App\Filament\Resources\Thumbnails\Pages\EditThumbnail;
use App\Filament\Resources\Thumbnails\Pages\ListThumbnails;
use App\Filament\Resources\Thumbnails\Schemas\ThumbnailForm;
use App\Filament\Resources\Thumbnails\Tables\ThumbnailsTable;
use App\Models\Thumbnail;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ThumbnailResource extends Resource
{
    protected static ?string $model = Thumbnail::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Website Content';

    public static function form(Schema $schema): Schema
    {
        return ThumbnailForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ThumbnailsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListThumbnails::route('/'),
            'create' => CreateThumbnail::route('/create'),
            'edit' => EditThumbnail::route('/{record}/edit'),
        ];
    }
}
