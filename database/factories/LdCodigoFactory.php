<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\LdCodigo;
use Faker\Generator as Faker;

$factory->define(LdCodigo::class, function (Faker $faker) {

    return [
        'ldcodigo' => $faker->word,
        'horas_semanales' => $faker->randomDigitNotNull,
        'importe' => $faker->randomDigitNotNull,
        'idtipo_nivel' => $faker->randomDigitNotNull,
        'idtipo_agrupamiento' => $faker->randomDigitNotNull,
        'idld_tipo_alta' => $faker->randomDigitNotNull,
        'idtipo_funcion_jerarquica' => $faker->randomDigitNotNull,
        'usuario' => $faker->word,
        'operacion' => $faker->word,
        'foperacion' => $faker->date('Y-m-d H:i:s')
    ];
});
