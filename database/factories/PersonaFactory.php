<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Persona::class, function (Faker $faker) {
    return [
        'documento' => $faker->biasedNumberBetween(1,99999999),
        'nombre' => $faker->firstName,
        'apellido' => $faker->lastName,
        'fecha_nacimiento' => $faker->date('1930-01-01','now'),
        'parentesco' => $faker->word,
        'discapacidad' => $faker->boolean
    ];
});
