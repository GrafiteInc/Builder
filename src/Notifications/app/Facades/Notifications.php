<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Notifications extends Facade
{
    /**
     * Create the Facade
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'NotificationService'; }
}