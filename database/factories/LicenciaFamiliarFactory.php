<?php
use Faker\Generator as Faker;

$factory->define(App\Models\LicenciaFamiliar::class, function (Faker $faker) {
    return [
        'idlicencia' => $faker->biasedNumberBetween(1,2),
        'idpersona' => $faker->biasedNumberBetween(1,2)
    ];
});
