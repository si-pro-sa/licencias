<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Agente;

class UpdateAgenteRequest extends FormRequest
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
        return [
            'documento' => 'required|numeric',
            'apellido' => 'required',
            'nombre' => 'required',
            'fnacimiento' => 'required|date',
            'cuil' => 'required|numeric',
            'falta' => 'required|date',
            'telefono' => 'numeric',
            'celular' => 'numeric',
            'email' => 'email',
            'idtipo_sexo' => 'required|numeric',
        ];
    }
}
