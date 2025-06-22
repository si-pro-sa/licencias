<?php

namespace App\Exports;

use App\Models\EvaluacionPsicotecnica;
use App\Models\RecomendacionCandidato;
use App\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Yajra\DataTables\DataTableAbstract;

class EvaluacionPsicotecnicaExport implements FromArray, WithHeadings
{
    use Exportable;
    private $psicotecnicos;

    /**
     * EvaluacionPsicotecnicaExport constructor.
     * @param $psicotecnicos DataTableAbstract
     */
    public function __construct($psicotecnicos)
    {
        $this->psicotecnicos = $psicotecnicos->toArray()['data'];
    }

    public function array() : array
    {
        $data = [];
        foreach ($this->psicotecnicos as $key => $psico) {
            $domicilio = $psico['evaluacion_psicotecnica']['domicilio_with_default'];
            try{
                $localidad = $domicilio['localidad_with_default'];
            }catch (\Exception $exception){
                $localidad = ['localidad'=>''];
            }
            try{
                $departamento = $domicilio['departamento_with_default'];
            }catch (\Exception $exception){
                $departamento = ['departamento'=>''];
            }
            $column = [
                'ID' => $psico['idevaluacion_psicotecnica'],
                'CALLE' => $domicilio['calle'] . ' ' . $domicilio['numero'],
                'DEPARTAMENTO' => $departamento['departamento'],
                'LOCALIDAD' => $localidad['localidad'],
                'C.P.' => $domicilio['codigo_postal'],
                'TEL.' => $psico['evaluacion_psicotecnica']['telefono'],
                'CELULAR' => $psico['evaluacion_psicotecnica']['celular'],
                'EMAIL' => $psico['evaluacion_psicotecnica']['email'],
                'DNI' => $psico['evaluacion_psicotecnica']['documento'],
                'NOMBRE' => $psico['evaluacion_psicotecnica']['apellido'] . ',' . $psico['evaluacion_psicotecnica']['nombre'],
                'F. NAC' => $psico['evaluacion_psicotecnica']['fnacimiento'],
                'POSTULA PARA' => $psico['recomendacion']['especialidad']['tipofuncion'],
                'FORMACION' => isset($psico['recomendacion']['formacion']) ? $psico['recomendacion']['formacion']['titulo'] : "",
                'NIVEL' => isset($psico['recomendacion']['nivel']) ? $psico['recomendacion']['nivel']['tiponivel'] : "",
                'RESULTADO EVALUACIÓN' => $psico['tipo_recomendacion']['tiporecomendacion'],
                'RECOMIENDA PARA' => $psico['recomienda_para'],
                'FECHA DE EVALUACION' => $psico['fecha_evaluacion'],
                'FECHA DE CREACIÓN' => $psico['created_at'],
                'OBSERVACIONES' => $psico['observaciones'],
                'psicoevaluador' => $psico['psicoevaluador_with_trashed']['firma'],
                'TIPO DE EVALUACIÓN' => $psico['tipo_entrevista']['tipoentrevista'],
                'TÍTULO EVALUACIÓN GRUPAL' => $psico['grupo_with_default']['evaluacion_psicotecnica_grupo'],
                'PUNTAJE' => $psico['puntaje'],
                'INGRESO' => $psico['ingreso'],
                'TIPO DE INGRESO' => $psico['tipo_ingreso_sin_dependencia'],
                'LUGAR' => $psico['lugar'],
            ];
            if (User::tienePermiso('Referido-consolidadoReferido1')){
                $column['REFERIDO 1'] = $psico['recomendacion']['referido_interno_with_default']['nombre'];
            }
            if (User::tienePermiso('Referido-consolidadoReferido2')){
                $column['REFERIDO 2'] = $psico['recomendacion']['referido_politico_with_default']['nombre'];
            }
            $data[$key] = $column;
        }
        return $data;
    }

    public function headings(): array
    {
        $column = [
            'ID',
            'CALLE',
            'DEPARTAMENTO',
            'LOCALIDAD',
            'C.P.',
            'TEL.',
            'CELULAR',
            'EMAIL',
            'DNI',
            'NOMBRE',
            'F. NAC',
            'POSTULA PARA',
            'FORMACION',
            'NIVEL',
            'RESULTADO EVALUACIÓN',
            'RECOMIENDA PARA',
            'FECHA DE EVALUACION',
            'FECHA DE CREACIÓN',
            'OBSERVACIONES',
            'ENTREVISTADOR',
            'TIPO DE EVALUACIÓN',
            'TÍTULO EVALUACIÓN GRUPAL',
            'PUNTAJE',
            'INGRESO',
            'TIPO DE INGRESO',
            'LUGAR',
        ];
        if (User::tienePermiso('Referido-consolidadoReferido1')){
            array_push($column,'REFERIDO 1');
        }
        if (User::tienePermiso('Referido-consolidadoReferido2')){
            array_push($column,'REFERIDO 2');
        }
        return $column;
    }

    public function setData($psicotecnicos){
        $this->psicotecnicos = $psicotecnicos;
    }

}
