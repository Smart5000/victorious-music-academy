<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Product;
use App\Support\FilamentCloudinaryUpload;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')->required()->maxLength(255),
                Select::make('product_type')
                    ->label('Product Type')
                    ->options([
                        Product::PRODUCT_TYPE_INSTRUMENT => 'Instrument',
                        Product::PRODUCT_TYPE_MATERIALS => 'Materials',
                    ])
                    ->default(Product::PRODUCT_TYPE_MATERIALS)
                    ->live()
                    ->required(),
                Textarea::make('description')->required()->columnSpanFull(),
                FilamentCloudinaryUpload::image(FileUpload::make('thumbnail')
                    ->image()
                    ->disk('public')
                    ->directory('products/thumbnails')
                    ->visibility('public')
                    ->acceptedFileTypes(['image/jpg', 'image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(2048)
                    ->fetchFileInformation(false)
                    ->deletable()
                    ->openable(), 'victorious-music-academy/products/thumbnails'),
                Select::make('price_type')
                    ->options([
                        Product::PRICE_TYPE_FREE => 'Free',
                        Product::PRICE_TYPE_PAID => 'Paid',
                    ])
                    ->default(Product::PRICE_TYPE_FREE)
                    ->live()
                    ->required(),
                TextInput::make('price')
                    ->numeric()
                    ->prefix('₦')
                    ->default(0)
                    ->visible(fn (Get $get): bool => $get('price_type') === Product::PRICE_TYPE_PAID)
                    ->required(),
                FilamentCloudinaryUpload::file(FileUpload::make('material_file')
                    ->label('Material File')
                    ->disk('public')
                    ->directory('products/materials')
                    ->visibility('public')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/zip',
                        'application/x-zip-compressed',
                    ])
                    ->maxSize(20480)
                    ->visible(fn (Get $get): bool => $get('product_type') === Product::PRODUCT_TYPE_MATERIALS), 'victorious-music-academy/products/materials'),
                Toggle::make('is_new_release')->label('New Release')->default(false),
                Toggle::make('is_active')->label('Published')->default(true),
            ]);
    }
}
