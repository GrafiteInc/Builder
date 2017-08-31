<?php

/*
|--------------------------------------------------------------------------
| Subscription Config
|--------------------------------------------------------------------------
*/

return [

    'subscription' => env('SUBSCRIPTION'),

    'subscription_name' => 'main',

    'plans' => [

        'app_basic' => [
            'access' => [
                'some name'
            ],
            'limits' => [
                'Model\Namespace' => 5,
                'pivot_table' => 1
            ],
            'credits' => [
                'column' => 'credits_spent',
                'limit' => 10
            ],
            'custom' => [
                'anything' => 'anything'
            ],
        ],

    ]
];
