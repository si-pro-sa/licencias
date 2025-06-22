<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CargoReemplazado;
use Faker\Generator as Faker;

$factory->define(CargoReemplazado::class, function (Faker $faker) {

    return [
        'idcargo' => $faker->randomDigitNotNull,
        'resolucion_ministerial' => $faker->word,
        'idpuesto' => $faker->randomDigitNotNull,
        'idtipo_funcion' => $faker->randomDigitNotNull,
        'idtipo_nivel' => $faker->randomDigitNotNull,
        'idtipo_agrupamiento' => $faker->randomDigitNotNull,
        'idagentetitulo' => $faker->randomDigitNotNull,
        'idtipo_especialidad' => $faker->randomDigitNotNull,
        'idtipo_cese' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'created_by' => $faker->randomDigitNotNull,
        'updated_by' => $faker->randomDigitNotNull,
        'deleted_by' => $faker->randomDigitNotNull
    ];
});
