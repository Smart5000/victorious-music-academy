<?php

namespace App\Filament\Resources\VideoWatchHistories;

use App\Filament\Resources\VideoWatchHistories\Pages\CreateVideoWatchHistory;
use App\Filament\Resources\VideoWatchHistories\Pages\EditVideoWatchHistory;
use App\Filament\Resources\VideoWatchHistories\Pages\ListVideoWatchHistories;
use App\Filament\Resources\VideoWatchHistories\Schemas\VideoWatchHistoryForm;
use App\Filament\Resources\VideoWatchHistories\Tables\VideoWatchHistoriesTable;
use App\Models\VideoWatchHistory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VideoWatchHistoryResource extends Resource
{
    protected static ?string $model = VideoWatchHistory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Analytics';

    public static function form(Schema $schema): Schema
    {
        return VideoWatchHistoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VideoWatchHistoriesTable::configure($table);
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
            'index' => ListVideoWatchHistories::route('/'),
            'create' => CreateVideoWatchHistory::route('/create'),
            'edit' => EditVideoWatchHistory::route('/{record}/edit'),
        ];
    }
}
