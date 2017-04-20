<?php

/*
 * --------------------------------------------------------------------------
 * Helpers for Activities
 * --------------------------------------------------------------------------
*/

if (!function_exists('activity')) {
    function activity($description)
    {
        return app({{App\}}Services\ActivityService::class)->log($description);
    }
}
