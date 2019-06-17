<?php

use App\Restaurant;
use App\Table;
use Faker\Generator as Faker;

$factory->define(Table::class, function (Faker $faker) {
    $restaurants = Restaurant::all()->pluck('id');
    return [
        'capacity_min' => $faker->numberBetween(1,3),
        'capacity_max' => $faker->numberBetween(3,6),
        'class' => $faker->numberBetween(1, 5),
        'in_restaurant_number' => $faker->unique()->numberBetween(1,20),
        'available' => $faker->boolean(),
        'restaurant_id' => $faker->randomElement($restaurants),
    ];
});
