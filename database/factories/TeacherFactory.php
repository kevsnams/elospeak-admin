<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Teacher;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

$factory->define(Teacher::class, function (Faker $faker) {
    return [
        'username' => $faker->userName,
        'password' => Hash::make('password'),
        'email' => $faker->safeEmail,
        'full_name' => $faker->firstName .' '. $faker->lastName,
        'skype' => 'skype_'. $faker->userName,
        'educational_attainment' => $faker->randomElement(
            [ Teacher::EDUC_GRADUATE, Teacher::EDUC_UNDERGRADUATE ]
        ),
        'birthday' => $faker->date('Y-m-d', '-18 years')
    ];
});
