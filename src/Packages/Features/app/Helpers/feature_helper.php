<?php

/*
 * --------------------------------------------------------------------------
 * Helpers for Features
 * --------------------------------------------------------------------------
*/

if (!function_exists('feature')) {
    function feature($key)
    {
        return app({{App\}}Services\FeatureService::class)->isActive($key);
    }
}