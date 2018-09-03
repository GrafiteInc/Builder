<?php

/*
|--------------------------------------------------------------------------
| Forge Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => 'auth'], function () {

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */

    Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function () {
        Route::get('forge/settings', 'Admin\ForgeController@index');
        Route::get('forge/scheduler', 'Admin\ForgeController@scheduler');
        Route::get('forge/workers', 'Admin\ForgeController@workers');

        Route::post('forge/scheduler', 'Admin\ForgeController@createJob');
        Route::delete('forge/scheduler', 'Admin\ForgeController@deleteJob');

        Route::post('forge/workers', 'Admin\ForgeController@createWorker');
        Route::delete('forge/workers', 'Admin\ForgeController@deleteWorker');
    });
});
