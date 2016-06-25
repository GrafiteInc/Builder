<?php

/*
|--------------------------------------------------------------------------
| Subscription Config
|--------------------------------------------------------------------------
*/

return [

    'subscription_name' => 'main',

    'plans' => [

        'palantyr_basic' => [
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