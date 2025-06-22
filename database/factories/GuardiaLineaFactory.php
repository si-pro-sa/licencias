<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\GuardiaLinea;
use Faker\Generator as Faker;

$factory->define(GuardiaLinea::class, function (Faker $faker) {

    return [
        'idguardia' => $faker->randomDigitNotNull,
        'hora_desde' => $faker->word,
        'hora_hasta' => $faker->word,
        'idpuesto' => $faker->randomDigitNotNull,
        'idguardia_tipo_estado_linea' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'created_by' => $faker->word,
        'updated_by' => $faker->word,
        'deleted_by' => $faker->word,
        'aprobado' => $faker->word
    ];
});
