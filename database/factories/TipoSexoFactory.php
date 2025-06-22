<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\TipoSexo;
use Faker\Generator as Faker;

$factory->define(TipoSexo::class, function (Faker $faker) {

    return [
        'tiposexo' => $faker->word,
        'abreviatura' => $faker->word,
        'usuario' => $faker->word,
        'operacion' => $faker->word,
        'foperacion' => $faker->date('Y-m-d H:i:s')
    ];
});
