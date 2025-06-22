<?php

namespace App\Exports;

use App\Models\Puesto;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class VAgentesPlantaExport implements FromArray, WithHeadings
{
    use Exportable;
    private $agentes;

    public function array() : array
    {
        $data = [];
        foreach ($this->agentes as $key => $puesto) {
            $data[$key] = [
                $puesto['idagente'],
                $puesto['idpuesto'],
                $puesto['iddependencia'],
                $puesto['documento'],
                $puesto['apellido'],
                $puesto['nombre'],
                $puesto['grupo_funcion'],
                $puesto['funcion'],
                $puesto['planta'],
                $puesto['area_operativa'],
                $puesto['efector'],
                $puesto['servicio'],
                $puesto['hora_desde'],
                $puesto['hora_hasta'],
                $puesto['tipodia'],
                $puesto['cantidad_horas'],
                $puesto['horario_nuevo'],
            ];
        }
        return $data;
    }

    public function headings(): array
    {
        return [
            'IDAGENTE',
            'IDPUESTO',
            'IDDEPENDENCIA',
            'DNI',
            'APELLIDO',
            'NOMBRE',
            'GRUPO FUNCIÓN',
            'FUNCIÓN',
            'PLANTA',
            'ÁREA OPERATIVA',
            'EFECTOR',
            'SERVICIO',
            'HORA_DESDE',
            'HORA_HASTA',
            'DÍA',
            'HS',
            'SISTEMA_HORARIO_NUEVO'
        ];
    }

    public function __construct($agentes)
    {
        $this->agentes = $agentes->toArray()['data'];
    }
}
