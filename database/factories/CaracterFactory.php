<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Caracter;
use Faker\Generator as Faker;

$factory->define(Caracter::class, function (Faker $faker) {

    return [
        'codigo' => $faker->randomDigitNotNull,
        'descripcion' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
