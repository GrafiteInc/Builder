<?php

if (!function_exists('input_maker_create')) {
    function input_maker_create($name, $field, $object = null, $class = 'form-control', $reformatted = false, $populated = true)
    {
        return app('InputMaker')->create($name, $field, $object, $class, $reformatted, $populated);
    }
}

if (!function_exists('input_maker_label')) {
    function input_maker_label($name, $attributesConfig = [])
    {
        return app('InputMaker')->label($name, $attributesConfig);
    }
}
