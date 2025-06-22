<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class PuestosTotalesExport implements FromArray, WithHeadings
{
    use Exportable;
    private $puestosFormateados;

    public function array() : array
    {
        return $this->puestosFormateados;
    }

    public function headings(): array
    {
        return [
            'idpuesto',
            'idagente',
            'documento',
            'apellido',
            'nombre',
            'sexo',
            'fecha_nacimiento',
            'cuil',
            'fdesde',
            'fhasta',
            'tipo_planta',
            'tipo_agrupamiento',
            'tipo_funcion',
            'tipo_nivel',
            'dependencia_padre1',
            'servicio1',
            'dependencia_padre2',
            'servicio2',
            'dependencia_padre3',
            'servicio3',
            'dependencia_padre4',
            'servicio4',
            'dependencia_padre5',
            'servicio5',
        ];
    }

    public function __construct($puestosFormateados)
    {
        $this->puestosFormateados = $puestosFormateados;
    }
}
