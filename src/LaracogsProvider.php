<?php

namespace Yab\Laracogs;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Yab\Laracogs\Utilities\Crypto;
use Yab\Laracogs\Utilities\FormMaker;
use Yab\Laracogs\Utilities\InputMaker;

class LaracogsProvider extends ServiceProvider
{
    /**
     * Boot method.
     *
     * @return void
     */
    public function boot()
    {
        @mkdir(base_path('resources/laracogs/crud'));
        $this->publishes([
            __DIR__.'/Templates'                   => base_path('resources/laracogs/crud'),
            __DIR__.'/Starter/config/laracogs.php' => base_path('config/laracogs.php'),
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

        $this->app->register(\Collective\Html\HtmlServiceProvider::class);
        $this->app->register(\AlfredoRamos\ParsedownExtra\ParsedownExtraServiceProvider::class);

        /*
        |--------------------------------------------------------------------------
        | Register the Utilities
        |--------------------------------------------------------------------------
        */

        $this->app->singleton('FormMaker', function ($app) {
            return new FormMaker($app);
        });

        $this->app->singleton('InputMaker', function ($app) {
            return new InputMaker($app);
        });

        $this->app->singleton('Crypto', function ($app) {
            return new Crypto($app);
        });

        $loader = AliasLoader::getInstance();

        $loader->alias('FormMaker', \Yab\Laracogs\Facades\FormMaker::class);
        $loader->alias('InputMaker', \Yab\Laracogs\Facades\InputMaker::class);
        $loader->alias('Crypto', \Yab\Laracogs\Utilities\Crypto::class);
        $loader->alias('Markdown', \AlfredoRamos\ParsedownExtra\Facades\ParsedownExtra::class);

        // Thrid party
        $loader->alias('Form', \Collective\Html\FormFacade::class);
        $loader->alias('HTML', \Collective\Html\HtmlFacade::class);

        /*
        |--------------------------------------------------------------------------
        | Blade Directives
        |--------------------------------------------------------------------------
        */

        // Form Maker
        Blade::directive('form_maker_table', function ($expression) {
            return "<?php echo FormMaker::fromTable$expression; ?>";
        });

        Blade::directive('form_maker_array', function ($expression) {
            return "<?php echo FormMaker::fromArray$expression; ?>";
        });

        Blade::directive('form_maker_object', function ($expression) {
            return "<?php echo FormMaker::fromObject$expression; ?>";
        });

        Blade::directive('form_maker_columns', function ($expression) {
            return "<?php echo FormMaker::getTableColumns$expression; ?>";
        });

        // Label Maker
        Blade::directive('input_maker_label', function ($expression) {
            return "<?php echo InputMaker::label$expression; ?>";
        });

        Blade::directive('input_maker_create', function ($expression) {
            return "<?php echo InputMaker::create$expression; ?>";
        });

        // Crypto
        Blade::directive('crypto_encrypt', function ($expression) {
            return "<?php echo Crypto::encrypt$expression; ?>";
        });

        Blade::directive('crypto_decrypt', function ($expression) {
            return "<?php echo Crypto::encrypt$expression; ?>";
        });

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
            \Yab\Laracogs\Console\Crud::class,
            \Yab\Laracogs\Console\TableCrud::class,
            \Yab\Laracogs\Console\Starter::class,
        ]);
    }
}
