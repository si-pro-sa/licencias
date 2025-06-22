<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Capacitacion;
use Faker\Generator as Faker;

$factory->define(Capacitacion::class, function (Faker $faker) {

    return [
        'resolucion' => $faker->word,
        'razon' => $faker->word,
        'evento_nombre' => $faker->word,
        'evento_lugar' => $faker->word,
        'fecha_evento_inicio' => $faker->word,
        'fecha_evento_final' => $faker->word,
        'idCaracter' => $faker->word,
        'idTipoEvento' => $faker->word,
        'idAlcanceCapacitacion' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
