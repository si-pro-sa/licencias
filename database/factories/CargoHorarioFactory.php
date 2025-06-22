<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CargoHorario;
use Faker\Generator as Faker;

$factory->define(CargoHorario::class, function (Faker $faker) {

    return [
        'idcargo' => $faker->randomDigitNotNull,
        'idtipo_dia' => $faker->randomDigitNotNull,
        'idefector' => $faker->randomDigitNotNull,
        'idservicio' => $faker->randomDigitNotNull,
        'hora_desde' => $faker->word,
        'hora_hasta' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
