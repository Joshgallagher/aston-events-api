<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Favorite::class, function (Faker $faker) {
    return [
        'user_id' => factory('App\Models\User')->create()->id,
        'favorited_id' => function () {
            return App\Models\Event::all()->random()->id;
        },
        'favorited_type' => App\Models\Event::class,
    ];
});
