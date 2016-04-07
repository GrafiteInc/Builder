<?php

if (! function_exists('form_maker_table')) {
    function form_maker_table($table, $columns = null, $class = 'form-control', $view = null, $reformatted = true, $populated = false, $idAndTimestamps = false)
    {
        return app('FormMaker')->fromTable($table, $columns = null, $class = 'form-control', $view = null, $reformatted = true, $populated = false, $idAndTimestamps = false);
    }
}

if (! function_exists('form_maker_object')) {
    function form_maker_object($object, $columns = null, $class = 'form-control', $view = null, $reformatted = true, $populated = false, $idAndTimestamps = false)
    {
        return app('FormMaker')->fromObject($object, $columns = null, $class = 'form-control', $view = null, $reformatted = true, $populated = false, $idAndTimestamps = false);
    }
}

if (! function_exists('form_maker_array')) {
    function form_maker_array($array, $columns = null, $view = null, $class = 'form-control', $populated = true, $reformatted = false, $idAndTimestamps = false)
    {
        return app('FormMaker')->fromArray($array, $columns = null, $view = null, $class = 'form-control', $populated = true, $reformatted = false, $idAndTimestamps = false);
    }
}
