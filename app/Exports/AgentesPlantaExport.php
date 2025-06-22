<?php

namespace App\Exports;

use App\Models\Puesto;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class AgentesPlantaExport implements FromArray, WithHeadings
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
                $puesto['agente']['documento'],
                $puesto['agente']['apellido_nombre'],
                $puesto['tipo_funcion']['tipofuncion'],
                $puesto['tipo_planta']['tipoplanta'],
                $puesto['efector'],
                $puesto['dependencia']['dependencia'],
                $puesto['hora_desde_formateada'],
                $puesto['hora_hasta_formateada'],
                $puesto['tipo_dia_formateado'],
                $puesto['cantidad_horas_formateado'],
                empty($puesto['DT_RowClass']) ? 'SI' : 'NO',
            ];
        }
        return $data;
    }

    public function headings(): array
    {
        return [
            'IDAGENTE',
            'IDPUESTO',
            'DNI',
            'APELLIDO Y NOMBRE',
            'FUNCIÓN',
            'PLANTA',
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
