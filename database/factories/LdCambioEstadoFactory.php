<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\LdCambioEstado;
use Faker\Generator as Faker;

$factory->define(LdCambioEstado::class, function (Faker $faker) {

    return [
        'fecha_creado' => $faker->date('Y-m-d H:i:s'),
        'fhasta' => $faker->word,
        'fuera_termino' => $faker->word,
        'usada' => $faker->word,
        'info_adicional' => $faker->text,
        'idefector' => $faker->randomDigitNotNull,
        'idperiodo' => $faker->randomDigitNotNull,
        'idld_estado' => $faker->randomDigitNotNull,
        'idld_tipo_cambio_estado' => $faker->randomDigitNotNull,
        'idld_alta' => $faker->randomDigitNotNull,
        'idtipo_formulario' => $faker->randomDigitNotNull,
        'usuario' => $faker->word,
        'operacion' => $faker->word,
        'foperacion' => $faker->date('Y-m-d H:i:s'),
        'bloqueado' => $faker->word
    ];
});
