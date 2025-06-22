<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Domicilio;
use App\Models\Localidad;
use App\User;
use Faker\Generator as Faker;

$factory->define(Domicilio::class, function (Faker $faker) {
    /** @var Localidad $localidad */
    $localidad = Localidad::where('idprovincia',90)->get(['idlocalidad'])->toArray();
    $idlocalidad = $faker->randomElements($localidad,1,false)[0]['idlocalidad'];
    $localidad = Localidad::find($idlocalidad);
    $departamento =  $localidad->departamento;
    $provincia = $departamento->provincia;

    $users = User::all(['idusuario'])->toArray();
    $idusuario = $faker->randomElements($users,1,false)[0]['idusuario'];
    return [
        'calle' => $faker->streetName,
        'numero' => $faker->randomDigit,
        'departamento' => $faker->randomElements(array('a','b','c',1,2,3),1)[0],
        'piso' => $faker->randomElements(array(1,2,3,4,5,6,7,8,9,10),1)[0],
        'block' => $faker->randomElements(array(1,2,3,null),1)[0],
        'codigo_postal' => $faker->postcode,
        'idlocalidad' => $idlocalidad,
        'iddepartamento' => $departamento->iddepartamento,
        'idprovincia' => $provincia->idprovincia,
        'created_by' => $idusuario,
        'updated_by' => $idusuario,
    ];
});
