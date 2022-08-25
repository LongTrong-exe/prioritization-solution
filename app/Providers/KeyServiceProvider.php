<?php

namespace App\Providers;

use App\Repositories\KeyRepositoryInterface;
use App\Repositories\KeyServiceRepository;
use Illuminate\Support\ServiceProvider;

class KeyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            KeyRepositoryInterface::class,
            function () {
                return new KeyServiceRepository();
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
