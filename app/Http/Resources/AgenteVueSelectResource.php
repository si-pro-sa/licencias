<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AgenteVueSelectResource extends JsonResource
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
            'apellido_nombre' => $this->apellido_nombre,
            'documento' => $this->documento,
            'label' =>
                ucwords(mb_strtolower($this->apellido)) .
                ', ' .
                ucwords(mb_strtolower($this->nombre)) .
                ' - ' .
                $this->documento,
            'value' => $this->idagente,
        ];
    }
}
