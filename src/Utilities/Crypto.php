<?php

namespace Yab\Laracogs\Utilities;

use Illuminate\Support\Facades\Config;
use Yab\Laracogs\Encryption\LaracogsEncrypter;
use Yab\Laracogs\Encryption\LaravelCrypto;

class Crypto
{
    /**
     * Make the value shareable.
     *
     * @return LaracogsEncrypter
     */
    public static function shareable()
    {
        $key = Config::get('gondolyn.gondolyn.authKeys')[0] ?: 'someRandomString';

        return new LaracogsEncrypter($key, $key);
    }

    /**
     * Encrypt using the Laravel Crypto.
     *
     * @param string $value
     *
     * @return string
     */
    public static function encrypt($value)
    {
        return (new LaravelCrypto())->encrypt($value);
    }

    /**
     * Decrypt using the Laravel Crypto.
     *
     * @param string $value
     *
     * @return string
     */
    public static function decrypt($value)
    {
        return (new LaravelCrypto())->decrypt($value);
    }

    /**
     * Generate a UUID.
     *
     * @return string
     */
    public static function uuid()
    {
        return (new LaravelCrypto())->uuid();
    }
}
