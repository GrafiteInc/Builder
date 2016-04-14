<?php

/*
|--------------------------------------------------------------------------
| Notification Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => 'web'], function() {
    Route::group(['middleware' => 'auth'], function() {

        /*
        |--------------------------------------------------------------------------
        | User Routes
        |--------------------------------------------------------------------------
        */
        Route::group(['prefix' => 'user', 'namespace' => 'User'], function() {
            Route::group(['prefix' => 'notifications'], function() {
                Route::get('/', 'NotificationController@index');
                Route::get('{uuid}/read', 'NotificationController@read');
                Route::get('{uuid}/delete', 'NotificationController@delete');
                Route::get('search', 'NotificationController@search');
            });
        });

        /*
        |--------------------------------------------------------------------------
        | Admin Routes
        |--------------------------------------------------------------------------
        */
        Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function() {
            Route::resource('notifications', 'Admin\NotificationController');
            Route::post('notifications/search', 'Admin\NotificationController@search');
            Route::get('notifications/{id}/delete', [
                'as' => 'admin.notifications.delete',
                'uses' => 'Admin\NotificationController@destroy',
            ]);
        });

    });
});
