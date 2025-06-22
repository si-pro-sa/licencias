<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\HorarioPuesto;
use Faker\Generator as Faker;

$factory->define(HorarioPuesto::class, function (Faker $faker) {

    return [
        'idpuesto' => $faker->randomDigitNotNull,
        'idtipo_horario' => $faker->randomDigitNotNull,
        'dias_guardia' => $faker->word,
        'hora_desde' => $faker->word,
        'hora_hasta' => $faker->word,
        'usuario' => $faker->word,
        'operacion' => $faker->word,
        'foperacion' => $faker->date('Y-m-d H:i:s'),
        'puesto_id' => $faker->randomDigitNotNull,
        'puesto_type' => $faker->word,
        'idtipo_dia' => $faker->randomDigitNotNull,
        'iddependencia' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'created_by' => $faker->randomDigitNotNull,
        'updated_by' => $faker->randomDigitNotNull
    ];
});
