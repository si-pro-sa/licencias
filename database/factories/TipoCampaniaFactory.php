<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\TipoCampania;
use Faker\Generator as Faker;

$factory->define(TipoCampania::class, function (Faker $faker) {

    return [
        'tipocampania' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'created_by' => $faker->word,
        'updated_by' => $faker->word,
        'deleted_by' => $faker->word
    ];
});
