<?php

/*
|--------------------------------------------------------------------------
| Social Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => 'web'], function() {
    Route::get('auth/{provider}', 'Auth\SocialiteAuthController@redirectToProvider');
    Route::get('auth/{provider}/callback', 'Auth\SocialiteAuthController@handleProviderCallback');
});
