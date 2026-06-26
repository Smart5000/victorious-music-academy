<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Filament\Tables\Table;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

	if (app()->environment('production')) {
    URL::forceScheme('https');
}

        Schema::defaultStringLength(191);

        foreach ([
            storage_path('app'),
            storage_path('app/public'),
            storage_path('app/livewire-tmp'),
            storage_path('app/private/livewire-tmp'),
            storage_path('app/public/livewire-tmp'),
            storage_path('app/public/victorious-music-academy/store/banners'),
            storage_path('app/public/victorious-music-academy/products/thumbnails'),
            storage_path('app/public/victorious-music-academy/products/materials'),
            storage_path('app/public/victorious-music-academy/instruments'),
            storage_path('app/public/victorious-music-academy/courses'),
            storage_path('app/public/victorious-music-academy/homepage/intro-videos'),
            storage_path('app/public/victorious-music-academy/homepage/intro-posters'),
            base_path('bootstrap/cache'),
        ] as $directory) {
            if (! File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true);
            }
        }

        Table::configureUsing(function (Table $table): void {
            $table
                ->paginated([10, 25, 50])
                ->defaultPaginationPageOption(10);
        });
    }
}
