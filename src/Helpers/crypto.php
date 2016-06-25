<?php

if (!function_exists('crypto_encrypt')) {
    function crypto_encrypt($string)
    {
        return app('Crypto')->encrypt($string);
    }
}

if (!function_exists('crypto_decrypt')) {
    function crypto_decrypt($string)
    {
        return app('Crypto')->decrypt($string);
    }
}

if (!function_exists('crypto_uuid')) {
    function crypto_uuid()
    {
        return app('Crypto')->uuid();
    }
}
