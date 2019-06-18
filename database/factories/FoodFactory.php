<?php

use App\Restaurant;
use Faker\Generator as Faker;

$factory->define(App\Food::class, function (Faker $faker) {
    $restaurants = Restaurant::all()->pluck('id');
    return [
        'restaurant_id' => $faker->randomElement($restaurants),
        'name' => $faker->sentence(2),
        'description' => $faker->paragraph,
        'price' => $faker->numberBetween(50,1000),
    ];
});
