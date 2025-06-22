<?php

use Faker\Generator as Faker;

$factory->define(App\Models\GrupoFamiliar::class, function (Faker $faker) {
    return [
        'nExpediente' => $faker->biasedNumberBetween(1,99999999),
        'idagente' => $faker->biasedNumberBetween(1,5),
        'aprobado' => $faker->boolean,
        'activo' => $faker->boolean,
        'vencimiento' => $faker->date()
    ];
});
