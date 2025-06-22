<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NovedadCargoExport implements FromCollection, WithHeadings
{
    use Exportable;

    private $data;

    public function __construct($data)
    {
        $this->data = $data;

    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'idnovedad_cargo',
            'idefectiva_prestacion_cargo',
            'idcargo',
            'campania',
            'documento',
            'apellido_nombre',
            'fnacimiento',
            'dias',
            'ep_desde',
            'ep_hasta',
            'efector',
            'servicio',
            'nivel',
            'funcion',
            'tipo_cargo',
            'agrupamiento',
            'primera_vez',
            'estado_vigente',
            'periodo_ep',
            'periodo_liq',
            'titular',
            'observaciones',
            'visado',
            'horario',
        ];
    }

}
