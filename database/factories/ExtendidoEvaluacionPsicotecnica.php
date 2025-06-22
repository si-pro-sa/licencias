<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\ExtendidoEvaluacionPsicotecnica;
use App\User;
use Faker\Generator as Faker;

$factory->define(ExtendidoEvaluacionPsicotecnica::class, function (Faker $faker) {
    $users = User::all(['idusuario'])->toArray();
    $idusuario = $faker->randomElements($users,1,false)[0]['idusuario'];
    return [
        'presentacion' => substr($faker->text,0,100),
        'aspectos_cognitivos' => substr($faker->text,0,100),
        'modalidad_relacional' => substr($faker->text,0,100),
        'motivacion' => substr($faker->text,0,100),
        'created_by' => $idusuario,
        'updated_by' => $idusuario,
    ];
});
