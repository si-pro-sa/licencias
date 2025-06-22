<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\HorarioDependencia;
use Faker\Generator as Faker;

$factory->define(HorarioDependencia::class, function (Faker $faker) {

    return [
        'idtipo_dia' => $faker->randomDigitNotNull,
        'iddependencia' => $faker->randomDigitNotNull,
        'hora_desde' => $faker->word,
        'hora_hasta' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'created_by' => $faker->randomDigitNotNull,
        'updated_by' => $faker->randomDigitNotNull
    ];
});
