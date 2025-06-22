<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Usuario;
use Faker\Generator as Faker;

$factory->define(Usuario::class, function (Faker $faker) {

    return [
        'nombreusuario' => $faker->word,
        'clave' => $faker->word,
        'email' => $faker->word,
        'activo' => $faker->word,
        'usuario' => $faker->word,
        'operacion' => $faker->word,
        'foperacion' => $faker->date('Y-m-d H:i:s'),
        'idagente' => $faker->randomDigitNotNull,
        'idperfil' => $faker->randomDigitNotNull,
        'password' => $faker->word,
        'remember_token' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s'),
        'deleted_at' => $faker->date('Y-m-d H:i:s'),
        'api_token' => $faker->word
    ];
});
