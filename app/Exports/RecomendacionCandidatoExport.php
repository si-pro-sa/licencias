<?php

namespace App\Exports;

use App\Models\RecomendacionCandidato;
use App\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Yajra\DataTables\DataTableAbstract;

class RecomendacionCandidatoExport implements FromArray, WithHeadings
{
    use Exportable;
    private $candidatosSinPsico;

    /**
     * RecomendacionCandidatoExport constructor.
     * @param $candidatosSinPsico DataTableAbstract
     */
    public function __construct($candidatosSinPsico)
    {
        $this->candidatosSinPsico = $candidatosSinPsico->toArray()['data'];
    }

    public function array() : array
    {
        $data = [];
        foreach ($this->candidatosSinPsico as $key => $candidatoSinPsico) {
            $domicilio = $candidatoSinPsico['candidato']['domicilio_with_default'];
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
            $data[$key] = [
                $candidatoSinPsico['idrecomendacion_candidato'],
                $domicilio['calle'] . ' ' . $domicilio['numero'],
                $departamento['departamento'],
                $localidad['localidad'],
                $domicilio['codigo_postal'],
                $candidatoSinPsico['candidato']['telefono'],
                $candidatoSinPsico['candidato']['celular'],
                $candidatoSinPsico['candidato']['email'],
                $candidatoSinPsico['candidato']['documento'],
                $candidatoSinPsico['candidato']['apellido'] . ',' . $candidatoSinPsico['candidato']['nombre'],
                $candidatoSinPsico['candidato']['fnacimiento'],
                $candidatoSinPsico['especialidad_with_default']['tipofuncion'],
                $candidatoSinPsico['formacion_with_default']['titulo'],
                $candidatoSinPsico['nivel_with_default']['tiponivel'],
            ];
            if (User::tienePermiso('Referido-consolidadoReferido1')){
                array_push($data[$key],$candidatoSinPsico['referido_interno_with_default']['nombre']);
            }
            if (User::tienePermiso('Referido-consolidadoReferido2')){
                array_push($data[$key],$candidatoSinPsico['referido_politico_with_default']['nombre']);
            }
        }

        return $data;
    }

    public function headings(): array
    {
        $column = [
            'ID',
            'Dirección',
            'Departamento',
            'Localidad',
            'Código Postal',
            'Teléfono',
            'Celular',
            'email',
            'Documento',
            'Apellido y Nombre',
            'Fecha Nacimiento',
            'Postula Para',
            'Formación',
            'Nivel',
        ];
        if (User::tienePermiso('Referido-consolidadoReferido1')){
            array_push($column,'Referido 1');
        }
        if (User::tienePermiso('Referido-consolidadoReferido2')){
            array_push($column,'Referido 2');
        }
        return $column;
    }

    /**
     * @param string $permiso
     * @param $dato
     * @return string|null
     */
    private function tienePermiso(string $permiso,string $dato){
        if (User::tienePermiso($permiso)){
            return $dato;
        }
        return null;
    }


}
