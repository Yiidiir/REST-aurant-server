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
        'work_hours' => 'a:8:{s:6:"monday";a:2:{i:0;s:11:"10:00-16:00";i:1;s:11:"18:00-23:00";}s:7:"tuesday";a:2:{i:0;s:11:"10:00-16:00";i:1;s:11:"18:00-23:00";}s:9:"wednesday";a:1:{i:0;s:11:"09:00-12:00";}s:8:"thursday";a:2:{i:0;s:11:"10:00-16:00";i:1;s:11:"18:00-23:00";}s:6:"friday";a:0:{}s:8:"saturday";a:2:{i:0;s:11:"10:00-16:00";i:1;s:11:"18:00-23:00";}s:6:"sunday";a:2:{i:0;s:11:"10:00-16:00";i:1;s:11:"18:00-23:00";}s:10:"exceptions";a:5:{s:10:"2019-11-11";a:1:{i:0;s:11:"09:00-12:00";}s:5:"01-01";a:0:{}s:5:"07-05";a:0:{}s:5:"11-01";a:0:{}s:5:"03-08";a:1:{i:0;s:11:"11:00-13:00";}}}'
    ];
});
