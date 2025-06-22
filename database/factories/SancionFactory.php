<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Sancion;
use Faker\Generator as Faker;

$factory->define(Sancion::class, function (Faker $faker) {

    return [
        'idagente' => $faker->word,
        'resolucion' => $faker->word,
        'reseña' => $faker->word,
        'conclusion' => $faker->word,
        'acuerdo' => $faker->word,
        'expediente' => $faker->word,
        'sale' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
