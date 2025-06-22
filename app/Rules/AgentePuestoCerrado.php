<?php

namespace App\Rules;

use App\Models\Puesto;
use Illuminate\Contracts\Validation\Rule;

class AgentePuestoCerrado implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $puesto = Puesto::where('idpuesto', $value)->first();

        if ($puesto != null) {
            return ($puesto->fhasta != null && $puesto->fhasta != 'null') ? true : false;
        } else {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El puesto del agente no se encuentra cerrado';
    }
}
