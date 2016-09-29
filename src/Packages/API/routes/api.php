<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1', 'namespace' => 'Api'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');

    Route::group(['middleware' => 'jwt.auth'], function () {
        Route::get('refresh', 'AuthController@refresh');
        Route::group(['prefix' => 'user'], function () {
            Route::get('profile', 'UserController@getProfile');
            Route::post('profile', 'UserController@postProfile');
        });
    });
});
