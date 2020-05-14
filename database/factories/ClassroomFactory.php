<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Classroom;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Classroom::class, function (Faker $faker) {
    $start = Carbon::createFromFormat('Y-m-d H:i', $faker->dateTime('+50 days')->format('Y-m-d H:i'));
    $end = Carbon::createFromFormat('Y-m-d H:i', $start->format('Y-m-d H:i'));

    return [
        'start' => $start->format('Y-m-d H:i'),
        'end' => $end->addMinutes(25)->format('Y-m-d H:i'),
        'status' => $faker->numberBetween(0, 1),
        'price' => $faker->numberBetween(100, 500)
    ];
});
