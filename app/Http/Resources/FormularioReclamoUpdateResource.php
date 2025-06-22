<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\DependenciaVueSelectResource;

class FormularioReclamoUpdateResource extends JsonResource
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
            'agente' => AgenteVueSelectResource::make($this->agente),
            'efector' => DependenciaVueSelectResource::make($this->efector),
            'servicio' => DependenciaVueSelectResource::make($this->servicio),
            'periodoEP' => PeriodoVueSelectResource::make($this->periodoEP),
            'tipoFormulario' => TipoFormularioReclamoDePagoResource::make($this->tipoFormulario),
            'expediente' => $this->expediente,
            'tipoVisado' => $this->tipoVisado,
            'fdesde_ep' => $this->fdesde_ep->format('Y-m-d'),
            'fdesde_ep_es' => $this->fdesde_ep->format('d/m/Y'),
            'fhasta_ep' => $this->fhasta_ep->format('Y-m-d'),
            'fhasta_ep_es' => $this->fhasta_ep->format('d/m/Y'),
            'dias_ep' => $this->dias,
            'observacion_efector' => $this->observacion_efector,
            'observacion_ampliada' => $this->observacion_ampliada,
            'idtipo_visado' => $this->idtipo_visado,
            'created_at' => $this->created_at,
            'created_by' => $this->createdBy,
        ];
    }

    public function tipoFormulario($tipo)
    {
        switch ($tipo) {
            case 'cobertura_cargo':
                return 'COBERTURA DE CARGO';
                break;
            default:
                return 'COBERTURA DE CARGO';
                break;
        }
    }
}
