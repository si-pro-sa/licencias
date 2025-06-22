<?php
use Faker\Generator as Faker;

$factory->define(App\Models\TipoLicencia::class, function (Faker $faker) {
    return [
        'codigo' => $faker->biasedNumberBetween(1,10),
        'descripcion' => $faker->word
    ];
});
