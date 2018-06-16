<?php

/*
|--------------------------------------------------------------------------
| Logs Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => 'auth'], function () {

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */

    Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function () {
        Route::get('queue', 'Admin\QueueController@index');
        Route::get('queue/upcoming', 'Admin\QueueController@upcoming');
        Route::get('queue/failed', 'Admin\QueueController@failed');
        Route::get('queue/jobs/{job}', 'Admin\QueueController@show');
        Route::get('queue/jobs/{job}/retry', 'Admin\QueueController@retry');
        Route::get('queue/jobs/{job}/cancel/{table}', 'Admin\QueueController@cancel');
    });
});
