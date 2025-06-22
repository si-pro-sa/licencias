<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CargosVigentesExport implements FromCollection, WithHeadings
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
            'idcargo',
            'idcargo_cambio_estado',
            'campania',
            'tipo_formulario',
            'apellido_nombre',
            'documento',
            'fdesde',
            'fhasta',
            'efector',
            'servicio',
            'nivel',
            'cargo',
            'continuidad',
            'horarios',
        ];
    }

}
