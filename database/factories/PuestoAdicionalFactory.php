<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\PuestoAdicional;
use Faker\Generator as Faker;

$factory->define(PuestoAdicional::class, function (Faker $faker) {

    return [
        'idpuesto' => $faker->randomDigitNotNull,
        'iddependencia' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'created_by' => $faker->randomDigitNotNull,
        'updated_by' => $faker->randomDigitNotNull
    ];
});
