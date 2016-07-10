<?php

/*
|--------------------------------------------------------------------------
| Social Routes
|--------------------------------------------------------------------------
*/

Route::get('auth/{provider}', 'Auth\SocialiteAuthController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\SocialiteAuthController@handleProviderCallback');

