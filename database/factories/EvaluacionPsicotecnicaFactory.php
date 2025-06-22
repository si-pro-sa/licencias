<?php

/** @var Factory $factory */

use App\Model;
use App\Models\EvaluacionPsicotecnicaGrupo;
use App\Models\EvaluacionPsicotecnica;
use App\Models\PsicoEvaluador;
use App\Models\TipoEntrevista;
use App\Models\TipoFuncion;
use App\Models\TipoRecomendacion;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(EvaluacionPsicotecnica::class, function (Faker $faker) {
    $tipo_entrevista = TipoEntrevista::all(['idtipo_entrevista'])->toArray();
    $idtipo_entrevista = $faker->randomElements($tipo_entrevista,1,false)[0]['idtipo_entrevista'];
    $psicoevaluador = PsicoEvaluador::all(['idpsicoevaluador'])->toArray();
    $idpsicoevaluador = $faker->randomElements($psicoevaluador,1,false)[0]['idpsicoevaluador'];
    $tipofuncion = TipoFuncion::all(['idtipo_funcion'])->toArray();
    $idtipo_funcion1 = $faker->randomElements($tipofuncion,1,true)[0]['idtipo_funcion'];
    $idtipo_funcion2 = $faker->randomElements($tipofuncion,1,true)[0]['idtipo_funcion'];
    $idtipo_funcion3 = $faker->randomElements($tipofuncion,1,true)[0]['idtipo_funcion'];
    $evaluacion_psicotecnica_grupo = EvaluacionPsicotecnicaGrupo::all(['idevaluacion_psicotecnica_grupo'])->toArray();
    $idevaluacion_psicotecnica_grupo = $faker->randomElements($evaluacion_psicotecnica_grupo,1,true)[0]['idevaluacion_psicotecnica_grupo'];
    $tipo_recomendacion = TipoRecomendacion::all(['idtipo_recomendacion'])->toArray();
    $idtipo_recomendacion = $faker->randomElements($tipo_recomendacion,1,true)[0]['idtipo_recomendacion'];

    $users = User::all(['idusuario'])->toArray();
    $idusuario = $faker->randomElements($users,1,false)[0]['idusuario'];
    return [
        'idtipo_entrevista' => $idtipo_entrevista,
        'fecha_evaluacion' => $faker->date(),
        'desempeno' => $faker->randomElements(array(1.5,3,4.5,6,7.5),1,true)[0],
        'aspectos_cognitivos' => $faker->randomElements(array(3,6,9,12,15),1,true)[0],
        'aspectos_psicoafectivos' => $faker->randomElements(array(3,6,9,12,15),1,true)[0],
        'motivacion' => $faker->randomElements(array(1.5,3,4.5,6,7.5),1,true)[0],
        'experiencia_laboral' => $faker->randomElements(array(true,false),1,true)[0],
        'sector_publico' => $faker->randomElements(array(true,false),1,true)[0],
        'observaciones' => substr($faker->text,0,100),
        'atencion_usuario' => $faker->randomElements(array(2.2,4.4,6.6,8.8,11),1,true)[0],
        'trabajo_en_equipo' => $faker->randomElements(array(2.2,4.4,6.6,8.8,11),1,true)[0],
        'adaptabilidad' => $faker->randomElements(array(2.2,4.4,6.6,8.8,11),1,true)[0],
        'tolerancia_presion' => $faker->randomElements(array(2.2,4.4,6.6,8.8,11),1,true)[0],
        'organizacion' => $faker->randomElements(array(2.2,4.4,6.6,8.8,11),1,true)[0],
        'idpsicoevaluador' => $idpsicoevaluador,
        'idtipo_funcion1' => ($idtipo_recomendacion == 1) ? $idtipo_funcion1 : null,
        'idtipo_funcion2' => ($idtipo_recomendacion == 1) ? $idtipo_funcion2 : null,
        'idtipo_funcion3' => ($idtipo_recomendacion == 1) ? $idtipo_funcion3 : null,
        'idevaluacion_psicotecnica_grupo' => ($idtipo_entrevista == 2) ? $idevaluacion_psicotecnica_grupo : null,
        'idtipo_recomendacion' => $idtipo_recomendacion,
        'created_by' => $idusuario,
        'updated_by' => $idusuario,
    ];
});
