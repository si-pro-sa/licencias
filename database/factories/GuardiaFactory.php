<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Guardia;
use Faker\Generator as Faker;

$factory->define(Guardia::class, function (Faker $faker) {

    return [
        'fecha' => $faker->word,
        'fuera_termino' => $faker->word,
        'cantidad_lv' => $faker->randomDigitNotNull,
        'cantidad_sdf' => $faker->randomDigitNotNull,
        'cantidad_novedad_lv' => $faker->randomDigitNotNull,
        'cantidad_novedad_sdf' => $faker->randomDigitNotNull,
        'idperiodo' => $faker->randomDigitNotNull,
        'idefector' => $faker->randomDigitNotNull,
        'idservicio' => $faker->randomDigitNotNull,
        'idtipo_guardia' => $faker->randomDigitNotNull,
        'idtipo_campania' => $faker->randomDigitNotNull,
        'idtipo_formulario' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'created_by' => $faker->word,
        'updated_by' => $faker->word,
        'deleted_by' => $faker->word
    ];
});
