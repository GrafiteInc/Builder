<?php

namespace Grafite\Builder;

use Grafite\Builder\Console\Activity;
use Grafite\Builder\Console\Api;
use Grafite\Builder\Console\Billing;
use Grafite\Builder\Console\Bootstrap;
use Grafite\Builder\Console\Features;
use Grafite\Builder\Console\Forge;
use Grafite\Builder\Console\Logs;
use Grafite\Builder\Console\Notifications;
use Grafite\Builder\Console\Queue;
use Grafite\Builder\Console\Socialite;
use Grafite\Builder\Console\Starter;
use Grafite\CrudMaker\CrudMakerProvider;
use Grafite\Crypto\CryptoProvider;
use Grafite\FormMaker\FormMakerProvider;
use Illuminate\Support\ServiceProvider;

class GrafiteBuilderProvider extends ServiceProvider
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
            Activity::class,
            Api::class,
            Billing::class,
            Bootstrap::class,
            Features::class,
            Forge::class,
            Logs::class,
            Queue::class,
            Notifications::class,
            Socialite::class,
            Starter::class,
        ]);
    }
}
