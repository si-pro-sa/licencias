<?php
namespace App\Models;

use App\Models\ExportEPTxt;
use Illuminate\Support\Facades\Storage;

/**
* Class TipoAgrupamiento
* @package App\Models
* @version March 30, 2022, 7:47 pm UTC
*
*/
class ExportEPGuardias extends ExportEPTxt
{
    public function transformarAFormatoAdelia()
    {
        $periodo = Periodo::find($this->idperiodo);
        foreach ($this->datosSinFormatear as $epGuardia) {
            $formatoAdeliaLV = new FormatoAdelia(
                $epGuardia->documento,
                $epGuardia->codigo_lv,
                $epGuardia->cantidad_lv,
                $periodo->fdesde,
                $periodo->fhasta,
                $periodo->fdesde,
            );
            $formatoAdeliaSDF = new FormatoAdelia(
                $epGuardia->documento,
                $epGuardia->codigo_sdf,
                $epGuardia->cantidad_sdf,
                $periodo->fdesde,
                $periodo->fhasta,
                $periodo->fdesde,
            );
            array_push($this->datos, $formatoAdeliaLV->getTextoFormateado());
            array_push($this->datos, $formatoAdeliaSDF->getTextoFormateado());
            $this->agregarLineasATxt();
        }
    }
}
