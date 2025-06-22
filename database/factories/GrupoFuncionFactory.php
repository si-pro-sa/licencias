<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\GrupoFuncion;
use Faker\Generator as Faker;

$factory->define(GrupoFuncion::class, function (Faker $faker) {

    return [
        'idtipo_grupo_funcion' => $faker->randomDigitNotNull,
        'idtipo_funcion' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'created_by' => $faker->randomDigitNotNull,
        'updated_by' => $faker->randomDigitNotNull
    ];
});
