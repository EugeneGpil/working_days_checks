<?php

namespace App\Providers;

use App\Resources\ProdCalendarResources\ProdCalendarInterface;
use App\Resources\ProdCalendarResources\DataGovRuProdCalendarResource;
use App\Resources\ResourcesCache\ResourcesCache;
use App\Resources\ResourcesCache\ResourcesCacheInterface;
use App\Resources\ResourcesCache\SimpleCache;
use App\Resources\ResourcesCache\SimpleCacheInterface;
use Illuminate\Support\ServiceProvider;

class ResourceServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SimpleCacheInterface::class, function () {
            return new SimpleCache();
        });

        $this->app->singleton(ResourcesCacheInterface::class, function () {
            return new ResourcesCache();
        });

        $this->app->bind(ProdCalendarInterface::class, function() {
            return new DataGovRuProdCalendarResource();
        });
    }
}
