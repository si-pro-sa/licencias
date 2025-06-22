<?php

namespace App\Exports;

use App\Models\VEvaluacionPsicotecnica;
use App\Models\RecomendacionCandidato;
use App\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Yajra\DataTables\DataTableAbstract;

class VEvaluacionPsicotecnicaExport implements FromArray, WithHeadings
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
            $column = [
                'ID' => $psico['idevaluacion_psicotecnica'],
                'CALLE' => $psico['calle'],
                'DEPARTAMENTO' => $psico['departamento'],
                'LOCALIDAD' => $psico['localidad'],
                'C.P.' => $psico['codigo_postal'],
                'TEL.' => $psico['telefono'],
                'CELULAR' => $psico['celular'],
                'EMAIL' => $psico['email'],
                'DNI' => $psico['documento'],
                'NOMBRE' => $psico['nombre'],
                'F. NAC' => $psico['fnacimiento'],
                'POSTULA PARA' => $psico['tipofuncion'],
                'FORMACION' => $psico['titulo'],
                'NIVEL' => $psico['tiponivel'],
                'RESULTADO EVALUACIÓN' => $psico['tiporecomendacion'],
                'RECOMIENDA PARA' => $psico['recomienda_para'],
                'FECHA DE EVALUACION' => $psico['fecha_evaluacion'],
                'FECHA DE CREACIÓN' => $psico['fecha_creacion'],
                'OBSERVACIONES' => $psico['observaciones'],
                'psicoevaluador' => $psico['firma'],
                'TIPO DE EVALUACIÓN' => $psico['tipoentrevista'],
                'TÍTULO EVALUACIÓN GRUPAL' => $psico['evaluacion_psicotecnica_grupo'],
                'PUNTAJE' => $psico['puntaje'],
                'INGRESO' => $psico['ingreso'],
                'TIPO DE INGRESO' => $psico['tipo_ingreso_sin_dependencia'],
                'LUGAR' => $psico['lugar'],
            ];
            if (User::tienePermiso('Referido-consolidadoReferido1')){
                $column['REFERIDO 1'] = $psico['referido_1'];
            }
            if (User::tienePermiso('Referido-consolidadoReferido2')){
                $column['REFERIDO 2'] = $psico['referido_2'];
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
