<?php

namespace App\Providers;

use App\Repositories\ConfigurationRepositoryInterface;
use App\Repositories\ConfigurationServiceRepository;
use Illuminate\Support\ServiceProvider;

class ConfigurationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            ConfigurationRepositoryInterface::class,
            function () {
                return new ConfigurationServiceRepository();
            }
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
