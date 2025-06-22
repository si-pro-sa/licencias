<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CargoCambioEstado;
use Faker\Generator as Faker;

$factory->define(CargoCambioEstado::class, function (Faker $faker) {

    return [
        'idcargo' => $faker->randomDigitNotNull,
        'idperiodo_desde' => $faker->randomDigitNotNull,
        'fecha_desde' => $faker->word,
        'idperiodo_hasta' => $faker->randomDigitNotNull,
        'fecha_hasta' => $faker->word,
        'idcargo_tipo_visado' => $faker->randomDigitNotNull,
        'idtipo_formulario' => $faker->randomDigitNotNull,
        'fecha_ingreso' => $faker->word,
        'fecha_devolucion' => $faker->word,
        'fecha_entrega_organismo' => $faker->word,
        'observaciones_internas' => $faker->text,
        'motivo' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'created_by' => $faker->randomDigitNotNull,
        'updated_by' => $faker->randomDigitNotNull,
        'deleted_by' => $faker->randomDigitNotNull
    ];
});
