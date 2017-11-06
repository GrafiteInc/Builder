<?php

/*
 * --------------------------------------------------------------------------
 * Feature Factory
 * --------------------------------------------------------------------------
*/

$factory->define({{App\}}Models\Feature::class, function (Faker\Generator $faker) {
    return [
        'id' => 1,
        'key' => 'user-signup',
        'is_active' => false,
    ];
});
