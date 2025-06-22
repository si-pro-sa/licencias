<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class HorariosFormulariosExport implements FromArray, WithHeadings
{
    use Exportable;
    private $puestosConHorasDeFormularios;

    public function array() : array
    {
        return $this->puestosConHorasDeFormularios;
    }

    public function headings(): array
    {
        return [
            'idpuesto',
            'idagente',
            'documento',
            'apellido',
            'nombre',
            'puesto',
            'libre_disponibilidad',
            'guardia',
            'guardia_especial',
            'reemplazo',
            'guardia_real',
            'total',
            'total_real',
            '-',
            'horas_maximas_puesto',
            'horas_maximas_reemplazos',
            'horas_maximas_libres',
            'horas_maximas_guardias',
        ];
    }

    public function __construct($puestosConHorasDeFormularios)
    {
        $this->puestosConHorasDeFormularios = $puestosConHorasDeFormularios;
    }
}
