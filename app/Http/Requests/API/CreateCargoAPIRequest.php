<?php
namespace App\Http\Requests\API;

use InfyOm\Generator\Request\APIRequest;
use App\Models\Cargo;

class CreateCargoAPIRequest extends APIRequest
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
        return Cargo::$rules;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    // public function messages()
    // {
    //     return [
    //         'tipoCargo.required' => 'Seleccione un Tipo de Formulario es requerido',
    //         'dniReemplazado.required' => 'Por favor seleccione el Agente a Reemplazar',
    //         'dniReemplazado.required' => 'Por favor seleccione el Agente a Reemplazar',
    //     ];
    // }
}
