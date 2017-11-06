<?php

/*
|--------------------------------------------------------------------------
| Features Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => 'auth'], function () {

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */

    Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function () {
        Route::resource('features', 'Admin\FeatureController', [
            'except' => [
                'show',
            ],
            'as' => 'admin',
        ]);
        Route::post('features/search', 'Admin\FeatureController@search');
    });
});
