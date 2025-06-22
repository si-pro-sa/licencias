<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateHorarioDependenciaRequest extends FormRequest
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
            'id' => 'nullable|numeric',
            'tipoHorario' => 'required|in:lv,ld,p',
            'idservicio' => 'required|numeric',
            'hora_desde' => 'nullable',
            'hora_hasta' => 'nullable',
            'dias.*.id' => 'nullable|numeric',
            'dias.*.isChecked' => 'required|boolean',
            'dias.*.nombre' => 'required|string',
            'dias.*.hora_desde' => 'nullable',
            'dias.*.hora_hasta' => 'nullable',
        ];
    }
}
