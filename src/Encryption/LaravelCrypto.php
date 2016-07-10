<?php

namespace Yab\Laracogs\Encryption;

use Auth;
use Illuminate\Support\Facades\Config;

class LaravelCrypto extends LaracogsEncrypter
{
    /**
     * Construct the Laravel version of the LaracogsEncrypter
     * using the app key and the session ID.
     */
    public function __construct()
    {
        $app_key = Config::get('app.key') ?: 'unsafe';
        $api_key = Auth::id() ?: 0;

        parent::__construct($app_key, $api_key);
    }
}
