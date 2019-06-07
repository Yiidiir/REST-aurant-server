<?php

use Faker\Generator as Faker;

$factory->define(App\Restaurant::class, function (Faker $faker) {
    return [
        'id' => $faker->unique()->randomNumber,
        'address' => $faker->address,
        'class' => $faker->numberBetween(1, 5),
        'created_at' => $faker->dateTimeThisYear($max = 'now', $timezone = null),
        'updated_at' => $faker->dateTimeThisYear($max = 'now', $timezone = null)
    ];
});
