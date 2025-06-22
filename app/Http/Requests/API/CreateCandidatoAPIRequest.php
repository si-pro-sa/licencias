<?php

namespace App\Http\Requests\API;

use App\Models\Candidato;
use InfyOm\Generator\Request\APIRequest;
use App\Rules\Cuil;

class CreateCandidatoAPIRequest extends APIRequest
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
            'documento' => 'required|integer',
            'cuil' => [
                'required',
                'integer',
                'unique:agente',
                function ($attribute, $value, $fail) {
                    $documento = request()->input('documento');
            
                    $validator = app()->makeWith(CUIL::class, ['documento' => $documento]);
            
                    if (!$validator->passes($attribute, $value)) {
                        $fail('El CUIL es inválido o no se corresponde con el DNI ingresado.');
                    }
                },
            ],
            'apellido' => 'required|string',
            'nombre' => 'required|string',
            'celular' => 'required|string',
            'fnacimiento' => 'nullable|date',
            'telefono' => 'string|nullable',
            'email' => 'nullable|email',
            'domicilio.calle' => 'nullable|string',
            'domicilio.numero' => 'nullable|integer',
            'domicilio.piso' => 'nullable|integer',
            'domicilio.departamento' => 'nullable|string',
            'domicilio.block' => 'nullable|string',
            'domicilio.codigo_postal' => 'nullable|integer',
            'domicilio.idlocalidad' => 'nullable|integer',
            'domicilio.idprovincia' => 'nullable|integer',
            'idtitulo' => 'nullable|integer',
            'idfuncion' => 'nullable|integer',
            'idtipo_nivel' => 'nullable|integer',
            'idtipo_sexo' => 'nullable|integer',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return Candidato::$messages;
    }
}
