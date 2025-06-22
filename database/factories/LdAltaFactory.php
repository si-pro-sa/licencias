<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\LdAlta;
use Faker\Generator as Faker;

$factory->define(LdAlta::class, function (Faker $faker) {

    return [
        'fecha_creado' => $faker->date('Y-m-d H:i:s'),
        'fdesde' => $faker->word,
        'fhasta' => $faker->word,
        'fuera_termino' => $faker->word,
        'valor' => $faker->randomDigitNotNull,
        'info_adicional' => $faker->text,
        'bloqueado' => $faker->word,
        'idtipo_formulario' => $faker->randomDigitNotNull,
        'idld_estado' => $faker->randomDigitNotNull,
        'idld_tipo_alta' => $faker->randomDigitNotNull,
        'idpuesto' => $faker->randomDigitNotNull,
        'iddependencia_origen' => $faker->randomDigitNotNull,
        'iddependencia_destino' => $faker->randomDigitNotNull,
        'idefector' => $faker->randomDigitNotNull,
        'idld_codigo' => $faker->randomDigitNotNull,
        'idperiodo' => $faker->randomDigitNotNull,
        'idtipo_agrupamiento' => $faker->randomDigitNotNull,
        'usuario' => $faker->word,
        'operacion' => $faker->word,
        'foperacion' => $faker->date('Y-m-d H:i:s')
    ];
});
