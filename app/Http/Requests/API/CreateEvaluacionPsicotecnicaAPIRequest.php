<?php

namespace App\Http\Requests\API;

use App\Models\EvaluacionPsicotecnica;
use InfyOm\Generator\Request\APIRequest;

class CreateEvaluacionPsicotecnicaAPIRequest extends APIRequest
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
        return EvaluacionPsicotecnica::$rules;
    }

    public function messages()
    {
        return EvaluacionPsicotecnica::$messages;
    }
}
