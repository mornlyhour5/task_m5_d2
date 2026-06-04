<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ImportDataService;
// use App\Services\ImportDataServiceImpl;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
        ImportDataService::class,
        // ImportDataServiceImpl::class
    );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
