<?php

use Faker\Generator as Faker;
use App\Client;

$factory->define(Client::class, function (Faker $faker) {
    return [
        'id' => $faker->unique()->randomNumber,
        'first_name' => $faker->firstName(),
        'last_name' => $faker->lastName,
        'email' => $faker->email,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm'
    ];
});
