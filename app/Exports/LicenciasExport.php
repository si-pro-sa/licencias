<?php

namespace App\Exports;

use App\Models\Licencia;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Carbon;
use Maatwebsite\Excel\Concerns\FromArray;

class LicenciasExport implements FromArray
{
    protected $licencias;

    public function __construct(array $licencias)
    {
        $this->licencias = $licencias;
    }

    public function array(): array
    {
        return $this->licencias;
    }
}
