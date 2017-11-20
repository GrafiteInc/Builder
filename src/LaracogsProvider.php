<?php

namespace Yab\Laracogs;

use Illuminate\Support\ServiceProvider;
use Yab\Cerebrum\CerebrumProvider;
use Yab\CrudMaker\CrudMakerProvider;
use Yab\Crypto\CryptoProvider;
use Yab\FormMaker\FormMakerProvider;
use Yab\LaraTest\LaraTestProvider;
use Yab\Laracogs\Console\Activity;
use Yab\Laracogs\Console\Api;
use Yab\Laracogs\Console\Billing;
use Yab\Laracogs\Console\Bootstrap;
use Yab\Laracogs\Console\Docs;
use Yab\Laracogs\Console\Features;
use Yab\Laracogs\Console\Logs;
use Yab\Laracogs\Console\Notifications;
use Yab\Laracogs\Console\Semantic;
use Yab\Laracogs\Console\Socialite;
use Yab\Laracogs\Console\Starter;

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
        $this->app->register(CerebrumProvider::class);
        $this->app->register(LaraTestProvider::class);

        /*
        |--------------------------------------------------------------------------
        | Register the Commands
        |--------------------------------------------------------------------------
        */

        $this->commands([
            Activity::class,
            Api::class,
            Billing::class,
            Notifications::class,
            Features::class,
            Socialite::class,
            Bootstrap::class,
            Semantic::class,
            Docs::class,
            Logs::class,
            Starter::class,
        ]);
    }
}
