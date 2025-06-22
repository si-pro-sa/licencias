<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReclamoDePagoResource extends JsonResource
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
            'idreclamo_de_pago' => $this->idreclamo_de_pago,
            'apellido_nombre' => $this->agente->apellido_nombre,
            'tipo_formulario' => $this->tipo_formulario,
            'documento' => $this->agente->documento,
            'efector' => $this->efector->dependencia,
            'servicio' => $this->servicio->dependencia,
            'periodo_ep' => $this->periodoEP->periodo,
            'fdesde_ep_us' => $this->fdesde_ep,
            'fdesde_ep_es' => $this->fdesde_ep->format('d/m/Y'),
            'fhasta_ep_us' => $this->fhasta_ep,
            'fhasta_ep_es' => $this->fhasta_ep->format('d/m/Y'),
            'dias_ep' => $this->dias,
            'observacion' => $this->observacionInicial[0],
            'observaciones_visado' => [],
            'tipo_visado' => $this->tipoVisado->tipo_visado,
            'idtipo_visado' => $this->idtipo_visado,
        ];
    }
}
