<?php

namespace Yab\Laracogs\Facades;

use Illuminate\Support\Facades\Facade;

class Account extends Facade
{
    /**
     * Create the Facade
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'Account'; }
}