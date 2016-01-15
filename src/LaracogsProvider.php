<?php

namespace Yab\Laracogs;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class LaracogsProvider extends ServiceProvider
{
    /**
     * Boot method
     * @return void
     */
    public function boot()
    {
        // Nothing here
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

        $this->app->register(\Collective\Html\HtmlServiceProvider::class);
        $this->app->register(\AlfredoRamos\ParsedownExtra\ParsedownExtraServiceProvider::class);

        /*
        |--------------------------------------------------------------------------
        | Register the Utilities
        |--------------------------------------------------------------------------
        */

        $this->app->bind('FormMaker', function($app) {
            return new \Yab\Laracogs\Utilities\FormMaker();
        });

        $this->app->bind('InputMaker', function($app) {
            return new \Yab\Laracogs\Utilities\InputMaker();
        });

        $loader = AliasLoader::getInstance();

        $loader->alias('FormMaker', \Yab\Laracogs\Facades\FormMaker::class);
        $loader->alias('InputMaker', \Yab\Laracogs\Facades\InputMaker::class);
        $loader->alias('Crypto', \Yab\Laracogs\Utilities\Crypto::class);

        // Thrid party
        $loader->alias('Form', \Collective\Html\FormFacade::class);
        $loader->alias('HTML', \Collective\Html\HtmlFacade::class);
        $loader->alias('Markdown', \AlfredoRamos\ParsedownExtra\Facades\ParsedownExtra::class);

        /*
        |--------------------------------------------------------------------------
        | Register the Commands
        |--------------------------------------------------------------------------
        */

        $this->commands([
            \Yab\Laracogs\Console\Docs::class,
            \Yab\Laracogs\Console\Crud::class,
            \Yab\Laracogs\Console\Starter::class,
        ]);
    }
}
