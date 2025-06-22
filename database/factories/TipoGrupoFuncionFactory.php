<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\TipoGrupoFuncion;
use Faker\Generator as Faker;

$factory->define(TipoGrupoFuncion::class, function (Faker $faker) {

    return [
        'tipogrupo_funcion' => $faker->randomDigitNotNull,
        'created_by' => $faker->randomDigitNotNull,
        'updated_by' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
