<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\TipoDia;
use Faker\Generator as Faker;

$factory->define(TipoDia::class, function (Faker $faker) {

    return [
        'tipodia' => $faker->word,
        'nombre_corto' => $faker->word
    ];
});
