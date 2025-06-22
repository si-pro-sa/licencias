<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\EvaluacionPsicotecnicaGrupo;
use App\User;
use Faker\Generator as Faker;

$factory->define(EvaluacionPsicotecnicaGrupo::class, function (Faker $faker) {
    $users = User::all(['idusuario'])->toArray();
    $idusuario = $faker->randomElements($users,1,false)[0]['idusuario'];
    return [
        'evaluacion_psicotecnica_grupo' => substr($faker->text,0,15),
        'created_by' => $idusuario,
        'updated_by' => $idusuario,
    ];
});
