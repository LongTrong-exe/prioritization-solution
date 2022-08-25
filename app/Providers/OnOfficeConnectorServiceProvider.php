<?php

namespace App\Providers;

use App\Services\OnOfficeConnector;
use App\Services\OnOfficeConnectorInterface;
use Illuminate\Support\ServiceProvider;
use onOffice\SDK\onOfficeSDK;

class OnOfficeConnectorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            OnOfficeConnectorInterface::class,
            function () {
                return new OnOfficeConnector(new onOfficeSDK());
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
