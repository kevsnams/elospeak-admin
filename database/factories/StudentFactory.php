<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Classroom;
use App\Student;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

$factory->define(Student::class, function (Faker $faker) {
    return [
        'username' => $faker->userName,
        'password' => Hash::make('password'),
        'email' => $faker->safeEmail,
        'full_name' => $faker->firstName . ' ' . $faker->lastName
    ];
});


$factory->afterCreating(Student::class, function (Student $student, Faker $faker) {
    $student->classrooms()->saveMany(
        factory(Classroom::class, 10)->make()
    );
});
