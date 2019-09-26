<?php

namespace App\Providers;

use App\Services\ServiceRepository;
use Illuminate\Support\ServiceProvider;
use App\Services\CrawlerServiceRepository;
use App\Repositories\ApplianceEloquentRepository;
use App\Repositories\ApplianceRepositoryContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ApplianceRepositoryContract::class, ApplianceEloquentRepository::class);

        $this->app->bind(ServiceRepository::class, CrawlerServiceRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
