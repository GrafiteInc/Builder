<?php

namespace {{App\}}Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class FeatureServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Blade::directive('feature', function ($expression) {
            return "<?php if (Features::isActive($expression)) : ?>";
        });

        Blade::directive('endfeature', function ($expression) {
            return "<?php endif; ?>";
        });
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();

        $loader->alias('Features', \App\Facades\Features::class);

        $this->app->singleton('FeatureService', function ($app) {
            return app(\App\Services\FeatureService::class);
        });
    }
}
