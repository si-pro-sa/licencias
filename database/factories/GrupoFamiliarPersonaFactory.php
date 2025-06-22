<?php
use Faker\Generator as Faker;

$factory->define(App\Models\GrupoFamiliarPersona::class, function (Faker $faker) {
    return [
        'idgrupoFamiliar' => $faker->biasedNumberBetween(1,2),
        'idpersona' => $faker->biasedNumberBetween(1,2)
    ];
});
