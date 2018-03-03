<?php

namespace Yab\Laracogs;

use Illuminate\Support\ServiceProvider;
use Yab\CrudMaker\CrudMakerProvider;
use Yab\Crypto\CryptoProvider;
use Yab\FormMaker\FormMakerProvider;

class LaracogsProvider extends ServiceProvider
{
    /**
     * Boot method.
     */
    public function boot()
    {
        // do nothing
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        /*
        |--------------------------------------------------------------------------
        | Providers
        |--------------------------------------------------------------------------
        */

        $this->app->register(FormMakerProvider::class);
        $this->app->register(CryptoProvider::class);
        $this->app->register(CrudMakerProvider::class);

        /*
        |--------------------------------------------------------------------------
        | Register the Commands
        |--------------------------------------------------------------------------
        */

        $this->commands([
            \Yab\Laracogs\Console\Activity::class,
            \Yab\Laracogs\Console\Api::class,
            \Yab\Laracogs\Console\Billing::class,
            \Yab\Laracogs\Console\Notifications::class,
            \Yab\Laracogs\Console\Features::class,
            \Yab\Laracogs\Console\Socialite::class,
            \Yab\Laracogs\Console\Bootstrap::class,
            \Yab\Laracogs\Console\Logs::class,
            \Yab\Laracogs\Console\Starter::class,
        ]);
    }
}
