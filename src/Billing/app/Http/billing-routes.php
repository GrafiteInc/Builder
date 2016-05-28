<?php

/*
|--------------------------------------------------------------------------
| Billing Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => 'auth'], function(){
    Route::group(['prefix' => 'user', 'namespace' => 'User'], function(){

        Route::group(['prefix' => 'billing'], function() {
            Route::get('details', 'BillingController@getSubscribe');
            Route::post('subscribe', 'BillingController@postSubscribe');
            Route::group(['gateway' => 'access-billing'], function() {
                Route::get('change-card', 'BillingController@getChangeCard');
                Route::post('change-card', 'BillingController@postChangeCard');
                Route::get('cancellation', 'BillingController@cancelSubscription');
                Route::get('invoices', 'BillingController@getInvoices');
                Route::get('invoice/{id}', 'BillingController@getInvoiceById');
                Route::get('coupon', 'BillingController@getCoupon');
                Route::post('coupon', 'BillingController@postCoupon');
            });
        });

    });
});

/*
|--------------------------------------------------------------------------
| Failed Payments
|--------------------------------------------------------------------------
*/

Route::post(
    'stripe/webhook',
    '\Laravel\Cashier\Http\Controllers\WebhookController@handleWebhook'
);
