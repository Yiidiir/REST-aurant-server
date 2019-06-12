<?php

use App\User;
use Faker\Generator as Faker;

$factory->define(App\Restaurant::class, function (Faker $faker) {
    $owners = User::where('role', 2)->pluck('id');

    return [
        'name' => $faker->company,
        'address' => $faker->address,
        'class' => $faker->numberBetween(1, 5),
        'owner_id' => $faker->randomElement($owners),
        'created_at' => $faker->dateTimeThisYear($max = 'now', $timezone = null),
        'updated_at' => $faker->dateTimeThisYear($max = 'now', $timezone = null)
    ];
});
