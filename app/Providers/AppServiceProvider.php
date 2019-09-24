<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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
