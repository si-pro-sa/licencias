<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CargoTipoVisado;
use Faker\Generator as Faker;

$factory->define(CargoTipoVisado::class, function (Faker $faker) {

    return [
        'cargotipo_visado' => $faker->word
    ];
});
