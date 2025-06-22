<?php
namespace App\Models;

use App\Models\Agente;
use App\Models\Periodo;
use App\Models\TipoFormularioEnum;

/*
* Clase para ordenar los parámetros utilizados para la contabilización de horas
*
*/
class ContadorHorasFormulario
{
    public function __construct(
        public Agente $agente,
        public Periodo $periodo,
        public ?string $fechaDesdeNueva = null,
        public ?string $fechaHastaNueva = null,
        public ?int $horasNuevas = 0,
        public ?TipoFormularioEnum $tipoFormulario = TipoFormularioEnum::OTRO,
        public ?bool $periodoSolo = true,
        public ?int $idtipo_guardia = 0,
        public ?int $idtipo_campania = 0
    ) {
    }

    public function getCantidadHorasGuardia()
    {
        switch ($this->horasNuevas) {
            case 6:
                return 5;
            case 12:
                return 10;
            case 18:
                return 15;
            case 24:
                return 20;
            default:
                return 999;
        }
    }
}
