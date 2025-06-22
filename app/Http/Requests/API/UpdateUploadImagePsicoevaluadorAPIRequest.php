<?php

namespace App\Http\Requests\API;

use App\Models\PsicoEvaluador;
use InfyOm\Generator\Request\APIRequest;

class UpdateUploadImagePsicoevaluadorAPIRequest extends APIRequest
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
        $rules = PsicoEvaluador::$rulesImage;

        return $rules;
    }
}
