<?php

namespace App\Http\Requests\API;

use App\Models\Agente;
use InfyOm\Generator\Request\APIRequest;
use App\Rules\Cuil;

class CreateAgenteAPIRequest extends APIRequest
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
            'documento' => 'required|numeric|unique:agente|min:999999|max:999999999',
            'apellido' => 'required',
            'nombre' => 'required',
            'fnacimiento' => 'required|date',
            'cuil' => ['required', 'numeric', 'unique:agente', new Cuil],
            'falta' => 'required|date',
            'fdesde' => 'required|date',
            'telefono' => 'numeric|nullable',
            'celular' => 'numeric|nullable',
            'email' => 'email|nullable|unique:agente',
            'idtipo_sexo' => 'required|numeric',
            'idtitulo' => 'required|numeric',
            'idtipo_especialidad' => 'numeric|nullable',
            'idtipo_nivel' => 'required|numeric',
            'idtipo_planta' => 'required|numeric',
            'idtipo_agrupamiento' => 'required|numeric',
            'idtipo_funcion' => 'required|numeric',
            'idefector' => 'required|numeric',
            'idservicio' => 'required|numeric',
        ];
    }
}
