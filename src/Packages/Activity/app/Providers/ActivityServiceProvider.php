<?php

namespace {{App\}}Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ActivityServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        if (function_exists('activity')) {
            function activity($description)
            {
                return Activity::log($description);
            }
        }
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();

        $loader->alias('Activity', \App\Facades\Activity::class);

        $this->app->singleton('ActivityService', function ($app) {
            return app(\App\Services\ActivityService::class);
        });
    }
}
