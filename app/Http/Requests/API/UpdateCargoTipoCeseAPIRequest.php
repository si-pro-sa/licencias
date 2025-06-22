<?php

namespace App\Http\Requests\API;

use InfyOm\Generator\Request\APIRequest;
use App\Models\CargoTipoCese;
use Illuminate\Validation\Rule;

class UpdateCargoTipoCeseAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $idtipo_cargo = $this->idtipo_cargo;
        $idtipo_cese = $this->idtipo_cese;
        $idcargo_tipo_cese = $this->idcargo_tipo_cese;
        return [
            'idtipo_cargo' => [
                'required',
                'numeric',
                Rule::unique('cargo_tipo_cese')
                    ->where(function ($query) use ($idtipo_cargo, $idtipo_cese) {
                        return $query->where('idtipo_cargo', $idtipo_cargo)
                            ->where('idtipo_cese', $idtipo_cese);
                    })->ignore($idcargo_tipo_cese, 'idcargo_tipo_cese')
            ],
        'idtipo_cese' => [
            'required',
            'numeric',
            Rule::unique('cargo_tipo_cese')->ignore($idcargo_tipo_cese, 'idcargo_tipo_cese')
            ],
        'agente_reemplazado' => 'boolean'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'idtipo_cargo.required' => 'Tiene que seleccionar un Tipo de Cargo',
            'idtipo_cargo.unique' => 'La combinación elegida no es única',
            'idtipo_cese.required' => 'Tiene que seleccionar un Causal',
            'idtipo_cese.unique' => 'El Causal ya fue utilizado',
        ];
    }
}
