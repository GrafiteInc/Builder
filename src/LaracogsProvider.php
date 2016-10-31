<?php

namespace Yab\Laracogs;

use Illuminate\Support\ServiceProvider;

class LaracogsProvider extends ServiceProvider
{
    /**
     * Boot method.
     *
     * @return void
     */
    public function boot()
    {
        // do nothing
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        /*
        |--------------------------------------------------------------------------
        | Providers
        |--------------------------------------------------------------------------
        */

        $this->app->register(\Yab\FormMaker\FormMakerProvider::class);
        $this->app->register(\Yab\Crypto\CryptoProvider::class);
        $this->app->register(\Yab\CrudMaker\CrudMakerProvider::class);
        $this->app->register(\Yab\Cerebrum\CerebrumProvider::class);
        $this->app->register(\Yab\LaraTest\LaraTestProvider::class);

        /*
        |--------------------------------------------------------------------------
        | Register the Commands
        |--------------------------------------------------------------------------
        */

        $this->commands([
            \Yab\Laracogs\Console\Api::class,
            \Yab\Laracogs\Console\Billing::class,
            \Yab\Laracogs\Console\Notifications::class,
            \Yab\Laracogs\Console\Socialite::class,
            \Yab\Laracogs\Console\Bootstrap::class,
            \Yab\Laracogs\Console\Semantic::class,
            \Yab\Laracogs\Console\Docs::class,
            \Yab\Laracogs\Console\Starter::class,
        ]);
    }
}
