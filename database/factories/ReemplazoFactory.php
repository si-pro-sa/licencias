<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Reemplazo;
use Faker\Generator as Faker;

$factory->define(Reemplazo::class, function (Faker $faker) {

    return [
        'idperiodo' => $faker->randomDigitNotNull,
        'idformulario' => $faker->randomDigitNotNull,
        'iddependencia' => $faker->randomDigitNotNull,
        'idagente_reemplazado' => $faker->randomDigitNotNull,
        'idagente_reemplazante' => $faker->randomDigitNotNull,
        'usuario' => $faker->word,
        'operacion' => $faker->word,
        'foperacion' => $faker->date('Y-m-d H:i:s'),
        'fdesde' => $faker->word,
        'fhasta' => $faker->word,
        'idtipo_nivel' => $faker->randomDigitNotNull,
        'idtipo_funcion' => $faker->randomDigitNotNull,
        'idtipo_agrupamiento' => $faker->randomDigitNotNull,
        'idtipo_novedad' => $faker->randomDigitNotNull,
        'aprobado' => $faker->word,
        'desaprobado' => $faker->word,
        'idusuario' => $faker->randomDigitNotNull,
        'iddependenciapadre' => $faker->randomDigitNotNull,
        'idtipo_horario' => $faker->randomDigitNotNull,
        'horario' => $faker->word,
        'idpuesto_reemplazado' => $faker->randomDigitNotNull,
        'idpuesto_reemplazante' => $faker->randomDigitNotNull,
        'idtipo_solicitud' => $faker->randomDigitNotNull,
        'idreemplazo_padre' => $faker->randomDigitNotNull,
        'estado' => $faker->randomDigitNotNull,
        'novedad' => $faker->word,
        'idtipo_nivel_reemplazado' => $faker->randomDigitNotNull,
        'idtipo_nivel_reemplazante' => $faker->randomDigitNotNull
    ];
});
