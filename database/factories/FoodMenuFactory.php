<?php

use Faker\Generator as Faker;
use App\Food;
use App\Order;

$factory->define(App\FoodMenu::class, function (Faker $faker) {

    $foods = Food::all()->pluck('id');
    $orders = Order::all()->pluck('id');

    return [
        'food_id' => $faker->randomElement($foods),
        'order_id' => $faker->randomElement($orders),
    ];
});
