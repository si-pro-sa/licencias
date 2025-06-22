<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Licencia::class, function (Faker $faker) {
    return [
        'idpuesto' => $faker->biasedNumberBetween(1,2),
        'idagente' => $faker->biasedNumberBetween(1,2),
        'idtipoLicencia' => $faker->biasedNumberBetween(1,2),
        'fecha_inicio' => $faker->date('1930-01-01','now'),
        'fecha_final' => $faker->date('1930-01-01','now')
    ];
});
