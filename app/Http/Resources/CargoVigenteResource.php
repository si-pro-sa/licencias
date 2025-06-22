<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class CargoVigenteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'idcargo' => $this->idcargo,
            //'idcargo_cambio_estado' => $this->idcargo_cambio_estado,
            'campania' => $this->tipoCampania->tipocampania,
            'tipo_cargo' => $this->tipoCargo->tipocargo_corto,
            'apellido_nombre' => $this->agentePropuesto->apellido_nombre,
            'documento' => $this->agentePropuesto->documento,
            'fdesde' => $this->fecha_inicio_alta,
            'fhasta' => $this->fecha_vencimiento_real ? Carbon::parse($this->fecha_vencimiento_real)->format('d/m/y') : '',
            'efector' => $this->efector->dependencia,
            'servicio' => $this->servicio->dependencia,
            'nivel' => $this->tipoNivel->tiponivel,
            'funcion' => $this->tipoFuncion->tipofuncion,
            //'tipo_formulario' => $this->calcularTipoFormulario($this->idcargo_tipo_formulario),
            'horarios' => $this->horarios->first()->horario_cargo,
        ];
    }

    private function calcularTipoFormulario($tipo_formulario)
    {
        $formulario = '';
        switch($tipo_formulario) {
            case 1:
                    $formulario = "NUEVO";
                    break;
            case 2: $formulario = "CONTINUIDAD";
                    break;
            default:
                    $formulario = "BAJA";
                    break;
        }

        return $formulario;
    }
}
