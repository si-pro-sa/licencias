<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\PeriodoEPCargo;

class NovedadCargoExportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $fdesde_aut = $this->efectivaPrestacion->cargo->fecha_inicio_alta;
        $fhasta_aut = $this->efectivaPrestacion->cargo->fecha_vencimiento_real;
        $estado_vigente = $this->efectivaPrestacion->cargo->getEstadoVigente($this->efectivaPrestacion->periodoEP);
        return [
            'idnovedad_cargo' => $this->idnovedad_cargo,
            'idefectiva_prestacion_cargo' => $this->efectivaPrestacion->idefectiva_prestacion_cargo,
            'idcargo' => $this->efectivaPrestacion->cargo->idcargo,
            'campania' => $this->efectivaPrestacion->cargo->tipoCampania->tipocampania,
            'documento' => $this->efectivaPrestacion->cargo->agentePropuesto->documento,
            'apellido_nombre' => $this->efectivaPrestacion->cargo->agentePropuesto->apellido_nombre,
            'fnacimiento' => $this->efectivaPrestacion->cargo->agentePropuesto->fnacimiento->format('d/m/Y'),
            'dias' => $this->efectivaPrestacion->dias,
            'periodosDesdoblados' => $this->efectivaPrestacion->periodosDesdoblados->map->format(),
            'efector' => $this->efectivaPrestacion->cargo->efector->dependencia,
            'servicio' => $this->efectivaPrestacion->cargo->servicio->dependencia,
            'nivel' => $this->efectivaPrestacion->cargo->tipoNivel->tiponivel,
            'funcion' => $this->efectivaPrestacion->cargo->tipoFuncion->tipofuncion,
            'tipo_cargo' => $this->efectivaPrestacion->cargo->tipoCargo->tipocargo_corto,
            'primera_vez' => $this->efectivaPrestacion->primera_vez ? "SI":"NO",
            'estado_vigente' => $estado_vigente->cargoTipoFormulario->cargotipo_formulario,
            'periodo_ep' => $this->efectivaPrestacion->periodoEP->periodo,
            'periodo_liq' => $this->efectivaPrestacion->periodoLiquidacion->periodo,
            //'visado' => $this->tipoVisadoNovedad->tipovisado,

        ];
    }
}
