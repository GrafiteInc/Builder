<?php

namespace Yab\Laracogs;

use Illuminate\Foundation\AliasLoader;
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
        $this->publishes([
            __DIR__.'/Packages/Starter/config/permissions.php' => base_path('config/permissions.php'),
        ]);
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

        /*
        |--------------------------------------------------------------------------
        | Register the Services
        |--------------------------------------------------------------------------
        */

        $loader = AliasLoader::getInstance();

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
