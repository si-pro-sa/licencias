<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CargoDevolucionRelacion;
use Faker\Generator as Faker;

$factory->define(CargoDevolucionRelacion::class, function (Faker $faker) {

    return [
        'idcargo_devuelto' => $faker->randomDigitNotNull,
        'idcargo' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'created_by' => $faker->randomDigitNotNull,
        'updated_by' => $faker->randomDigitNotNull
    ];
});
