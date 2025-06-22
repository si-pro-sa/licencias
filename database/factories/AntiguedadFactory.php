<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Antiguedad;
use Faker\Generator as Faker;

$factory->define(Antiguedad::class, function (Faker $faker) {
    return [
        'idagente' => '11806',
        'año' => $faker->numberBetween(2000,2030),
        'pedido' => $faker->numberBetween(0,20),
        'disponible' => 30,
        'vigente' => true
    ];
});
