<?php

use App\User;
use App\Restaurant;
use Faker\Generator as Faker;

$factory->define(App\Order::class, function (Faker $faker) {
    $clients = User::where('role', 1)->pluck('id');
    $restaurants = Restaurant::all()->pluck('id');
    $order_types = ['App\OrderBooking', 'App\OrderDelivery'];
    return [
        'restaurant_id' => $faker->randomElement($restaurants),
        'client_id' => $faker->randomElement($clients),
        'order_time' => $faker->dateTimeThisYear($max = 'now', $timezone = null),
        'order_status' => $faker->numberBetween(0, 3),
        'menu_id' => $faker->numberBetween(1, 50),
        'orderDb_type' => $faker->randomElement($order_types),
        'updated_at' => $faker->dateTimeThisYear($max = 'now', $timezone = null)
    ];
});
