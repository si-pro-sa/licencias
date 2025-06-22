<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CargoCambioEstadoObservacion;
use Faker\Generator as Faker;

$factory->define(CargoCambioEstadoObservacion::class, function (Faker $faker) {

    return [
        'idcargo_cambio_estado' => $faker->randomDigitNotNull,
        'idcargo_tipo_observacion' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'created_by' => $faker->randomDigitNotNull,
        'updated_by' => $faker->randomDigitNotNull,
        'deleted_by' => $faker->randomDigitNotNull
    ];
});
