<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CargoTipoVisadoEP;
use Faker\Generator as Faker;

$factory->define(CargoTipoVisadoEP::class, function (Faker $faker) {

    return [
        'cargo_tipo_visado_ep' => $faker->word
    ];
});
