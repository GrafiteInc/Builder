<?php

/*
|--------------------------------------------------------------------------
| UserMeta Factory
|--------------------------------------------------------------------------
*/

$factory->define({{App\}}Models\UserMeta::class, function (Faker\Generator $faker) {
    return [
        'user_id' => 1,
        'phone' => $faker->phoneNumber,
        'marketing' => 1,
        'terms_and_cond' => 1,
    ];
});
