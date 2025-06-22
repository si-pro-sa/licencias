<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\RecomendacionCandidato;
use App\Models\TipoFuncion;
use App\Models\TipoNivel;
use App\Models\TipoReferido;
use App\Models\Titulo;
use App\User;
use Faker\Generator as Faker;

$factory->define(RecomendacionCandidato::class, function (Faker $faker) {
    $funciones = TipoFuncion::all(['idtipo_funcion'])->toArray();
    $idtipo_funcion = $faker->randomElements($funciones,1,false)[0]['idtipo_funcion'];
    $titulos = Titulo::all(['idtitulo'])->toArray();
    $idtitulo = $faker->randomElements($titulos,1,false)[0]['idtitulo'];
    $niveles = TipoNivel::all(['idtipo_nivel'])->toArray();
    $idtipo_nivel = $faker->randomElements($niveles,1,false)[0]['idtipo_nivel'];
    $tipo_referido_interno = TipoReferido::where('interno',true)->get(['idtipo_referido'])->toArray();
    $idtipo_referido_interno = $faker->randomElements($tipo_referido_interno,1,false)[0]['idtipo_referido'];
    $tipo_referido_politico = TipoReferido::where('interno',false)->get(['idtipo_referido'])->toArray();
    $idtipo_referido_politico = $faker->randomElements($tipo_referido_politico,1,false)[0]['idtipo_referido'];

    $users = User::all(['idusuario'])->toArray();
    $idusuario = $faker->randomElements($users,1,false)[0]['idusuario'];
    return [
        'idtipo_funcion' => $idtipo_funcion,
        'idtitulo' => $idtitulo,
        'idtipo_nivel' => $idtipo_nivel,
        'created_by' => $idusuario,
        'updated_by' => $idusuario,
        'idtipo_referido_interno' => $idtipo_referido_interno,
        'idtipo_referido_politico' => $idtipo_referido_politico,
    ];
});
