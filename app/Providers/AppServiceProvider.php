<?php

namespace App\Providers;
use Illuminate\Support\Facades\URL;
use Filament\Tables\Table;
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

        Table::configureUsing(function (Table $table): void {
            $table
                ->paginated([10, 25, 50])
                ->defaultPaginationPageOption(10);
        });
    }
}
