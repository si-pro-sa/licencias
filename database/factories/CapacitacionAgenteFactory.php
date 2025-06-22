<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CapacitacionAgente;
use Faker\Generator as Faker;

$factory->define(CapacitacionAgente::class, function (Faker $faker) {

    return [
        'idCapacitacion' => $faker->word,
        'idAgente' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s')
    ];
});
