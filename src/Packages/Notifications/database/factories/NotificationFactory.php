<?php

/*
 * --------------------------------------------------------------------------
 * Notification Factory
 * --------------------------------------------------------------------------
*/

$factory->define({{App\}}Models\Notification::class, function (Faker\Generator $faker) {
    return [
        'id' => 1,
        'user_id' => 1,
        'flag' => 'info',
        'uuid' => 'lksjdflaskhdf',
        'title' => 'Testing',
        'details' => 'Your car has been impounded!',
        'is_read' => 0,
    ];
});
