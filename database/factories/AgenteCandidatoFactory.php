<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Agente;
use Faker\Generator as Faker;

$factory->define(Agente::class, function (Faker $faker) {
    $agentes = Agente::inRandomOrder()->limit(10)->get(['idagente']);
    $idagente = $faker->randomElements($agentes,1,false)[0]['idagente'];
//    $idagente = $faker->numberBetween(0,50000);
    print_r("\n".$idagente);
    return [
        'idagente' => $idagente,
    ];
});
