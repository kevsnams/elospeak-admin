<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CustomerMessage;
use Faker\Generator as Faker;

$factory->define(CustomerMessage::class, function (Faker $faker) {
    return [
        'email' => $faker->safeEmail,
        'full_name' => $faker->name,
        'message' => $faker->realText(mt_rand(500, 1000)),
        'is_read' => $faker->numberBetween(0, 1)
    ];
});
