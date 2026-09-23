<?php

namespace App\Providers;

use App\Repositories\Contracts\PropertyRepositoryInterface;
use App\Repositories\PropertyRepository;
use App\Services\Contracts\PropertyServiceInterface;
use App\Services\PropertyService;
use Illuminate\Support\ServiceProvider;
use \App\Repositories\Contracts\CityRepositoryInterface;
use \App\Repositories\CityRepository;
use \App\Services\Contracts\CityServiceInterface;
use \App\Services\CityService;
use \App\Repositories\Contracts\LocalityRepositoryInterface;
use \App\Repositories\LocalityRepository;
use \App\Services\Contracts\LocalityServiceInterface;
use \App\Services\LocalityService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PropertyRepositoryInterface::class, PropertyRepository::class);
        $this->app->bind(PropertyServiceInterface::class, PropertyService::class);
        $this->app->bind(CityRepositoryInterface::class, CityRepository::class);
        $this->app->bind(CityServiceInterface::class, CityService::class);
        $this->app->bind(LocalityRepositoryInterface::class, LocalityRepository::class);
        $this->app->bind(LocalityServiceInterface::class, LocalityService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
