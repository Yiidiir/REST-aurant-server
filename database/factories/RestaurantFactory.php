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
        'updated_at' => $faker->dateTimeThisYear($max = 'now', $timezone = null),
        'work_hours' => "{
    monday: ['09:00-12:00', '13:00-18:00'],
    tuesday: ['09:00-12:00', '13:00-18:00'],
    wednesday: ['09:00-12:00'],
    thursday: ['09:00-12:00', '13:00-18:00'],
    friday: ['09:00-12:00', '13:00-20:00'],
    saturday: ['09:00-12:00', '13:00-16:00'],
    sunday: [],
    exceptions: {'2016-11-11': ['09:00-12:00'], '2016-12-25': [], '01-01': [], '12-25': ['09:00-12:00']}
  }"
    ];
});
