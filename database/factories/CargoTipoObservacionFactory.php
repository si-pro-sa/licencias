<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CargoTipoObservacion;
use Faker\Generator as Faker;

$factory->define(CargoTipoObservacion::class, function (Faker $faker) {

    return [
        'cargotipo_observacion' => $faker->word
    ];
});
