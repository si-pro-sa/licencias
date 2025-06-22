<?php

namespace App\Http\Requests\API;

use App\Models\CreateEvaluacionPsicotecnica;
use App\Models\EvaluacionPsicotecnica;
use InfyOm\Generator\Request\APIRequest;

class UpdateEvaluacionPsicotecnicaAPIRequest extends APIRequest
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
        $rules = EvaluacionPsicotecnica::$rulesUpdate;

        return $rules;
    }

    public function messages()
    {
        return EvaluacionPsicotecnica::$messages;
    }

}
