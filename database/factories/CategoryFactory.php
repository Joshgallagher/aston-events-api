<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Category::class, function (Faker $faker) {
    $categoryName = $faker->unique()->word;

    return [
        'name' => $categoryName,
        'slug' => str_slug($categoryName),
    ];
});
