<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Candidato;
use App\Models\TipoFuncion;
use App\Models\TipoNivel;
use App\Models\TipoReferido;
use App\Models\TipoSexo;
use App\Models\Titulo;
use App\User;
use Faker\Generator as Faker;

$factory->define(Candidato::class, function (Faker $faker) {
    $funciones = TipoFuncion::all(['idtipo_funcion'])->toArray();
    $idtipo_funcion = $faker->randomElements($funciones,1,false)[0]['idtipo_funcion'];
    $titulos = Titulo::all(['idtitulo'])->toArray();
    $idtitulo = $faker->randomElements($titulos,1,false)[0]['idtitulo'];
    $niveles = TipoNivel::all(['idtipo_nivel'])->toArray();
    $idtipo_nivel = $faker->randomElements($niveles,1,false)[0]['idtipo_nivel'];
    $tipo_referido_interno = TipoReferido::where('interno',true)->get(['idtipo_referido'])->toArray();
    $idtipo_referido_interno = $faker->randomElements($tipo_referido_interno,1,false)[0]['idtipo_referido'];
    $tipo_sexo = TipoSexo::get(['idtipo_sexo'])->toArray();
    $idtipo_sexo = $faker->randomElements($tipo_sexo,1,false)[0]['idtipo_sexo'];

    print_r('entro');
    $users = User::all(['idusuario'])->toArray();
    $idusuario = $faker->randomElements($users,1,false)[0]['idusuario'];
    return [
        'documento' => $faker->numberBetween(10000000,99999999),
        'apellido' => $faker->lastName,
        'nombre' => $faker->name,
        'fnacimiento' => $faker->date('Y-m-d'),
        'celular' => $faker->phoneNumber,
        'idtipo_referido_interno' => $idtipo_referido_interno,
        'idtipo_sexo' => $idtipo_sexo,
        'idtipo_funcion' => $idtipo_funcion,
        'idtitulo' => $idtitulo,
        'idtipo_nivel' => $idtipo_nivel,
        'created_by' => $idusuario,
        'updated_by' => $idusuario,
    ];
});
