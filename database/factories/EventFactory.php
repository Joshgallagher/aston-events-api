<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Event::class, function (Faker $faker) {
    $eventName = $faker->sentence;
    $eventDateTime = $faker->dateTimeBetween('now', '+1 month');
    $eventDate = $eventDateTime->format('Y-m-d');
    $eventTime = $eventDateTime->format('H:i:s');

    return [
        'category_id' => function () {
            return App\Models\Category::all()->random()->id;
        },
        'user_id' => function () {
            return App\Models\User::all()->random()->id;
        },
        'name' => $eventName,
        'slug' => str_slug($eventName),
        'description' => $faker->paragraph,
        'location' => 'Aston Students\' Union',
        'date' => $eventDate,
        'time' => $eventTime,
    ];
});
