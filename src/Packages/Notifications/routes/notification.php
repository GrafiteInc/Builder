<?php

/*
|--------------------------------------------------------------------------
| Notification Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => 'auth'], function () {

    /*
    |--------------------------------------------------------------------------
    | User Routes
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'user', 'namespace' => 'User'], function () {
        Route::group(['prefix' => 'notifications'], function () {
            Route::get('/', 'NotificationController@index');
            Route::get('{uuid}/read', 'NotificationController@read');
            Route::delete('{uuid}/delete', 'NotificationController@delete');
            Route::get('search', 'NotificationController@search');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function () {
        Route::resource('notifications', 'Admin\NotificationController', ['except' => ['show'], 'as' => 'admin']);
        Route::post('notifications/search', 'Admin\NotificationController@search');
    });
});
