<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\PeriodoEPCargo;

class NovedadCargoResource extends JsonResource
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
        $limites_periodo_ep = PeriodoEPCargo::limitPeriodo($fdesde_aut, $fhasta_aut, $this->efectivaPrestacion->periodoEP);
        return [
            'idnovedad_cargo' => $this->idnovedad_cargo,
            'idefectiva_prestacion_cargo' => $this->idefectiva_prestacion_cargo,
            'idcargo' => $this->efectivaPrestacion->idcargo,
            'idtipo_visado_novedad' => $this->idtipo_visado_novedad,
            'idtipo_observacion_novedad' => $this->idtipo_observacion_novedad,
            'apellido_nombre' => $this->efectivaPrestacion->cargo->agentePropuesto->apellido_nombre,
            'documento' => $this->efectivaPrestacion->cargo->agentePropuesto->documento,
            'fnacimiento' => $this->efectivaPrestacion->cargo->agentePropuesto->fnacimiento->format('d/m/Y'),
            'dias' => $this->efectivaPrestacion->dias,
            'idefector' => $this->efectivaPrestacion->cargo->idefector,
            'efector' => $this->efectivaPrestacion->cargo->efector->dependencia,
            'idservicio' => $this->efectivaPrestacion->cargo->idservicio,
            'servicio' => $this->efectivaPrestacion->cargo->servicio->dependencia,
            'titular' => $this->efectivaPrestacion->cargo->cargoReemplazado()->exists() ? $this->efectivaPrestacion->cargo->cargoReemplazado->apellido_nombre:' - ',
            'campania' => $this->efectivaPrestacion->cargo->tipoCampania->tipocampania,
            'nivel' => $this->efectivaPrestacion->cargo->tipoNivel->tiponivel,
            'periodosDesdoblados' => $this->efectivaPrestacion->periodosDesdoblados->map->format(),
            'periodos' => $this->efectivaPrestacion->periodosDesdoblados->map->format(),
            'comentarios' => $this->comentarios,
            'primera_vez' => $this->efectivaPrestacion->primera_vez ? true:false,
            'tipo_cargo' => $this->efectivaPrestacion->cargo->tipoCargo->tipocargo_corto,
            'liquidado' => $this->liquidado,
            'tipo_agrupamiento' => strtoupper($this->efectivaPrestacion->cargo->tipoAgrupamiento->tipoagrupamiento),
            'estado' => $estado_vigente ? $estado_vigente->cargoTipoFormulario->cargotipo_formulario:null,
            'tipo_formulario' => $estado_vigente ? $estado_vigente->cargoTipoFormulario->cargotipo_formulario:null,
            'funcion' => $this->efectivaPrestacion->cargo->tipoFuncion->tipofuncion,
            'rechazado' => $this->efectivaPrestacion->rechazado ? true:false,
            'horarios' => $this->efectivaPrestacion->cargo->horarios->first()->horario_cargo,
            'periodo_ep' => $this->efectivaPrestacion->periodoEP->periodo,
            'periodo_liq' => $this->efectivaPrestacion->periodoLiquidacion->periodo,
            'fdesde' => $fdesde_aut->format('d/m/Y'),
            'fhasta' => $fhasta_aut ? $fhasta_aut->format('d/m/Y'): ' - ',
            'horario' => $this->efectivaPrestacion->cargo->horarios->first()->horario_cargo,
            'fdesde_limit' => $limites_periodo_ep['fdesde']->format('Y-m-d'),
            'fhasta_limit' => $limites_periodo_ep['fhasta']->format('Y-m-d'),
            'rectificada' => $this->efectivaPrestacion->rectificada ? true:false,
        ];
    }
}
