<?php

/*
|--------------------------------------------------------------------------
| Api Routes
|--------------------------------------------------------------------------
*/


Route::group(['prefix' => 'api/v1', 'namespace' => 'Api'], function(){
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');

    Route::group(['middleware' => 'jwt.auth'], function(){
        Route::get('refresh', 'AuthController@refresh');
        Route::group(['prefix' => 'user'], function() {
            Route::get('profile', 'UserController@getProfile');
            Route::post('profile', 'UserController@postProfile');
        });
    });
});
