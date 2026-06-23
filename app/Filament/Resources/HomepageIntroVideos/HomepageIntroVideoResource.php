<?php

namespace App\Filament\Resources\HomepageIntroVideos;

use App\Filament\Resources\HomepageIntroVideos\Pages\CreateHomepageIntroVideo;
use App\Filament\Resources\HomepageIntroVideos\Pages\EditHomepageIntroVideo;
use App\Filament\Resources\HomepageIntroVideos\Pages\ListHomepageIntroVideos;
use App\Filament\Resources\HomepageIntroVideos\Schemas\HomepageIntroVideoForm;
use App\Filament\Resources\HomepageIntroVideos\Tables\HomepageIntroVideosTable;
use App\Models\HomepageIntroVideo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class HomepageIntroVideoResource extends Resource
{
    protected static ?string $model = HomepageIntroVideo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedVideoCamera;

    protected static string|\UnitEnum|null $navigationGroup = 'Website Content';

    protected static ?string $navigationLabel = 'Homepage Intro Video';

    public static function form(Schema $schema): Schema
    {
        return HomepageIntroVideoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HomepageIntroVideosTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHomepageIntroVideos::route('/'),
            'create' => CreateHomepageIntroVideo::route('/create'),
            'edit' => EditHomepageIntroVideo::route('/{record}/edit'),
        ];
    }
}
